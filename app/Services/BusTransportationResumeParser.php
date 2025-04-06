<?php


namespace App\Services;

use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Classifiers\KNearestNeighbors;
use Rubix\ML\Transformers\TextNormalizer;
use Rubix\ML\Transformers\WordCountVectorizer;
use Rubix\ML\Transformers\TfIdfTransformer;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;

use Smalot\PdfParser\Parser;
class BusTransportationResumeParser
{
    protected $departmentSkills = [
        'Bus Transportation Department' => [
            'route planning', 'schedule optimization', 'fleet dispatch',
            'passenger capacity management', 'transit operations', 'transport logistics',
            'defensive driving', 'passenger boarding', 'fare collection',
            'route mapping', 'service reliability', 'on-time performance',
            'bus operation', 'driver scheduling', 'terminal operations'
        ],
        'Operation Department' => [
            'process improvement', 'KPI monitoring', 'resource allocation',
            'operational efficiency', 'performance metrics', 'service delivery',
            'demand forecasting', 'capacity planning', 'shift management',
            'operational analytics', 'service quality control', 'incident management',
            'vendor coordination', 'supply chain logistics', 'fuel management'
        ],
        'Maintenance Department' => [
            'preventive maintenance', 'vehicle inspection', 'diesel engines',
            'electrical systems', 'hydraulic systems', 'ASE certification',
            'bus refurbishment', 'brake systems', 'HVAC maintenance',
            'emissions control', 'lubrication systems', 'tire maintenance',
            'welding repairs', 'body work', 'diagnostic equipment'
        ],
        'Safety and Compliance Department' => [
            'DOT regulations', 'safety audits', 'OSHA compliance',
            'accident investigation', 'training programs', 'risk assessment',
            'safety inspections', 'emergency preparedness', 'hazard prevention',
            'regulatory compliance', 'insurance coordination', 'claims management',
            'drug testing programs', 'hours-of-service compliance', 'vehicle safety standards'
        ],
        'Customer Service Department' => [
            'passenger relations', 'complaint resolution', 'service quality',
            'communication skills', 'CRM systems', 'feedback collection',
            'customer retention', 'service recovery', 'accessibility services',
            'multilingual support', 'ticketing systems', 'information services',
            'passenger education', 'special needs assistance', 'crowd management'
        ],
        'Human Resource Department' => [
            'driver recruitment', 'employee relations', 'training development',
            'performance management', 'labor laws', 'union negotiations',
            'workforce planning', 'safety training', 'benefits administration',
            'disciplinary procedures', 'employee engagement', 'succession planning',
            'CDL training programs', 'certification tracking', 'shift bidding systems'
        ],
        'Finance Department' => [
            'transportation budgeting', 'financial reporting', 'cost analysis',
            'accounting software', 'ROI analysis', 'fare revenue management',
            'grant applications', 'fuel cost optimization', 'capital planning',
            'expense control', 'payroll management', 'vendor contracts',
            'ticketing revenue', 'subsidy management', 'financial forecasting'
        ]
    ];

    public function parseResume(string $filePath)
    {
        $text = $this->extractText($filePath);
        
        return [
            'skills' => $this->extractAllSkills($text),
            'experience_years' => $this->calculateExperienceYears($text),
            'education' => $this->extractEducation($text),
            'job_history' => $this->extractJobHistory($text),
            'raw_data' => $text
        ];
    }

    protected function extractText(string $filePath): string
{
    $text = '';

    // Check if file is a PDF
    if (str_ends_with(strtolower($filePath), '.pdf')) {
        $parser = new Parser();
        $pdf = $parser->parseFile(storage_path("app/{$filePath}"));
        $text = $pdf->getText();
    } else {
        // Fallback for other file types (basic text extraction)
        $text = file_get_contents(storage_path("app/{$filePath}"));
    }

    return $text ?? '';
}

    protected function extractAllSkills(string $text)
    {
        $foundSkills = [];
        $text = strtolower($text);
        
        // Check against all department skills
        foreach ($this->departmentSkills as $department => $skills) {
            foreach ($skills as $skill) {
                if (strpos($text, strtolower($skill)) !== false) {
                    $foundSkills[] = ucwords($skill);
                }
            }
        }
        
        // Extract from explicit skills section if exists
        if (preg_match('/(skills|qualifications|competencies)[\s\S]{0,20}?:([\s\S]*?)(\n\n|\r\n\r\n)/i', $text, $matches)) {
            $extraSkills = preg_split('/,|;|\||\//', $matches[2]);
            $foundSkills = array_merge($foundSkills, array_map('trim', $extraSkills));
        }
        
        return array_unique($foundSkills);
    }

    protected function calculateExperienceYears(string $text)
    {
        // Find year ranges (e.g., 2018-2022)
        preg_match_all('/(\d{4})\s*-\s*(\d{4}|Present)/', $text, $matches);
        
        $totalYears = 0;
        foreach ($matches[1] as $i => $startYear) {
            $endYear = $matches[2][$i] === 'Present' ? date('Y') : (int)$matches[2][$i];
            $totalYears += ($endYear - (int)$startYear);
        }
        
        // Check for explicit mention (e.g., "5 years experience")
        if (preg_match('/(\d+)\s*\+?\s*(years?)\s*(of)?\s*(relevant|total)?\s*experience/i', $text, $match)) {
            return max($totalYears, (int)$match[1]);
        }
        
        return $totalYears ?: null;
    }

    protected function extractEducation(string $text)
    {
        $education = [];
        
        // Degrees
        if (preg_match_all('/((Associate|Bachelor|Master|PhD)[^\.]+)/i', $text, $matches)) {
            $education = array_merge($education, $matches[0]);
        }
        
        // Transportation-specific
        if (preg_match_all('/((Transport|Logistics|Fleet|Maintenance|Safety)[^\.]+)/i', $text, $matches)) {
            $education = array_merge($education, $matches[0]);
        }
        
        // Certifications
        if (preg_match_all('/(CDL|ASE|DOT|OSHA|CPR|First Aid|HazMat)[^\.]+/i', $text, $matches)) {
            $education = array_merge($education, $matches[0]);
        }
        
        return implode("\n", array_unique($education)) ?: null;
    }

    protected function extractJobHistory(string $text)
    {
        $history = [];
        $lines = explode("\n", $text);
        
        foreach ($lines as $line) {
            if (preg_match(
                '/((Fleet|Transport|Bus|Transit|Logistics|Maintenance|Safety|Operations|Customer Service|HR|Human Resources|Finance).*(Manager|Supervisor|Coordinator|Specialist|Officer|Director|Administrator|Technician|Analyst|Dispatcher))/i', 
                $line
            )) {
                // Clean up and add to history
                $cleaned = preg_replace('/\s+/', ' ', trim($line));
                $history[] = $cleaned;
            }
        }
        
        return $history ?: null;
    }

    protected function extractJobTitles(string $text)
{

    
    preg_match_all('/(?i)([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*\s+(Manager|Supervisor|Specialist|Technician|Officer|Director|Analyst|Coordinator))/', $text, $matches);
    return array_unique($matches[0]);
}


}