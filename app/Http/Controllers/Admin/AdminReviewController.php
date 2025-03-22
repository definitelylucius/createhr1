<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Log;


class AdminReviewController extends Controller
{
    public function index()
    {
        $applications = JobApplication::where('application_status', 'for_admin_review')
            ->with(['job', 'reviewer', 'user'])
            ->get();

        return view('admin.applicants.review', [
            'applications' => $applications,
            'application' => $applications->isNotEmpty() ? $applications->first() : null,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $application = JobApplication::findOrFail($id);

        $request->validate([
            'application_status' => 'required|string',
        ]);

        $application->update([
            'application_status' => $request->input('application_status'),
        ]);

        return redirect()->back()->with('status', 'Application status updated successfully.');
    }


    
    
    

    public function updateApplicationReviewer(Request $request)
    {
        // Validate the request
        $request->validate([
            'application_id' => 'required|exists:job_applications,id',
            'reviewed_by' => 'required|exists:users,id', // Ensure the reviewer is a valid user
        ]);
    
        // Find the application
        $application = JobApplication::findOrFail($request->application_id);
    
        // Update the reviewed_by field and status
        $application->reviewed_by = $request->reviewed_by;
        $application->status = 'under_review';  // Example status update
        $application->save();
    
        return redirect()->back()->with('success', 'Reviewer updated successfully.');
    }
    
public function showApplications()
{
    $applications = JobApplication::with('job', 'reviewer', 'user')->get();
    
    // Pass the first application as a default one
    return view('admin.applications', [
        'applications' => $applications,
        'application' => $applications->first() // Ensure there's a default application
    ]);
}



}


