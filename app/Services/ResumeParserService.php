<?php

namespace App\Services;

use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ResumeParserService
{
    protected $parser;
    protected $departments = [
        'Bus Transportation Department',
        'Operation Department',
        'Maintenance Department',
        'Safety and Compliance Department',
        'Customer Service Department',
        'Human Resource Department',
        'Finance Department'
    ];

    public function __construct()
    {
        $this->parser = new Parser();
    }

    public function parseResume($filePath)
    {
        try {
            $pdf = $this->parser->parseFile($filePath);
            $text = $pdf->getText();
            
            return [
                'experience' => $this->extractTransportExperience($text),
                'departments' => $this->extractRelevantDepartments($text),
                'licenses' => $this->extractTransportLicenses($text),
                'skills' => $this->extractTransportSkills($text),
                'education' => $this->extractEducation($text),
                'safety_certifications' => $this->extractSafetyCertifications($text),
                'raw_text' => $text
            ];
        } catch (\Exception $e) {
            Log::error("Resume parsing failed: " . $e->getMessage());
            return null;
        }
    }

    protected function extractTransportExperience($text)
    {
        // Specialized for transportation industry experience
        if (preg_match('/(\d+)\s+years?[\s\w]*experience/i', $text, $matches)) {
            $years = (int)$matches[1];
            
            // Check for transportation-specific experience
            $transportKeywords = [
                'bus', 'transportation', 'fleet', 'transit', 'passenger', 
                'driving', 'logistics', 'dispatch', 'route planning'
            ];
            
            foreach ($transportKeywords as $keyword) {
                if (stripos($text, $keyword) !== false) {
                    return [
                        'total_years' => $years,
                        'transport_years' => $this->estimateTransportYears($text),
                        'is_transport_experience' => true
                    ];
                }
            }
            
            return ['total_years' => $years, 'transport_years' => 0];
        }
        
        return ['total_years' => 0, 'transport_years' => 0];
    }

    protected function estimateTransportYears($text)
    {
        // Try to find specific transportation job durations
        $pattern = '/((?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]* \d{4}).*?'
                 . '((?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]* \d{4}|\bPresent\b)/i';
        
        preg_match_all($pattern, $text, $matches);
        
        $totalMonths = 0;
        $transportKeywords = ['bus', 'driver', 'transport', 'fleet', 'transit'];
        
        foreach ($matches[0] as $key => $match) {
            foreach ($transportKeywords as $keyword) {
                if (stripos($match, $keyword) !== false) {
                    $start = $matches[1][$key];
                    $end = $matches[2][$key];
                    $totalMonths += $this->calculateMonthDifference($start, $end);
                    break;
                }
            }
        }
        
        return round($totalMonths / 12, 1);
    }

    protected function calculateMonthDifference($start, $end)
    {
        // Implementation to calculate months between dates
        // ...
    }

    protected function extractRelevantDepartments($text)
    {
        $foundDepartments = [];
        
        foreach ($this->departments as $department) {
            $simpleName = str_replace(' Department', '', $department);
            if (stripos($text, $simpleName) !== false || stripos($text, $department) !== false) {
                $foundDepartments[] = $department;
            }
        }
        
        return $foundDepartments;
    }

    protected function extractTransportLicenses($text)
    {
        $licenses = [];
        
        // Commercial Driver Licenses
        $cdlPatterns = [
            'CDL Class A', 'CDL Class B', 'CDL Class C', 
            'Commercial Driver License', 'PSA', 'Public Service License'
        ];
        
        foreach ($cdlPatterns as $pattern) {
            if (preg_match("/\b" . preg_quote($pattern, '/') . "\b/i", $text)) {
                $licenses[] = $pattern;
            }
        }
        
        // Safety Certifications (handled separately)
        // Maintenance Certifications
        $maintenanceCerts = [
            'ASE Certification', 'DOT Inspection', 'EVIT Certification',
            'Automotive Service Excellence'
        ];
        
        foreach ($maintenanceCerts as $cert) {
            if (stripos($text, $cert) !== false) {
                $licenses[] = $cert;
            }
        }
        
        return array_unique($licenses);
    }

    protected function extractTransportSkills($text)
    {
        $departmentSkills = [
            'Bus Transportation' => [
                'Route Planning', 'Passenger Safety', 'Schedule Adherence',
                'Defensive Driving', 'Fleet Knowledge', 'DOT Regulations'
            ],
            'Operation' => [
                'Dispatch Systems', 'GPS Navigation', 'Logistics Management',
                'Fuel Efficiency', 'Vehicle Inspection', 'Emergency Procedures'
            ],
            'Maintenance' => [
                'Diesel Engine Repair', 'Preventive Maintenance', 'Electrical Systems',
                'Brake Systems', 'HVAC Systems', 'Diagnostic Equipment'
            ],
            'Safety and Compliance' => [
                'DOT Compliance', 'OSHA Regulations', 'Safety Audits',
                'Accident Investigation', 'Training Programs', 'Hazard Recognition'
            ],
            'Customer Service' => [
                'Passenger Assistance', 'Conflict Resolution', 'Fare Collection',
                'Accessibility Services', 'Multilingual', 'Customer Relations'
            ],
            'Human Resource' => [
                'Recruitment', 'Labor Relations', 'Training Development',
                'Benefits Administration', 'Workers Compensation', 'Union Contracts'
            ],
            'Finance' => [
                'Budget Management', 'Grant Writing', 'Fuel Cost Analysis',
                'Fleet Financing', 'Payroll Systems', 'Procurement'
            ]
        ];
        
        $foundSkills = [];
        
        foreach ($departmentSkills as $department => $skills) {
            foreach ($skills as $skill) {
                if (stripos($text, $skill) !== false) {
                    $foundSkills[] = $skill;
                }
            }
        }
        
        // Extract from skills section if exists
        if (preg_match('/skills.*?\n(.*?)(?=\n\w+:|$)/is', $text, $match)) {
            $skillsText = $match[1];
            $potentialSkills = preg_split('/,|\n|\•|\-/', $skillsText);
            
            foreach ($potentialSkills as $potential) {
                $trimmed = trim($potential);
                if (!empty($trimmed)) {
                    $foundSkills[] = $trimmed;
                }
            }
        }
        
        return array_slice(array_unique($foundSkills), 0, 15);
    }

    protected function extractEducation($text)
    {
        // Look for transportation-specific education
        $transportPrograms = [
            'CDL Training Program', 'Transportation Management', 
            'Fleet Maintenance', 'Logistics Degree'
        ];
        
        foreach ($transportPrograms as $program) {
            if (stripos($text, $program) !== false) {
                return $program;
            }
        }
        
        // Standard education extraction
        if (preg_match('/education.*?\n(.*?)(?=\n\w+:|$)/is', $text, $match)) {
            return trim(preg_replace('/\s+/', ' ', $match[1]));
        }
        
        return 'Not specified';
    }

    protected function extractSafetyCertifications($text)
    {
        $certifications = [];
        $safetyCerts = [
            'DOT Certified', 'OSHA 10', 'OSHA 30', 'First Aid/CPR',
            'Defensive Driving Course', 'Hazardous Materials',
            'Passenger Assistance Certification', 'EVOC Certification',
            'Smith System Training', 'Transit Safety and Security'
        ];
        
        foreach ($safetyCerts as $cert) {
            if (stripos($text, $cert) !== false) {
                $certifications[] = $cert;
            }
        }
        
        return $certifications;
    }

    public function parseWithAI($filePath)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.ai_parser.key'),
                'Content-Type' => 'application/pdf'
            ])
            ->timeout(60)
            ->post('https://api.resumeparser.ai/v2/parse', [
                'file' => fopen($filePath, 'r')
            ]);

            if ($response->successful()) {
                $aiData = $response->json();
                
                return [
                    'experience' => $this->formatAIExperience($aiData),
                    'departments' => $this->extractRelevantDepartments($aiData['text']),
                    'licenses' => array_merge(
                        $this->extractTransportLicenses($aiData['text']),
                        $aiData['certifications'] ?? []
                    ),
                    'skills' => array_merge(
                        $this->extractTransportSkills($aiData['text']),
                        $aiData['skills'] ?? []
                    ),
                    'education' => $this->formatAIEducation($aiData),
                    'safety_certifications' => $this->extractSafetyCertifications($aiData['text']),
                    'raw_data' => $aiData
                ];
            }
            
            return $this->parseResume($filePath); // Fallback to basic parser
        } catch (\Exception $e) {
            Log::error("AI Resume parsing failed: " . $e->getMessage());
            return $this->parseResume($filePath);
        }
    }

    protected function formatAIExperience($aiData)
    {
        $transportKeywords = ['bus', 'transport', 'fleet', 'transit', 'driver'];
        $transportYears = 0;
        $transportPositions = [];
        
        foreach ($aiData['work_experience'] ?? [] as $job) {
            foreach ($transportKeywords as $keyword) {
                if (stripos($job['job_title'] . $job['description'], $keyword) !== false) {
                    $transportYears += $this->calculateYearsFromDuration($job['duration']);
                    $transportPositions[] = [
                        'title' => $job['job_title'],
                        'duration' => $job['duration'],
                        'company' => $job['company']
                    ];
                    break;
                }
            }
        }
        
        return [
            'total_years' => $this->calculateTotalExperience($aiData['work_experience']),
            'transport_years' => $transportYears,
            'transport_positions' => $transportPositions,
            'is_transport_experience' => $transportYears > 0
        ];
    }

    protected function formatAIEducation($aiData)
    {
        $transportPrograms = [
            'CDL', 'Transportation', 'Logistics', 'Fleet Management'
        ];
        
        foreach ($aiData['education'] ?? [] as $edu) {
            foreach ($transportPrograms as $program) {
                if (stripos($edu['degree'] . $edu['institution'], $program) !== false) {
                    return "{$edu['degree']} in {$program} from {$edu['institution']}";
                }
            }
        }
        
        return $aiData['education'][0]['degree'] ?? 'Not specified';
    }
}