<?php

// app/Http/Controllers/ApplicantDocumentController.php
namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\PreEmploymentDocument;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class ApplicantDocumentController extends Controller
{
    public function showUploadForm($applicationId)
    {
        $application = JobApplication::findOrFail($applicationId);
        return view('applicant.documents.upload', [
            'applicationId' => $applicationId,
            'application' => $application
        ]);
    }
    
    

    // Handle document upload
    public function uploadDocuments(Request $request, $applicationId)
{
    // Add logging at the start
    Log::info('Upload request received', [
        'application_id' => $applicationId,
        'document_type' => $request->document_type,
        'file' => $request->file('document_file') ? $request->file('document_file')->getClientOriginalName() : null
    ]);

    $request->validate([
        'document_type' => 'required|in:nbi_clearance,police_clearance,barangay_clearance,coe,drivers_license,drug_test,medical_exam',
        'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]); 

    $application = JobApplication::findOrFail($applicationId);
    
    // Debug the application and existing document
    Log::info('Application found', ['application' => $application->toArray()]);
    
    // Get or create the document record
    $document = $application->preEmploymentDocument;
    
    if (!$document) {
        Log::info('No existing document record, creating new one');
        $document = new PreEmploymentDocument();
        $document->job_application_id = $applicationId;
        $document->save();
        
        // Refresh the relationship
        $application->load('preEmploymentDocument');
    }

    Log::info('Document record', ['document' => $document->toArray()]);

    // Store the file
    $path = $request->file('document_file')->store('pre-employment-docs', 'public');
Log::info('File stored at path: ' . $path);

$fieldMap = [
    'nbi_clearance' => 'nbi_clearance_path',
    'police_clearance' => 'police_clearance_path',
    'barangay_clearance' => 'barangay_clearance_path',
    'coe' => 'coe_path',
    'drivers_license' => 'drivers_license_path',
    'drug_test' => 'drug_test_path',
    'medical_exam' => 'medical_exam_path',
];

    $field = $fieldMap[$request->document_type];
    
    Log::info('Updating field', ['field' => $field, 'path' => $path]);
    
    // Update the document
    $document->$field = $path;
    $saved = $document->save();
    
    Log::info('Save result', ['saved' => $saved, 'document_after' => $document->fresh()->toArray()]);

    return response()->json([
        'success' => $saved,
        'message' => $saved ? 'Document uploaded successfully!' : 'Failed to save document',
        'path' => $path
    ]);
}
    
    public function listDocuments($applicationId)
    {
        $application = JobApplication::with('preEmploymentDocument')->findOrFail($applicationId);
        
        if (!$application->preEmploymentDocument) {
            return response()->json([]);
        }
    
        $documents = [];
        $documentTypes = [
            'nbi_clearance_path' => 'NBI Clearance',
            'police_clearance_path' => 'Police Clearance',
            'barangay_clearance_path' => 'Barangay Clearance',
            'coe_path' => 'Certificate of Employment',
            'drivers_license_path' => 'Driver\'s License',
            'drug_test_path' => 'Drug Test Result',
            'medical_exam_path' => 'Medical Exam Result'
        ];
    
        foreach ($documentTypes as $field => $name) {
            if ($application->preEmploymentDocument->$field) {
                $documents[] = [
                    'name' => $name,
                    'path' => $application->preEmploymentDocument->$field,
                    'updated_at' => $application->preEmploymentDocument->updated_at
                ];
            }
        }
    
        return response()->json($documents);
    }
}
