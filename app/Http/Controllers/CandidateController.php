<?php



namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\CandidateTag;
use App\Models\LicenseVerification;
use App\Models\CandidateTest;
use App\Models\CandidateDocument;
use App\Services\MLResumeParser;
use App\Models\ParsedResume;
use App\Services\BusTransportationResumeParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CandidateController extends Controller
{
    // Display application form
    public function create()
    {
        return view('candidates.create');
    }

    // Store new candidate application
    public function store(Request $request)
{
    // Validate request
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:candidates,email',
        'phone' => 'nullable|string|max:20',
        'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        'job_id' => 'required|exists:jobs,id', // ✅ Validate that job_id exists in the jobs table
    ]);

    // Get the authenticated user's ID
    $userId = auth()->id();

    // Create candidate and associate with the user
    $candidate = Candidate::create([
        'first_name' => $validated['first_name'],
        'last_name' => $validated['last_name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'status' => 'new',
        'user_id' => $userId,
        'job_id' => $validated['job_id'], // ✅ Use validated data
    ]);

    // Store resume
    if ($request->hasFile('resume')) {
        $file = $request->file('resume');
        $path = $file->store('candidate_documents');

        // Create CandidateDocument record
        $document = CandidateDocument::create([
            'candidate_id' => $candidate->id,
            'type' => 'resume',
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
        ]);

        // Now call the parseResume method, passing the CandidateDocument instance
        $this->parseResume($document); // Pass the CandidateDocument model instance here
    }

    // Redirect to the success route with the candidate ID
    return redirect()->route('application.success', $candidate->id)
        ->with('success', 'Application submitted successfully!');
}

public function success($id)
{
    // Find the candidate or application based on the provided ID
    $candidate = Candidate::findOrFail($id);

    // Return the success view, passing the candidate data
    return view('welcome', ['candidate' => $candidate]);
}
    

    // Staff dashboard - list candidates
    public function index()
    {
        $status = request('status');
        $candidates = Candidate::when($status, function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->with(['tags', 'licenseVerification', 'tests'])
            ->latest()
            ->paginate(10);

        $tags = CandidateTag::all();

        return view('staff.candidates.index', compact('candidates', 'tags'));
    }

    // Show candidate details
    public function show(Candidate $candidate)
    {
        $candidate->load(['tags', 'licenseVerification', 'tests', 'documents']);
        $allTags = CandidateTag::all();
        $testTypes = ['Technical Assessment', 'Personality Test', 'Skills Evaluation', 'Background Check'];

        return view('staff.candidates.show', compact('candidate', 'allTags', 'testTypes'));
    }

    // Add tag to candidate
    public function addTag(Request $request, Candidate $candidate)
    {
        $request->validate([
            'tag_id' => 'required|exists:candidate_tags,id',
        ]);

        $candidate->tags()->syncWithoutDetaching([$request->tag_id]);

        return back()->with('success', 'Tag added successfully');
    }

    // Remove tag from candidate
    public function removeTag(Candidate $candidate, CandidateTag $tag)
    {
        $candidate->tags()->detach($tag->id);

        return back()->with('success', 'Tag removed successfully');
    }

    // Verify license
    public function verifyLicense(Request $request, Candidate $candidate)
    {
        $request->validate([
            'license_number' => 'required|string',
            'license_type' => 'required|string',
            'expiration_date' => 'nullable|date',
        ]);

        LicenseVerification::updateOrCreate(
            ['candidate_id' => $candidate->id],
            [
                'license_number' => $request->license_number,
                'license_type' => $request->license_type,
                'expiration_date' => $request->expiration_date,
                'is_verified' => true,
                'verified_at' => now(),
                'verified_by' => auth()->id(),
            ]
        );

        $candidate->update(['status' => 'license_verified']);

        return back()->with('success', 'License verified successfully');
    }

    // Schedule test
    public function scheduleTest(Request $request, Candidate $candidate)
    {
        $request->validate([
            'test_type' => 'required|string',
            'scheduled_at' => 'required|date',
        ]);

        CandidateTest::create([
            'candidate_id' => $candidate->id,
            'test_type' => $request->test_type,
            'scheduled_at' => $request->scheduled_at,
            'administered_by' => auth()->id(),
        ]);

        $candidate->update(['status' => 'test_scheduled']);

        return back()->with('success', 'Test scheduled successfully');
    }

    // Record test results
    public function recordTestResult(Request $request, CandidateTest $test)
    {
        $request->validate([
            'score' => 'required|numeric',
            'is_passed' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        $test->update([
            'completed_at' => now(),
            'score' => $request->score,
            'is_passed' => $request->is_passed,
            'notes' => $request->notes,
        ]);

        $status = $request->is_passed ? 'pending_approval' : 'rejected';
        $test->candidate->update([
            'status' => $status,
            'staff_notes' => $request->notes,
        ]);

        return back()->with('success', 'Test results recorded');
    }

    // Update candidate status
    public function updateStatus(Candidate $candidate, $status)
    {
        $validStatuses = ['under_review', 'license_verified', 'test_scheduled', 'pending_approval'];
        
        if (!in_array($status, $validStatuses)) {
            return back()->with('error', 'Invalid status');
        }

        $candidate->update(['status' => $status]);

        return back()->with('success', 'Status updated successfully');
    }

    // Update staff notes
    public function updateNotes(Request $request, Candidate $candidate)
    {
        $request->validate([
            'staff_notes' => 'nullable|string',
        ]);

        $candidate->update(['staff_notes' => $request->staff_notes]);

        return back()->with('success', 'Notes updated successfully');
    }


    public function parseResume(CandidateDocument $document)
{

    
    try {
        $filePath = storage_path('app/' . $document->file_path);

        if (!file_exists($filePath)) {
            Log::error('Resume file does not exist: ' . $filePath);
            return;
        }

        $parser = new BusTransportationResumeParser();
        $parsedData = $parser->parseResume($filePath);

        if (!isset($parsedData['skills'], $parsedData['experience_years'], $parsedData['education'], $parsedData['job_history'])) {
            Log::error('Parsed resume data format is invalid for document ID: ' . $document->id);
            return;
        }

        

        ParsedResume::updateOrCreate(
            ['document_id' => $document->id],
            [
                'skills' => is_array($parsedData['skills']) ? implode(', ', $parsedData['skills']) : $parsedData['skills'],
                'experience_years' => $parsedData['experience_years'],
                'education' => $parsedData['education'],
                'job_history' => is_array($parsedData['job_history']) ? implode("\n", $parsedData['job_history']) : $parsedData['job_history'],
                'raw_data' => json_encode($parsedData['raw_data'] ?? []),
            ]
        );
    } catch (\Exception $e) {
        Log::error('Resume parsing failed: ' . $e->getMessage());
    }
}

public function  showDocument(Candidate $candidate)
{
    // Eager load documents with the candidate
    $candidate->load('documents');
    
    return view('staff.candidates.show', [
        'candidate' => $candidate,
        'documents' => $candidate->documents
    ]);
}

/**
 * Store a new document for the candidate
 */
public function storeDocument(Request $request, Candidate $candidate)
{
    $request->validate([
        'document' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'type' => 'required|in:resume,certificate,license,other'
    ]);

    $file = $request->file('document');
    $path = $file->store('candidate_documents');

    $candidate->documents()->create([
        'original_name' => $file->getClientOriginalName(),
        'file_path' => $path,
        'type' => $request->type,
        'mime_type' => $file->getClientMimeType(),
        'size' => $file->getSize()
    ]);

    return back()->with('success', 'Document uploaded successfully');
}
// Add this method for the admin review queue
public function review()
{
    $candidates = Candidate::where('status', 'pending_approval')
        ->with(['tags', 'licenseVerification', 'tests'])
        ->latest()
        ->paginate(10);

    return view('admin.candidates.review', compact('candidates'));
}

public function approvalQueue()
{
    // Example: get candidates pending approval
    $candidates = Candidate::where('status', 'pending')->get();
    return view('admin.candidates.approval-queue', compact('candidates'));
}
/**
 * Download a candidate document
 */
public function downloadDocument(Candidate $candidate, CandidateDocument $document)  // ✅ Correct

{
    // Verify the document belongs to the candidate
    if ($document->candidate_id !== $candidate->id) {
        abort(403, 'Unauthorized action.');
    }

    $path = storage_path('app/' . $document->file_path);
    
    if (!Storage::exists($document->file_path)) {
        abort(404);
    }

    return response()->download($path, $document->original_name);
}

/**
 * Delete a candidate document
 */
public function deleteDocument(Candidate $candidate, CandidateDocument $document)
{
    // Verify the document belongs to the candidate
    if ($document->candidate_id !== $candidate->id) {
        abort(403, 'Unauthorized action.');
    }

    Storage::delete($document->file_path);
    $document->delete();

    return back()->with('success', 'Document deleted successfully');
}


}
