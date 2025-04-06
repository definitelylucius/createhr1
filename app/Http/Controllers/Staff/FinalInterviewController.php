<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\FinalInterview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InterviewScheduled;

class FinalInterviewController extends Controller
{
    public function index()
    {
        $interviews = FinalInterview::with(['candidate', 'interviewer'])
            ->where('interviewer_id', auth()->id())
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10);
            
        return view('staff.final-interviews.index', compact('interviews'));
    }
    

    public function selectCandidate()
    {
        $candidates = Candidate::where('status', 'approved')
            ->whereDoesntHave('finalInterview')
            ->with(['job'])
            ->orderBy('last_name')
            ->paginate(10);
            
        return view('staff.final-interviews.select-candidate', compact('candidates'));
    }

    public function create(Candidate $candidate)
    {
        abort_if($candidate->status !== 'approved', 403, 'Only approved candidates can be scheduled for final interview');
        
        return view('staff.final-interviews.create', compact('candidate'));
    }

    public function store(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'notes' => 'nullable|string|max:500'
        ]);
    
        $interview = FinalInterview::create([
            'candidate_id' => $candidate->id,
            'interviewer_id' => auth()->id(),
            'scheduled_at' => $validated['scheduled_at'],
            'notes' => $validated['notes'],
            'status' => 'scheduled'
        ]);
    
        $candidate->update(['status' => 'final_interview_scheduled']);
        
        // Send email notification
        Mail::to($candidate->email)
            ->send(new InterviewScheduled($interview));
        
        return redirect()
            ->route('staff.final-interviews.show', $interview)
            ->with('success', 'Final interview scheduled successfully');
    }

    public function show(FinalInterview $interview)
    {
        abort_if($interview->interviewer_id !== auth()->id(), 403);
        
        return view('staff.final-interviews.show', [
            'interview' => $interview->load(['candidate', 'interviewer'])
        ]);
    }

    public function complete(Request $request, FinalInterview $interview)
    {
        abort_if($interview->interviewer_id !== auth()->id(), 403);
        abort_if($interview->status !== 'scheduled', 400, 'Only scheduled interviews can be completed');

        $validated = $request->validate([
            'result' => 'required|in:recommended,not_recommended',
            'feedback' => 'required|string|max:1000'
        ]);

        $interview->update([
            'status' => 'completed',
            'result' => $validated['result'],
            'feedback' => $validated['feedback'],
            'completed_at' => now()
        ]);

        $newStatus = $validated['result'] === 'recommended' 
            ? 'final_interview_completed'
            : 'rejected';
            
        $interview->candidate->update(['status' => $newStatus]);

        return redirect()
            ->route('staff.final-interviews.show', $interview)
            ->with('success', 'Interview results submitted');
    }
}