<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\RecruitmentProcess;
use App\Models\PreEmploymentDocument;
use App\Models\User;

class AdminApplicantController extends Controller
{
    /**
     * Display all job applications
     */
    public function index()
    {
        $applications = JobApplication::with(['user', 'job', 'recruitmentProcess', 'preEmploymentDocument'])->latest()->get();
        return view('admin.applicants.index', compact('applications'));
    }

    /**
     * Show details for a specific applicant.
     */
    public function show($id)
    {
        $application = JobApplication::with([
            'user',
            'job',
            'recruitmentProcess',
            'preEmploymentDocument',
            'onboarding',
            'examEvaluations'
        ])->findOrFail($id);

        return view('admin.applicants.show', compact('application'));
    }

    /**
     * Update job application status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $application = JobApplication::findOrFail($id);
        $application->status = $request->status;
        $application->save();

        return redirect()->back()->with('success', 'Application status updated.');
    }

    /**
     * Store recruitment process updates
     */
    public function updateRecruitmentProcess(Request $request, $applicationId)
    {
        $request->validate([
            'stage' => 'required|string',
            'scheduled_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'notes' => 'nullable|string',
            'interviewer' => 'nullable|string',
            'location' => 'nullable|string',
            'meeting_link' => 'nullable|url',
            'passed' => 'nullable|boolean'
        ]);

        RecruitmentProcess::create([
            'application_id' => $applicationId,
            'stage' => $request->stage,
            'scheduled_at' => $request->scheduled_at,
            'completed_at' => $request->completed_at,
            'notes' => $request->notes,
            'interviewer' => $request->interviewer,
            'location' => $request->location,
            'meeting_link' => $request->meeting_link,
            'passed' => $request->passed ?? false,
        ]);

        return redirect()->back()->with('success', 'Recruitment stage updated.');
    }

    /**
     * Approve pre-employment documents manually
     */
    public function approveDocuments($id)
    {
        $document = PreEmploymentDocument::where('job_application_id', $id)->firstOrFail();

        $document->update([
            'nbi_clearance_verified' => true,
            'police_clearance_verified' => true,
            'barangay_clearance_verified' => true,
            'coe_verified' => true,
            'drivers_license_verified' => true,
            'reference_check_verified' => true,
            'drug_test_verified' => true,
            'medical_exam_verified' => true,
        ]);

        return redirect()->back()->with('success', 'All pre-employment documents approved.');
    }

    /**
     * Delete an applicant and related records
     */
    public function destroy($id)
    {
        $application = JobApplication::findOrFail($id);
        $application->delete(); // Deletes resume file too via model event

        return redirect()->route('admin.applicants.index')->with('success', 'Application deleted successfully.');
    }
}
