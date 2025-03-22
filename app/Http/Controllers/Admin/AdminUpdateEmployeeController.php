<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\User;
use App\Models\Employee;
use App\Notifications\ApplicantHiredNotification;
use Illuminate\Support\Facades\Log;



class AdminUpdateEmployeeController extends Controller
{


    public function updateToEmployees(Request $request, $applicantIds)
    {
        // Split the comma-separated string of applicant IDs
        $ids = explode(',', $applicantIds);
    
        foreach ($ids as $applicantId) {
            // ✅ Find the job application first
            $application = JobApplication::find($applicantId);
        
            if (!$application) {
                return redirect()->back()->with('error', 'One or more job applications not found.');
            }
        
            // ✅ Find the associated user
            $user = User::find($application->user_id);
        
            if (!$user) {
                return redirect()->back()->with('error', 'One or more users associated with applications not found.');
            }
        
            // ✅ Ensure only "recommended_for_hiring" applicants can be hired
            if ($application->status !== 'recommended_for_hiring') {
                return redirect()->back()->with('error', 'Only recommended applicants can be hired.');
            }
        
            // ✅ Retrieve the related job (if available)
            $job = $application->job ?? null;
        
            // ✅ Determine active status: If hired, set 'active', otherwise 'not_active'
            $activeStatus = 'active'; // ✅ This is set upon hiring
        
            // ✅ Ensure the user isn't already an employee
            $employee = Employee::where('user_id', $user->id)->first();
        
            if (!$employee) {
                // ✅ Create a new employee record
                $employee = Employee::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $application->phone ?? 'N/A',
                    'address' => $application->address ?? 'N/A',
                    'soft_skills' => !empty($application->soft_skills) ? $application->soft_skills : 'N/A',
                    'hard_skills' => !empty($application->hard_skills) ? $application->hard_skills : 'N/A',
                    'department' => $job ? $job->department : 'N/A',
                    'job_type' => $job ? $job->title : 'Unknown Job Type',
                    'resume' => $application->resume ?? null,
                    'hired_date' => now()->format('Y-m-d'),
                    'active' => true,
                ]);
            } else {
                // ✅ If already exists, just update status and hired_date
                $activeStatus = 1; // 1 for active, 0 for inactive

                // ✅ Update the employee's status to 'active'
                $employee->update([
                    'hired_date' => now()->format('Y-m-d'),
                    'active' => $activeStatus // This should be an integer value (1 or 0)
                ]);
            }
        
            // ✅ Update the job application status
            $application->update([
                'status' => 'hired',
                'employee_status' => 'employee'
            ]);
        
            // ✅ Update the user's role
            $user->update([
                'role' => 'employee'
            ]);
        
            // ✅ Send notification to all admins & staff
            $adminsAndStaff = User::whereIn('role', ['admin', 'staff'])->get();
            foreach ($adminsAndStaff as $recipient) {
                $recipient->notify(new ApplicantHiredNotification($employee));
            }
        }
    
        // ✅ Redirect with success message
        return redirect()->route('admin.applicants.hired')->with('success', 'The selected applicants have been hired and are now employees.');

    }
    
    

    
    public function showHiredApplicants()
    {
        // Fetch applicants who have been hired
        $applications = JobApplication::where('status', 'recommended_for_hiring')
        ->with(['user', 'job', 'reviewer'])
        ->get();

    


    return view('admin.recruitment.hired', compact('applications'));
}

}


