<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\OnboardingDocument;
use Illuminate\Support\Facades\Storage;
use App\Mail\OnboardingWelcomeEmail;
use Illuminate\Support\Facades\Mail;

class OnboardingController extends Controller
{
    public function start($applicationId)
    {
        $application = JobApplication::findOrFail($applicationId);
        
        // Create empty onboarding record if not exists
        OnboardingDocument::firstOrCreate(['application_id' => $applicationId]);
        
        // Send welcome email
        Mail::to($application->email)->send(new OnboardingWelcomeEmail($application));
        
        return view('staff.recruitment.onboarding', compact('application'));
    }

    public function uploadContract(Request $request, $applicationId)
    {
        $request->validate([
            'employment_contract' => 'required|file|mimes:pdf|max:5120'
        ]);

        $path = $request->file('employment_contract')->store("onboarding/{$applicationId}", 'public');
        
        $onboarding = OnboardingDocument::where('application_id', $applicationId)->firstOrFail();
        $onboarding->update(['employment_contract' => $path]);

        return back()->with('success', 'Employment contract uploaded.');
    }

    public function uploadTaxForms(Request $request, $applicationId)
    {
        $request->validate([
            'tax_forms' => 'required|file|mimes:pdf|max:5120'
        ]);

        $path = $request->file('tax_forms')->store("onboarding/{$applicationId}", 'public');
        
        $onboarding = OnboardingDocument::where('application_id', $applicationId)->firstOrFail();
        $onboarding->update(['tax_forms' => $path]);

        return back()->with('success', 'Tax forms uploaded.');
    }

    public function uploadPolicies(Request $request, $applicationId)
    {
        $request->validate([
            'company_policies' => 'required|file|mimes:pdf|max:5120'
        ]);

        $path = $request->file('company_policies')->store("onboarding/{$applicationId}", 'public');
        
        $onboarding = OnboardingDocument::where('application_id', $applicationId)->firstOrFail();
        $onboarding->update(['company_policies' => $path]);

        return back()->with('success', 'Company policies uploaded.');
    }

    public function uploadTrainingMaterials(Request $request, $applicationId)
    {
        $request->validate([
            'training_materials' => 'required|file|mimes:pdf,zip|max:10240'
        ]);

        $path = $request->file('training_materials')->store("onboarding/{$applicationId}", 'public');
        
        $onboarding = OnboardingDocument::where('application_id', $applicationId)->firstOrFail();
        $onboarding->update(['training_materials' => $path]);

        return back()->with('success', 'Training materials uploaded.');
    }

    public function completeOnboarding(Request $request, $applicationId)
    {
        $request->validate([
            'completion_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $onboarding = OnboardingDocument::where('application_id', $applicationId)->firstOrFail();
        
        // Verify all required documents are uploaded
        if (!$onboarding->employment_contract || !$onboarding->tax_forms || 
            !$onboarding->company_policies || !$onboarding->training_materials) {
            return back()->with('error', 'All required documents must be uploaded before completing onboarding.');
        }

        $onboarding->update([
            'completed' => true,
            'completed_at' => $request->completion_date,
            'completion_notes' => $request->notes
        ]);

        // Update application status to hired
        $application = JobApplication::findOrFail($applicationId);
        $application->update(['status' => 'hired']);

        // TODO: Create employee record here

        return redirect()->route('staff.dashboard')
            ->with('success', 'Onboarding completed successfully. Candidate is now hired.');
    }
}
