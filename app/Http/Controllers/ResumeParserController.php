<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResumeParserController extends Controller
{
    public function showParser()
    {
        return view('tools.resume_parser');
    }

    public function parseResume(Request $request)
    {
        $request->validate([
            'resume' => 'required|file|mimes:pdf,docx|max:5120'
        ]);

        try {
            $file = $request->file('resume');
            $fileName = $file->getClientOriginalName();
            $text = $this->parseFile($file);
            $cleanedText = $this->cleanText($text);

            $filePath = $file->store('temp/resumes');

            $parsedData = [
                'name' => $this->extractName($cleanedText),
                'email' => $this->extractEmail($cleanedText),
                'phone' => $this->extractPhone($cleanedText),
                'skills' => $this->extractSkills($cleanedText),
                'experience' => $this->extractExperience($cleanedText),
                'education' => $this->extractEducation($cleanedText),
                'file_name' => $fileName,
                'file_path' => $filePath,
                'raw_text' => $cleanedText
            ];

            return view('tools.resume_parser', [
                'parsedData' => $parsedData,
                'rawText' => $cleanedText
            ]);

        } catch (\Exception $e) {
            Log::error("Resume Parse Error: " . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to parse resume. Please try again.']);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:applicants,email',
            'phone' => 'nullable|string|max:20',
            'skills' => 'nullable|string',
            'experience' => 'nullable|string',
            'education' => 'nullable|string',
            'original_file_data' => 'required|json',
        ]);

        try {
            $fileData = json_decode($request->original_file_data, true);
            $newPath = 'resumes/' . uniqid() . '_' . $fileData['file_name'];
            
            Storage::move($fileData['file_path'], 'public/' . $newPath);

            Applicant::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'skills' => $validated['skills'],
                'experience' => $validated['experience'],
                'education' => $validated['education'],
                'resume_file' => $newPath,
                'resume_original_name' => $fileData['file_name'],
                'raw_text' => $fileData['raw_text'],
                'parsed_data' => $fileData,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('tools.resume-parser')
                ->with('success', 'Applicant data saved successfully!');

        } catch (\Exception $e) {
            Log::error("Applicant Save Error: " . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to save applicant data.']);
        }
    }

    private function parseFile($file)
    {
        if ($file->getClientOriginalExtension() === 'pdf') {
            $parser = new Parser();
            return $parser->parseFile($file->getPathname())->getText();
        }
        
        if ($file->getClientOriginalExtension() === 'docx') {
            $phpWord = IOFactory::load($file->getPathname());
            $text = '';
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getElements')) {
                        foreach ($element->getElements() as $childElement) {
                            if (method_exists($childElement, 'getText')) {
                                $text .= $childElement->getText() . ' ';
                            }
                        }
                    } elseif (method_exists($element, 'getText')) {
                        $text .= $element->getText() . ' ';
                    }
                }
            }
            return $text;
        }
        
        throw new \Exception("Unsupported file type");
    }

    private function cleanText($text)
    {
        // Normalize line breaks and remove excessive whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Remove special characters but preserve basic punctuation
        $text = preg_replace('/[^\w\s.,\-@\n]/', '', $text);
        
        return trim($text);
    }

    private function extractName($text)
    {
        // Improved name extraction with multiple patterns
        $patterns = [
            // Common resume header format (Name at top)
            '/^([A-Z][a-z]+(?:\s+[A-Z][a-z]+)+)/',
            // Name followed by contact info
            '/([A-Z][a-z]+(?:\s+[A-Z][a-z]+)+)\s*(?:email|phone|contact)/i',
            // Name in title case with line breaks around it
            '/(?:\n|\r|\r\n)([A-Z][a-z]+(?:\s+[A-Z][a-z]+)+)(?:\n|\r|\r\n)/'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return trim($matches[1]);
            }
        }

        return null;
    }

    private function extractEmail($text)
    {
        // More comprehensive email pattern
        if (preg_match_all('/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/', $text, $matches)) {
            // Return the first email found (usually the primary one)
            return $matches[0][0];
        }
        return null;
    }

    private function extractPhone($text)
    {
        // Comprehensive phone number patterns
        $patterns = [
            // International format: +1 (123) 456-7890
            '/\+?\d{1,3}[-.\s]?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}/',
            // US/Canada format: (123) 456-7890 or 123-456-7890
            '/\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}/',
            // With extensions: 123-456-7890 x1234
            '/\d{3}[-.\s]?\d{3}[-.\s]?\d{4}\s*(?:x|ext|extension)?\s*\d{0,6}/i'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return trim($matches[0]);
            }
        }

        return null;
    }

    private function extractSkills($text) 
    {
        // 1. Normalize the text
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);
        $words = preg_split('/\s+/', $text);
    
        // 2. Identify skill phrases using linguistic patterns
        $skillPatterns = [
            '/proficient in (\w+(?:\s+\w+){0,2})/',
            '/experienced with (\w+(?:\s+\w+){0,2})/',
            '/skilled in (\w+(?:\s+\w+){0,2})/',
            '/knowledge of (\w+(?:\s+\w+){0,2})/',
            '/ability to (\w+(?:\s+\w+){0,3})/',
            '/strong (\w+(?:\s+\w+){0,1}) skills/',
            '/expertise in (\w+(?:\s+\w+){0,2})/',
            '/certified in (\w+(?:\s+\w+){0,2})/',
            '/trained in (\w+(?:\s+\w+){0,2})/',
            '/qualified in (\w+(?:\s+\w+){0,2})/'
        ];
    
        $foundSkills = [];
        foreach ($skillPatterns as $pattern) {
            if (preg_match_all($pattern, $text, $matches)) {
                foreach ($matches[1] as $skill) {
                    $foundSkills[] = ucwords(trim($skill));
                }
            }
        }
    
        // 3. Extract from skills sections using structural analysis
        $sectionPatterns = [
            '/skills:(.*?)(?:experience|education|$)/is',
            '/technical skills:(.*?)(?:soft skills|$)/is',
            '/qualifications:(.*?)(?:employment|$)/is',
            '/competencies:(.*?)(?:references|$)/is'
        ];
    
        foreach ($sectionPatterns as $pattern) {
            if (preg_match($pattern, $text, $sectionMatch)) {
                $skillsText = $sectionMatch[1];
                // Split by common delimiters
                $potentialSkills = preg_split('/[,;•\-•\n]+/', $skillsText);
                foreach ($potentialSkills as $skill) {
                    $skill = trim($skill);
                    if (strlen($skill) > 3 && !in_array(strtolower($skill), ['none', 'various'])) {
                        $foundSkills[] = ucwords($skill);
                    }
                }
            }
        }
    
        // 4. Analyze verb-noun pairs from experience sections
        $verbs = ['operate', 'maintain', 'manage', 'coordinate', 'repair', 'drive', 
                  'schedule', 'navigate', 'inspect', 'communicate', 'train'];
        
        $nouns = ['vehicle', 'bus', 'route', 'passenger', 'schedule', 'system', 
                 'safety', 'log', 'inventory', 'team', 'regulation'];
        
        $wordPairs = [];
        for ($i = 0; $i < count($words) - 1; $i++) {
            if (in_array($words[$i], $verbs) && in_array($words[$i+1], $nouns)) {
                $skill = $words[$i] . ' ' . $words[$i+1];
                $foundSkills[] = ucwords($skill);
            }
        }
    
        // 5. Filter and normalize results
        $filteredSkills = array_filter(array_unique($foundSkills), function($skill) {
            // Remove too generic terms
            return !in_array(strtolower($skill), [
                'team player', 'hard worker', 'detail oriented', 
                'fast learner', 'good communication'
            ]);
        });
    
        // 6. Score skills by frequency and position
        $scoredSkills = [];
        foreach ($filteredSkills as $skill) {
            $score = substr_count($text, strtolower($skill));
            // Boost score if appears in skills section
            if (preg_match('/skills:.*' . preg_quote($skill, '/') . '/i', $text)) {
                $score += 2;
            }
            $scoredSkills[$skill] = $score;
        }
    
        // Sort by score and return top skills
        arsort($scoredSkills);
        $finalSkills = array_keys(array_slice($scoredSkills, 0, 15));
    
        return $finalSkills ? implode(', ', $finalSkills) : null;
    }
    private function extractExperience($text)
    {
        // Extract total years of experience
        if (preg_match('/(\d+)\s*(years?|yrs?)\s*(?:of\s*)?experience/i', $text, $matches)) {
            return $matches[1] . ' years of experience';
        }

        // Extract experience from job history
        $experience = [];
        $lines = preg_split('/\r\n|\r|\n/', $text);
        $currentJob = null;

        foreach ($lines as $line) {
            // Look for job title patterns
            if (preg_match('/(?:position|role|job title):?\s*(.+)/i', $line, $matches)) {
                $currentJob = trim($matches[1]);
            }
            
            // Look for company patterns
            if (preg_match('/(?:company|employer|organization):?\s*(.+)/i', $line, $matches)) {
                if ($currentJob) {
                    $experience[] = $currentJob . ' at ' . trim($matches[1]);
                    $currentJob = null;
                }
            }
            
            // Look for duration patterns
            if (preg_match('/(\d{4})\s*[-–]\s*(\d{4}|present|current)/i', $line, $matches)) {
                $duration = $matches[1] . ' - ' . $matches[2];
                if ($currentJob) {
                    $experience[count($experience) - 1] .= " ($duration)";
                }
            }
        }

        if (!empty($experience)) {
            return implode("\n", $experience);
        }

        return null;
    }

    private function extractEducation($text)
    {
        // Look for education section
        if (preg_match('/education:?\s*([^\.]+)/i', $text, $matches)) {
            return trim($matches[1]);
        }

        // Look for degree patterns
        $education = [];
        $degreePatterns = [
            '/\b(?:bachelor|b\.?s\.?|b\.?a\.?|undergraduate)\b.*?\b(?:in|of)\b.*?\b\w+/i',
            '/\b(?:master|m\.?s\.?|m\.?a\.?|graduate)\b.*?\b(?:in|of)\b.*?\b\w+/i',
            '/\b(?:ph\.?d\.?|doctorate)\b.*?\b(?:in|of)\b.*?\b\w+/i',
            '/\b(?:diploma|certificate)\b.*?\b(?:in|of)\b.*?\b\w+/i'
        ];

        foreach ($degreePatterns as $pattern) {
            if (preg_match_all($pattern, $text, $matches)) {
                foreach ($matches[0] as $match) {
                    $education[] = trim($match);
                }
            }
        }

        // Look for university names
        $universities = ['university', 'college', 'institute', 'school'];
        $lines = preg_split('/\r\n|\r|\n/', $text);
        
        foreach ($lines as $line) {
            foreach ($universities as $uni) {
                if (stripos($line, $uni) !== false) {
                    $education[] = trim($line);
                    break;
                }
            }
        }

        return $education ? implode("\n", array_unique($education)) : null;
    }
}