<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\PreEmploymentDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\DocumentRequestMail;
use App\Mail\AppointmentScheduledMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PreEmploymentController extends Controller
{
   public function index()
{
    $applications = JobApplication::with(['job', 'preEmploymentDocument', 'user'])
        ->whereHas('job', fn ($query) => $query->where('status', 'active'))
        ->where(function($query) {
            $query->where('status', JobApplication::STATUS_FINAL_INTERVIEW_PASSED)
                  ->orWhere('status', JobApplication::STATUS_PRE_EMPLOYMENT)
                  ->orWhere('status', JobApplication::STATUS_PRE_EMPLOYMENT_DOCUMENTS)
                  ->orWhere('status', JobApplication::STATUS_PRE_EMPLOYMENT_DOCS_REQUESTED)
                  ->orWhere('status', JobApplication::STATUS_PRE_EMPLOYMENT_DOCS_SUBMITTED)
                  ->orWhere('status', JobApplication::STATUS_PRE_EMPLOYMENT_VERIFICATION)
                  ->orWhere('status', JobApplication::STATUS_PRE_EMPLOYMENT_COMPLETED);
        })
        ->paginate(10);

    foreach ($applications as $application) {
        $previousStatus = $application->status;

        switch ($application->preEmploymentStatus()) {
            case 'completed':
                $application->status = JobApplication::STATUS_PRE_EMPLOYMENT_COMPLETED;
                $application->current_stage = 'Pre-employment Complete';
                break;

            case 'documents-completed':
                $application->status = JobApplication::STATUS_PRE_EMPLOYMENT_VERIFICATION;
                $application->current_stage = 'Documents Verification';
                break;

            case 'pending':
                $application->status = JobApplication::STATUS_PRE_EMPLOYMENT_DOCS_REQUESTED;
                $application->current_stage = 'Documents Requested';
                break;

            default:
                if ($application->status === JobApplication::STATUS_FINAL_INTERVIEW_PASSED) {
                    $application->status = JobApplication::STATUS_PRE_EMPLOYMENT;
                    $application->current_stage = 'Pre-employment Started';
                }
                break;
        }

        if ($application->isDirty()) {
            try {
                $application->save();
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    // Document-based counting (maintained from original)
    $completedCount = JobApplication::whereHas('preEmploymentDocument', function ($query) {
        $query->where([
            ['nbi_clearance_verified', true],
            ['police_clearance_verified', true],
            ['barangay_clearance_verified', true],
            ['coe_verified', true],
            ['drivers_license_verified', true],
            ['reference_check_verified', true],
            ['drug_test_verified', true],
            ['medical_exam_verified', true],
        ])->whereNotNull('drug_test_path')
          ->whereNotNull('medical_exam_path');
    })->count();

    $inProgressCount = JobApplication::whereHas('preEmploymentDocument', function ($query) {
        $query->where(function ($q) {
            $q->orWhere('nbi_clearance_verified', true)
                ->orWhere('police_clearance_verified', true)
                ->orWhere('barangay_clearance_verified', true)
                ->orWhere('coe_verified', true)
                ->orWhere('drivers_license_verified', true)
                ->orWhere('reference_check_verified', true)
                ->orWhere('drug_test_verified', true)
                ->orWhere('medical_exam_verified', true);
        })->where(function ($q) {
            $q->orWhere('nbi_clearance_verified', false)
                ->orWhere('police_clearance_verified', false)
                ->orWhere('barangay_clearance_verified', false)
                ->orWhere('coe_verified', false)
                ->orWhere('drivers_license_verified', false)
                ->orWhere('reference_check_verified', false)
                ->orWhere('drug_test_verified', false)
                ->orWhereNull('drug_test_path')
                ->orWhere('medical_exam_verified', false)
                ->orWhereNull('medical_exam_path');
        });
    })->count();

    $notStartedCount = JobApplication::whereDoesntHave('preEmploymentDocument')
        ->where('status', JobApplication::STATUS_FINAL_INTERVIEW_PASSED)
        ->count();

    return view('staff.recruitment.pre_employment', compact(
        'applications', 'completedCount', 'inProgressCount', 'notStartedCount'
    ));
}

    public function showScheduleForm()
    {
        $scheduledApplications = JobApplication::with(['job', 'preEmploymentDocument'])
            ->whereHas('preEmploymentDocument', fn ($query) => $query->whereNotNull('scheduled_date'))
            ->where('status', JobApplication::STATUS_FINAL_INTERVIEW_PASSED)
            ->get();

        $allApplications = JobApplication::with('job')
            ->where('status', JobApplication::STATUS_FINAL_INTERVIEW_PASSED)
            ->get();

        return view('staff.recruitment.schedule_pre_employment', [
            'applications' => $allApplications,
            'scheduledApplications' => $scheduledApplications,
        ]);
    }

    public function schedule(Request $request)
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:job_applications,id',
            'verification_type' => 'required|string|in:document_verification,background_check',
            'scheduled_date' => 'required|date|after:now',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $application = JobApplication::findOrFail($validated['application_id']);

        $document = $application->preEmploymentDocument()->updateOrCreate(
            ['job_application_id' => $application->id],
            $validated
        );

        try {
            Mail::to($application->email)->send(new AppointmentScheduledMail($application, $document));
            return redirect()->route('staff.recruitment.pre-employment.index')
                ->with('success', 'Appointment scheduled and email sent successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Appointment saved but email failed to send: ' . $e->getMessage());
        }
    }

    public function requestDocuments(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'documents' => 'required|array',
            'documents.*' => 'string|in:nbi_clearance,police_clearance,barangay_clearance,coe,drivers_license,medical_exam',
            'message' => 'required|string',
            'deadline' => 'required|date|after:today',
        ]);
    
        try {
            DB::beginTransaction();
    
            $documentData = [
                'document_request_message' => $validated['message'],
                'document_request_deadline' => $validated['deadline'],
                'requested_documents' => json_encode($validated['documents']),
            ];
    
            foreach ($validated['documents'] as $doc) {
                $documentData[$doc] = null;
                $documentData[$doc . '_verified'] = false;
            }
    
            $application->preEmploymentDocument()->updateOrCreate(
                ['job_application_id' => $application->id],
                $documentData
            );
    
            // Explicitly update application status
            $application->update([
                'status' => JobApplication::STATUS_PRE_EMPLOYMENT_DOCS_REQUESTED,
                'current_stage' => 'Pre-employment: Documents Requested'
            ]);
    
            Mail::to($application->email)->send(new DocumentRequestMail(
                $application,
                $validated['documents'],
                $validated['deadline'],
                $validated['message']
            ));
    
            DB::commit();
            return redirect()->back()->with('success', 'Document request sent successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
         
            return redirect()->back()->with('error', 'Failed to send document request: ' . $e->getMessage());
        }
    }

    public function showDocuments(JobApplication $application)
    {
        $document = PreEmploymentDocument::firstOrCreate(['job_application_id' => $application->id]);
        return view('staff.recruitment.pre_employment_documents', compact('application', 'document'));
    }

    // In PreEmploymentController.php
public function verifyDocument(JobApplication $application, $documentType, Request $request)
{
    $request->validate([
        'verified' => 'sometimes|boolean',
        'notes' => 'nullable|string|max:500',
    ]);

    $document = $application->preEmploymentDocument()->firstOrCreate(
        ['job_application_id' => $application->id]
    );

    $document->update([
        $documentType . '_verified' => $request->input('verified', false),
        $documentType . '_notes' => $request->input('notes'),
    ]);

    return back()->with('success', ucwords(str_replace('_', ' ', $documentType)) . ' verification updated.');
}

   // In your controller
public function verifyDocumentStorage(JobApplication $application)
{
    $document = $application->preEmploymentDocument;
    $requestedDocs = json_decode($document->requested_documents ?? '[]', true) ?? [];
    
    foreach ($requestedDocs as $docType) {
        $path = $document->{$docType.'_path'};
        if ($path && !Storage::disk('public')->exists($path)) {
            // Document file missing - log removed as requested
        }
    }
}

    public function updateReferenceCheck(JobApplication $application, Request $request)
    {
        $request->validate([
            'reference_check_verified' => 'required|boolean',
            'reference_check_notes' => 'nullable|string|max:500',
        ]);

        $document = PreEmploymentDocument::firstOrCreate(['job_application_id' => $application->id]);

        $document->update($request->only(['reference_check_verified', 'reference_check_notes']));

        return back()->with('success', 'Reference check updated.');
    }

    public function updateDrugTest(JobApplication $application, Request $request)
    {
        $request->validate([
            'drug_test_verified' => 'required|boolean',
            'drug_test_result' => 'required|in:negative,positive',
            'drug_test_date' => 'required|date',
        ]);

        $document = PreEmploymentDocument::firstOrCreate(['job_application_id' => $application->id]);

        $document->update($request->only([
            'drug_test_verified', 'drug_test_result', 'drug_test_date'
        ]));

        return back()->with('success', 'Drug test status updated.');
    }

    public function updateMedicalExam(JobApplication $application, Request $request)
    {
        $request->validate([
            'medical_exam_verified' => 'required|boolean',
            'medical_exam_result' => 'required|in:fit,unfit,conditional',
            'medical_exam_date' => 'required|date',
            'medical_exam_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $document = PreEmploymentDocument::firstOrCreate(['job_application_id' => $application->id]);

        if ($request->hasFile('medical_exam_path')) {
            $path = $request->file('medical_exam_path')->store('medical_exams', 'public');
            $document->medical_exam_path = $path;
        }

        $document->update([
            'medical_exam_verified' => $request->medical_exam_verified,
            'medical_exam_result' => $request->medical_exam_result,
            'medical_exam_date' => $request->medical_exam_date,
        ]);

        $document->save();

        return back()->with('success', 'Medical exam updated.');
    }
}
