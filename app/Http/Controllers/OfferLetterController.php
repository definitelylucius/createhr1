<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\OfferLetter;
use Illuminate\Support\Facades\Storage;
use App\Mail\OfferLetterEmail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class OfferLetterController extends Controller
{
    public function prepare($applicationId)
    {
        $application = JobApplication::with('job')->findOrFail($applicationId);
        return view('admin.recruitment.offer_letter', compact('application'));
    }

    public function generate(Request $request, $applicationId)
    {
        $request->validate([
            'salary' => 'required|numeric',
            'position' => 'required|string',
            'start_date' => 'required|date',
            'terms' => 'required|string',
            'benefits' => 'required|string'
        ]);

        $application = JobApplication::with('job')->findOrFail($applicationId);
        
        $data = [
            'application' => $application,
            'salary' => $request->salary,
            'position' => $request->position,
            'start_date' => $request->start_date,
            'terms' => $request->terms,
            'benefits' => $request->benefits,
            'today' => now()->format('F j, Y')
        ];

        $pdf = Pdf::loadView('pdf.offer_letter', $data);
        $filename = 'offer_letter_' . $applicationId . '_' . now()->format('YmdHis') . '.pdf';
        $path = 'offer_letters/' . $filename;
        
        Storage::disk('public')->put($path, $pdf->output());

        // Save offer letter record
        OfferLetter::updateOrCreate(
            ['application_id' => $applicationId],
            [
                'file_path' => $path,
                'sent_at' => now()
            ]
        );

        return back()->with('success', 'Offer letter generated successfully.')
            ->with('offer_path', Storage::url($path));
    }

    public function send(Request $request, $applicationId)
    {
        $request->validate([
            'meeting_link' => 'nullable|url',
            'meeting_time' => 'nullable|date_format:Y-m-d\TH:i',
            'additional_notes' => 'nullable|string'
        ]);

        $application = JobApplication::findOrFail($applicationId);
        $offerLetter = OfferLetter::where('application_id', $applicationId)->firstOrFail();
        
        // Send email with offer letter
        Mail::to($application->email)->send(new OfferLetterEmail($application, [
            'offer_path' => Storage::url($offerLetter->file_path),
            'meeting_link' => $request->meeting_link,
            'meeting_time' => $request->meeting_time,
            'additional_notes' => $request->additional_notes
        ]));

        return back()->with('success', 'Offer letter sent to candidate.');
    }

    public function collectSignature(Request $request, $applicationId)
    {
        $request->validate([
            'signature' => 'required|string' // Base64 encoded signature
        ]);

        $offerLetter = OfferLetter::where('application_id', $applicationId)->firstOrFail();
        
        // Decode and store signature
        $signature = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->signature));
        $signatureName = 'signature_' . $applicationId . '_' . now()->format('YmdHis') . '.png';
        $signaturePath = 'signatures/' . $signatureName;
        
        Storage::disk('public')->put($signaturePath, $signature);

        $offerLetter->update([
            'signature_path' => $signaturePath,
            'signed_at' => now()
        ]);

        // Update application status
        $application = JobApplication::findOrFail($applicationId);
        $application->update(['status' => 'offer_accepted']);

        // Proceed to onboarding
        return redirect()->route('onboarding.start', $applicationId)
            ->with('success', 'Signature collected. Proceeding to onboarding.');
    }

    public function view($applicationId)
    {
        $offerLetter = OfferLetter::where('application_id', $applicationId)->firstOrFail();
        return response()->file(Storage::disk('public')->path($offerLetter->file_path));
    }

    public function showSignaturePage($applicationId)
{
    $application = JobApplication::findOrFail($applicationId);
    $offerLetter = OfferLetter::where('application_id', $applicationId)->firstOrFail();
    
    return view('offer_letter.sign', compact('application', 'offerLetter'));
}

public function processSignature(Request $request, $applicationId)
{
    $request->validate([
        'signature' => 'required|string' // Base64 encoded signature image
    ]);
    
    $offerLetter = OfferLetter::where('application_id', $applicationId)->firstOrFail();
    
    // Save signature image
    $signature = $this->saveSignatureImage($request->signature, $applicationId);
    
    $offerLetter->update([
        'signature_path' => $signature,
        'signed_at' => now()
    ]);
    
    // Update application status
    $application = JobApplication::findOrFail($applicationId);
    $application->update(['status' => 'offer_accepted']);
    
    return redirect()->route('onboarding.start', $applicationId)
        ->with('success', 'Offer letter signed successfully. Onboarding process started.');
}

private function saveSignatureImage($signatureData, $applicationId)
{
    $folderPath = "signatures/{$applicationId}";
    $image_parts = explode(";base64,", $signatureData);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    $file = $folderPath . '/' . uniqid() . '.' . $image_type;
    
    Storage::disk('public')->put($file, $image_base64);
    
    return $file;
}
}