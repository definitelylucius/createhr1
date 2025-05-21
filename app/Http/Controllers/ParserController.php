<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Str;
use App\Models\JobApplication;

class ParserController extends Controller
{
    public function parseResume(Request $request)
    {
        $validated = $request->validate([
            'file_path' => 'required|string',
            'job_id' => 'required|integer'
        ]);

        try {
            $absolutePath = storage_path('app/candidate_documents/' . $validated['file_path']);
            $absolutePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $absolutePath);

            if (!file_exists($absolutePath)) {
                throw new \Exception("File not found at: {$absolutePath}");
            }

            $parsedData = $this->parseResumeWithPhp($absolutePath);

            // Update the application record
            JobApplication::where('job_id', $validated['job_id'])
                ->where('resume', $validated['file_path'])
                ->update([
                    'parsed_data' => json_encode($parsedData),
                    'parser_used' => 'PhpParser',
                    'skills' => $this->extractCombinedSkills($parsedData),
                    'application_status' => 'processed'
                ]);

            return response()->json([
                'success' => true,
                'data' => $parsedData
            ]);

        } catch (\Exception $e) {
            Log::error("ParserController error: " . $e->getMessage(), [
                'file_path' => $validated['file_path'] ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to parse resume',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected function parseResumeWithPhp($filePath)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        
        if ($extension === 'pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($filePath);
            $text = $pdf->getText();
        } elseif ($extension === 'docx') {
            $text = $this->extractTextFromDocx($filePath);
        } else {
            throw new \Exception("Unsupported file type: {$extension}");
        }

        return [
            'skills' => $this->extractSkillsFromText($text),
            'experience' => $this->extractExperienceFromText($text),
            'education' => $this->extractEducationFromText($text),
            'raw_text' => Str::limit($text, 1000),
            'parser_used' => 'PhpParser',
            'success' => true,
            'timestamp' => now()->toDateTimeString()
        ];
    }

    protected function extractTextFromDocx($path)
    {
        $phpWord = IOFactory::load($path);
        $text = '';
        
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    foreach ($element->getElements() as $textElement) {
                        if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                            $text .= $textElement->getText() . ' ';
                        }
                    }
                }
            }
        }
        
        return $text;
    }

    protected function extractSkillsFromText($text)
    {
        $skillKeywords = config('resume_parser.skills', [
            'PHP', 'Laravel', 'JavaScript', 'Vue', 'React',
            'MySQL', 'Git', 'AWS', 'Docker', 'API'
        ]);
        
        $foundSkills = [];
        
        foreach ($skillKeywords as $skill) {
            if (stripos($text, $skill) !== false) {
                $foundSkills[] = $skill;
            }
        }
        
        if (preg_match('/Skills:(.*?)(?:\n\n|$)/i', $text, $matches)) {
            $skillsSection = $matches[1];
            foreach ($skillKeywords as $skill) {
                if (stripos($skillsSection, $skill) !== false) {
                    $foundSkills[] = $skill;
                }
            }
        }
        
        return array_unique($foundSkills);
    }

    protected function extractExperienceFromText($text)
    {
        $experience = [];
        
        if (preg_match_all('/(\d+)\s*(years?|yrs?)/i', $text, $matches)) {
            $experience['years'] = max($matches[1]);
        }
        
        if (preg_match_all('/\b(?:Senior|Junior|Lead)?\s*(Developer|Engineer|Designer|Manager)\b/i', $text, $matches)) {
            $experience['positions'] = array_unique($matches[0]);
        }
        
        return $experience;
    }

    protected function extractEducationFromText($text)
    {
        $education = [];
        
        if (preg_match('/Education(.*?)(?:\n\n|$)/i', $text, $matches)) {
            $education['section'] = trim($matches[1]);
        }
        
        $degreeKeywords = ['Bachelor', 'Master', 'PhD', 'Diploma', 'Associate'];
        foreach ($degreeKeywords as $degree) {
            if (stripos($text, $degree) !== false) {
                $education['degrees'][] = $degree;
            }
        }
        
        return $education;
    }

    protected function extractCombinedSkills(array $parsedData): string
    {
        return implode(', ', array_unique(
            $parsedData['skills'] ?? []
        ));
    }
}