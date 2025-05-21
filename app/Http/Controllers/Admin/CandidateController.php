<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\HiringProcessStage;
use App\Models\PreEmploymentCheck;
use App\Models\OnboardingTask;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::with(['job', 'currentStage'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.candidates.index', compact('candidates'));
    }

    public function show(Candidate $candidate)
    {
        $candidate->load([
            'documents',
            'hiringProcessStages',
            'preEmploymentChecks',
            'onboardingTasks'
        ]);

        return view('admin.candidates.show', compact('candidate'));
    }

    public function updateStage(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'stage' => 'required|in:initial_interview,demo,exam,final_interview,pre_employment,onboarding',
            'status' => 'required|in:pending,in_progress,completed,skipped',
            'scheduled_at' => 'nullable|date',
            'feedback' => 'nullable|string',
            'conducted_by' => 'nullable|exists:users,id'
        ]);

        $stage = $candidate->hiringProcessStages()->updateOrCreate(
            ['stage' => $validated['stage']],
            $validated
        );

        if ($validated['status'] === 'completed') {
            $this->progressCandidate($candidate);
        }

        return back()->with('success', 'Stage updated successfully');
    }

    protected function progressCandidate(Candidate $candidate)
    {
        $stages = [
            'initial_interview',
            'demo',
            'exam',
            'final_interview',
            'pre_employment',
            'onboarding',
            'hired'
        ];

        $currentIndex = array_search($candidate->status, $stages);
        if ($currentIndex !== false && isset($stages[$currentIndex + 1])) {
            $candidate->update(['status' => $stages[$currentIndex + 1]]);
        }
    }

    public function addDocument(Request $request, Candidate $candidate)
    {
        $request->validate([
            'type' => 'required|string',
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048'
        ]);

        $path = $request->file('document')->store('candidate_documents');

        $candidate->documents()->create([
            'type' => $request->type,
            'name' => $request->file('document')->getClientOriginalName(),
            'file_path' => $path
        ]);

        return back()->with('success', 'Document uploaded successfully');
    }
}