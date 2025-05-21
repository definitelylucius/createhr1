<?php


namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;
use App\Models\HiringProcessStage;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::with('job')->orderBy('created_at', 'desc')->get();
        return view('admin.candidates.index', compact('candidates'));
    }

    public function show(Candidate $candidate)
    {
        $candidate->load(['job', 'stages', 'calendarEvents', 'offerLetter']);
        return view('admin.candidates.show', compact('candidate'));
    }

    public function updateStatus(Candidate $candidate, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:applied,initial_interview,demo,exam,final_interview,pre_employment,hired,onboarding,rejected'
        ]);

        $candidate->update(['status' => $validated['status']]);

        // Create a new stage record if moving to a new stage
        if (!in_array($validated['status'], ['applied', 'rejected'])) {
            HiringProcessStage::firstOrCreate([
                'candidate_id' => $candidate->id,
                'stage' => $validated['status']
            ]);
        }

        return redirect()->back()->with('success', 'Candidate status updated successfully.');
    }

    public function parseResume(Candidate $candidate)
    {
        try {
            $filePath = storage_path('app/' . $candidate->resume_path);
            
            if (pathinfo($filePath, PATHINFO_EXTENSION) === 'pdf') {
                $parser = new PdfParser();
                $pdf = $parser->parseFile($filePath);
                $text = $pdf->getText();
            } else {
                // Handle DOC/DOCX files (would need appropriate library)
                $text = "Text extraction for non-PDF files would require additional libraries.";
            }

            $candidate->update(['resume_text' => $text]);
            
            return redirect()->back()->with('success', 'Resume parsed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to parse resume: ' . $e->getMessage());
        }
    }

    public function destroy(Candidate $candidate)
    {
        Storage::delete($candidate->resume_path);
        $candidate->delete();
        return redirect()->route('admin.candidates.index')->with('success', 'Candidate deleted successfully.');
    }
}