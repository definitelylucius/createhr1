<?php



namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Transformers\TextNormalizer;
use Rubix\ML\Transformers\WordCountVectorizer;
use Rubix\ML\Transformers\TfIdfTransformer;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;


class LocalResumeParser
{
    protected $departmentKeywords = [
        'Bus Transportation' => [
            'route planning', 'schedule optimization', 'fleet dispatch',
            'passenger capacity', 'transit operations', 'transport logistics',
            'passenger assistance','defensive driving',
            'route planning',
            'vehicle inspections',
            'safety regulations',
            'long-distance driving',
            'clean driving record'
        ],
        'Operations' => [
            'process improvement', 'KPI monitoring', 'resource allocation',
            'operational efficiency', 'performance metrics'
        ],
        'Maintenance' => [
            'preventive maintenance', 'vehicle inspection', 'diesel engines',
            'electrical systems', 'hydraulic systems', 'ASE certification'
        ],
        'Safety and Compliance' => [
            'DOT regulations', 'safety audits', 'OSHA compliance',
            'accident investigation', 'training programs'
        ],
        'Customer Service' => [
            'passenger relations', 'complaint resolution', 'service quality',
            'communication skills', 'CRM systems'
        ],
        'Human Resources' => [
            'recruitment', 'employee relations', 'training development',
            'performance management', 'labor laws'
        ],
        'Finance' => [
            'budgeting', 'financial reporting', 'cost analysis',
            'accounting software', 'ROI analysis'
        ]
    ];

    protected $mlModelPath;
    protected $pipeline;

    public function __construct()
    {
        $this->mlModelPath = storage_path('app/rubix-ml/resume_classifier.rbx');
        $this->initializePipeline();
    }

    protected function initializePipeline(): void
    {
        try {
            if (file_exists($this->mlModelPath)) {
                $this->pipeline = PersistentModel::load(new Filesystem($this->mlModelPath));
            }
        } catch (\Exception $e) {
            Log::error("Failed to initialize ML pipeline", ['error' => $e->getMessage()]);
        }
    }
    public function parse(string $filePath, string $department = null): array
    {
        $default = [
            'parser_used' => get_class($this),
            'storage_path' => $filePath,
            'file_exists' => file_exists($filePath),
            'file_size' => file_exists($filePath) ? filesize($filePath) : 0,
            'error' => null
        ];
    
        try {
            // 1. Verify physical file
            if (!file_exists($filePath)) {
                throw new \Exception("Physical file missing");
            }
    
            // 2. Extract text with validation
            $text = $this->extractText($filePath);
            if (empty(trim($text))) {
                throw new \Exception("No text extracted");
            }
    
            // 3. Parse components
            $result = [
                'skills' => $this->findTransportSkills($text) ?: [],
                'cdl_mentioned' => $this->detectCDL($text),
                'match_score' => $this->calculateMatchScore($text, $department),
                'raw_text' => Str::limit($text, 500), // Store sample
                'parser_used' => get_class($this),
                'success' => true
            ];
    
            return $result;
    
        } catch (\Exception $e) {
            Log::error("Parsing failed", [
                'file' => basename($filePath),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $default['error'] = $e->getMessage();
            return $default;
        }
    }
    

    private function normalizeFilePath(string $filePath): string
{
    // Handle storage path vs absolute path
    if (strpos($filePath, 'resumes/') === 0) {
        return Storage::disk('resumes')->path($filePath);
    }

    // Convert relative paths to absolute
    if (!realpath($filePath)) {
        $absolutePath = Storage::disk('resumes')->path($filePath);
        if (file_exists($absolutePath)) {
            return $absolutePath;
        }
    }

    return $filePath;
}

private function extractTextFromPdf(string $filePath): string
{
    try {
        $parser = new Parser();
        
        // Verify PDF is not corrupted
        if (!filesize($filePath)) {
            throw new \Exception("PDF file is empty");
        }

        $pdf = $parser->parseFile($filePath);
        $text = $pdf->getText();
        
        // Basic validation of extracted text
        if (empty(trim($text))) {
            throw new \Exception("PDF parser returned empty text");
        }

        return $text;
    } catch (\Exception $e) {
        Log::error("PDF parsing failed", [
            'file' => basename($filePath),
            'error' => $e->getMessage()
        ]);
        throw new \Exception("Failed to parse PDF: " . $e->getMessage());
    }
}

    protected function parseWithKeywords(string $text, ?string $department): array
    {
        $isValidDepartment = $this->validateDepartment($department);

        return [
            'skills' => $this->findTransportSkills($text),
            'department_skills' => $isValidDepartment ? $this->findDepartmentSkills($text, $department) : [],
            'cdl_mentioned' => $this->detectCDL($text),
            'experience' => $this->findExperience($text),
            'certifications' => $isValidDepartment ? $this->findCertifications($text, $department) : [],
            'match_score' => $isValidDepartment ? $this->calculateMatchScore($text, $department) : 0,
            'raw_text' => $text,
        ];
    }

    protected function parseWithML(string $text): array
    {
        if (!$this->pipeline) {
            return ['ml_available' => false];
        }

        try {
            // Create a dataset with the resume text
            $dataset = new Unlabeled([$text]);
            
            // Make predictions
            $predictions = $this->pipeline->predict($dataset);
            
            // For a classifier, the first prediction is what we want
            $primaryPrediction = $predictions[0] ?? null;
            
            // Get probability estimates if available
            $probabilities = [];
            if (method_exists($this->pipeline, 'proba')) {
                $probArray = $this->pipeline->proba($dataset);
                $probabilities = $probArray[0] ?? [];
            }
            
            return [
                'ml_available' => true,
                'primary_department_prediction' => $primaryPrediction,
                'department_probabilities' => $probabilities,
                'department_match_score' => $this->calculateMLMatchScore($primaryPrediction, $probabilities),
                'skills' => $this->extractSkillsWithML($text),
            ];
        } catch (\Exception $e) {
            Log::error("ML parsing failed", ['error' => $e->getMessage()]);
            return ['ml_available' => false, 'error' => $e->getMessage()];
        }
    }

    protected function calculateMLMatchScore(?string $prediction, array $probabilities): int
    {
        if (!$prediction || empty($probabilities)) {
            return 0;
        }
        
        // Get the probability for the predicted class
        $probability = $probabilities[$prediction] ?? 0;
        
        // Convert to percentage (0-100 scale)
        return (int)round($probability * 100);
    }

    protected function extractSkillsWithML(string $text): array
    {
        // This would use your trained ML model for skill extraction
        // For now, returning empty array - implement based on your model
        return [];
    }


    private function normalizeText(string $text): string
    {
        // First fix common encoding/OCR issues
        $text = str_replace(
            ['•', '·', '', '\uf0b7', '\u2022'], 
            '-', 
            $text
        );
        
        // Convert to single line breaks
        $text = preg_replace('/\R+/u', "\n", $text);
        
        // Remove excessive whitespace but preserve line breaks
        $text = preg_replace('/[^\S\n]+/', ' ', $text);
        
        return trim($text);
    }
    private function detectCDL(string $text): bool
    {
        return (bool)preg_match(
            '/\b(CDL|Commercial Driver[\'´]?s? License|Professional Driver|Driver\'?s? License|Licensed Professional Driver)\b/i', 
            $text
        );
    }

    private function findTransportSkills(string $text): array
    {
        // Match multiple possible section headings
        preg_match_all('/(?:Skills|Qualifications|Skills Summary|Related Skills)[:\s]*(.+?)(?=(?:Certifications|Education|$))/is', $text, $matches);
        
        $skills = [];
        if (!empty($matches[1])) {
            // Extract bullet points and comma-separated items
            preg_match_all('/[•\-]\s*([^\n]+)|([A-Z][a-z]+(?: [A-Z][a-z]+)*(?=,|\.|$))/', $matches[1][0], $skillMatches);
            
            $skills = array_filter(array_map('trim', 
                array_merge($skillMatches[1], $skillMatches[2])
            ));
        }
        
        return array_unique($skills);
    }
    private function findExperience(string $text): array
{
    // Simplified and more robust regex pattern
    $pattern = '/
        (?:(\d{1,3})\s*(?:years?|yrs?)\s+of\s+experience\s+in\s+(.+?)(?=\n|$))  # Years format
        |
        (?:(\w+\s+\d{4})\s*-\s*(\w+\s+\d{4}|Present)\s+(?:as\s+)?(.+?)(?:\s+at\s+|,\s*)(.+?)(?=\n|$)  # Traditional format
        |
        (?:(\w+\s+\d{4})\s*-\s*(\w+\s+\d{4}|Present)\s*:\s*(.+?)(?=\n|$)  # Alternate format
    /ix';

    preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);

    $experience = [];
    foreach ($matches as $match) {
        if (!empty($match[1])) { // Years format
            $experience[] = [
                'years' => (int)$match[1],
                'description' => trim($match[2])
            ];
        } elseif (!empty($match[3])) { // Traditional format
            $experience[] = [
                'duration' => trim($match[3] . ' - ' . $match[4]),
                'position' => trim($match[5]),
                'company' => trim($match[6])
            ];
        } elseif (!empty($match[7])) { // Alternate format
            $experience[] = [
                'duration' => trim($match[7] . ' - ' . $match[8]),
                'description' => trim($match[9])
            ];
        }
    }
    return $experience;
}

private function safePregMatchAll(string $pattern, string $text): array
{
    try {
        preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);
        return $matches ?: [];
    } catch (\Exception $e) {
        Log::error("Regex failed", [
            'pattern' => $pattern,
            'error' => $e->getMessage()
        ]);
        return [];
    }
}
    
    private function calculateExperienceYears(string $text): int
    {
        // First try to find explicit year counts (including bracketed numbers)
        if (preg_match('/\[?(\d{1,3})\]?\s*(?:years?|yrs?)\s+of\s+experience/i', $text, $match)) {
            return min((int)$match[1], 20);
        }
    
        // Fallback to date range calculation
        $totalYears = 0;
        $experience = $this->findExperience($text);
        
        foreach ($experience as $job) {
            if (isset($job['duration'])) {
                if (preg_match('/(\d{4})\s*-\s*(\d{4}|Present)/', $job['duration'], $years)) {
                    $startYear = (int)$years[1];
                    $endYear = $years[2] === 'Present' ? date('Y') : (int)$years[2];
                    $totalYears += max(0, $endYear - $startYear);
                }
            } elseif (isset($job['years'])) {
                $totalYears += $job['years'];
            }
        }
        
        return min($totalYears, 20);
    }
    
    private function calculateMatchScore(string $text, ?string $department): int
    {
        if (!$department || !$this->validateDepartment($department)) {
            return 0;
        }
    
        $score = 0;
        
        // Department skills (10 points each)
        $departmentSkills = $this->findDepartmentSkills($text, $department);
        $score += count($departmentSkills) * 10;
        
        // Certifications (15 points each)
        $certifications = $this->findCertifications($text, $department);
        $score += count($certifications) * 15;
        
        // CDL bonus for relevant departments (25 points)
        if (in_array($department, ['Bus Transportation', 'Safety and Compliance'])) {
            $score += $this->detectCDL($text) ? 25 : 0;
        }
        
        // Experience years (2 points per year up to 30)
        $expYears = $this->calculateExperienceYears($text);
        $score += min($expYears * 2, 30);
        
        return min($score, 100);
    }

    private function findDepartmentSkills(string $text, string $department): array
    {
        if (!isset($this->departmentKeywords[$department])) {
            return [];
        }
        
        $skills = [];
        foreach ($this->departmentKeywords[$department] as $keyword) {
            if (stripos($text, $keyword) !== false) {
                $skills[] = $keyword;
            }
        }
        
        return array_values(array_unique($skills));
    }

    private function findCertifications(string $text, string $department): array
    {
        // Example certifications for Bus Transportation
        $certifications = [
            'CDL', 'DOT Certification', 'First Aid/CPR', 'Passenger Endorsement',
            'Hazmat Endorsement', 'Defensive Driving Course'
        ];
        
        if ($department === 'Bus Transportation') {
            return array_filter($certifications, function ($cert) use ($text) {
                return stripos($text, $cert) !== false;
            });
        }
        
        return [];
    }


    private function validateDepartment(?string $department): bool
    {
        return in_array($department, array_keys($this->departmentKeywords), true);
    }

    private function getAbsolutePath(string $filePath): string
    {
        return Storage::disk('resumes')->path($filePath);
    }

    private function extractText(string $filePath): string
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        switch (strtolower($extension)) {
            case 'pdf':
                return $this->extractTextFromPdf($filePath);
            case 'docx':
                return $this->extractTextFromDocx($filePath);
            default:
                throw new \Exception("Unsupported file type: {$extension}");
        }
    }
    

    private function extractTextFromDoc(string $filePath): string
    {
        // Implement DOC file extraction logic here if needed
        return '';
    }
    private function extractTextFromTxt(string $filePath): string
    {
        return file_get_contents($filePath);
    }
}