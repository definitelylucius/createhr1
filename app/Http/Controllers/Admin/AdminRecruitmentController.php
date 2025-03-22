<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobApplication;

use App\Notifications\InterviewApprovedNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ApplicantHiredNotification;
use Illuminate\Support\Facades\Log;



class AdminRecruitmentController extends Controller
{
    
    public function updateApplicationStatus(Request $request, $id)
    {
        Log::info('Request Data:', ['request' => $request->all(), 'id' => $id]);
    
        $request->validate([
            'status' => 'required|string',
        ]);
    
        $application = JobApplication::findOrFail($id);
    
        $application->status = $request->status;
        $application->save();
    
        return redirect()->back()->with('success', 'Application status updated successfully.');
    }
    
    
    public function hiredApplicants()
    {
        $hiredApplicants = JobApplication::where('status', 'hired')->get();
        return view('admin.recruitment.hired', compact('hiredApplicants'));
    }

    public function hireApplicants($ids)
    {
        // Split the comma-separated IDs into an array
        $applicantIds = explode(',', $ids);

        foreach ($applicantIds as $applicantId) {
            $application = JobApplication::find($applicantId);
            if ($application) {
                $application->status = 'hired';
                $application->save();
                // Optionally send a notification or perform other actions here
                Notification::send($application->user, new ApplicantHiredNotification($application));
            }
        }

        return redirect()->route('admin.applicants.hired')->with('success', 'Applicants hired successfully.');
    }
}
    






