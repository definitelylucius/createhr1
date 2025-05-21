<?php 

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\HiringProcessStage;
use App\Models\PreEmploymentCheck;
use Illuminate\Http\Request;
use App\Services\CalendarService;
use App\Services\EmailService;

class HiringProcessController extends Controller
{
    protected $calendarService;
    protected $emailService;

    public function __construct(CalendarService $calendarService, EmailService $emailService)
    {
        $this->calendarService = $calendarService;
        $this->emailService = $emailService;
    }

    public function scheduleStage(Candidate $candidate, Request $request)
    {
        $validated = $request->validate([
            'stage' => 'required|in:initial_interview,demo,exam,final_interview,pre_employment',
            'scheduled_at' => 'required|date',
            'interviewer' => 'required|string',
            'notes' => 'nullable|string',
            'meeting_link' => 'nullable|url',
            'location' => 'nullable|string',
        ]);

        // Update or create the stage
        $stage = HiringProcessStage::updateOrCreate(
            [
                'candidate_id' => $candidate->id,
                'stage' => $validated['stage']
            ],
            [
                'scheduled_at' => $validated['scheduled_at'],
                'interviewer' => $validated['interviewer'],
                'notes' => $validated['notes'],
                'result' => 'pending'
            ]
        );

        // Create calendar event (except for pre-employment which is document collection)
        if ($validated['stage'] !== 'pre_employment') {
            $event = $this->calendarService->createEvent([
                'candidate_id' => $candidate->id,
                'stage_id' => $stage->id,
                'title' => ucfirst(str_replace('_', ' ', $validated['stage'])) . ' with ' . $candidate->full_name,
                'start_time' => $validated['scheduled_at'],
                'end_time' => date('Y-m-d H:i:s', strtotime($validated['scheduled_at'] . ' +1 hour')),
                'location' => $validated['location'],
                'meeting_link' => $validated['meeting_link'],
                'description' => $validated['notes'] ?? 'Scheduled interview for ' . $candidate->full_name,
            ]);

            // Send email invitation (except for pre-employment)
            $this->emailService->sendInterviewInvitation($candidate, $stage, $event);
        } else {
            // For pre-employment stage, create default checks
            $this->createDefaultPreEmploymentChecks($candidate);
        }

        // Update candidate status
        $candidate->update(['status' => $validated['stage']]);

        return redirect()->back()->with('success', 'Stage scheduled successfully.');
    }

    public function completeStage(Candidate $candidate, HiringProcessStage $stage, Request $request)
    {
        $validated = $request->validate([
            'result' => 'required|in:pass,fail',
            'feedback' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $stage->update([
            'completed_at' => now(),
            'result' => $validated['result'],
            'feedback' => $validated['feedback'],
            'notes' => $validated['notes'] ?? $stage->notes,
        ]);

        // Update calendar event if exists
        if ($stage->calendarEvent) {
            $stage->calendarEvent->update(['status' => 'completed']);
        }

        if ($validated['result'] === 'pass') {
            // For pre-employment, verify all checks are completed before moving forward
            if ($stage->stage === 'pre_employment') {
                $incompleteChecks = $candidate->preEmploymentChecks()
                    ->where('status', '!=', 'completed')
                    ->exists();
                
                if ($incompleteChecks) {
                    return redirect()->back()
                        ->with('error', 'Cannot complete pre-employment stage - some checks are still pending.');
                }
            }
            
            $candidate->moveToNextStage();
        } else {
            $candidate->update(['status' => 'rejected']);
        }

        return redirect()->back()->with('success', 'Stage completed successfully.');
    }

    /**
     * Create default pre-employment checks for a candidate
     */
    protected function createDefaultPreEmploymentChecks(Candidate $candidate)
    {
        $defaultChecks = [
            'nbi_clearance',
            'barangay_clearance',
            'police_clearance',
            'drug_test',
            'medical_exam'
        ];

        foreach ($defaultChecks as $checkType) {
            PreEmploymentCheck::firstOrCreate([
                'candidate_id' => $candidate->id,
                'type' => $checkType
            ], [
                'status' => 'pending'
            ]);
        }
    }
}