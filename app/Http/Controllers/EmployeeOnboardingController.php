<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\HiredEmployee;
use App\Models\EmployeePersonalDetails;
use App\Models\HiredDocument;
use App\Models\EmployeeBankDetails;
use Auth;
use App\Models\Task;

class EmployeeOnboardingController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->get();
    
        // Debugging: Check if tasks exist
        if ($tasks->isEmpty()) {
            return "No tasks found for this user.";
        }
    
        // Pass tasks to the view
        return view('employee.onboarding.onboarding', compact('tasks'));
    }

    public function storeEmployeeDetails(Request $request)
    {
        HiredEmployee::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only('profile_picture', 'bio', 'department', 'position', 'employee_id')
        );

        return redirect()->back()->with('success', 'Employee Details Saved!');
    }

    public function storePersonalDetails(Request $request)
    {
        EmployeePersonalDetails::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only('full_name', 'phone', 'email', 'age', 'birthday', 'gender', 'nationality')
        );

        return redirect()->back()->with('success', 'Personal Details Saved!');
    }

    public function uploadDocument(Request $request)
    {
        $filePath = $request->file('document')->store('uploads');

        HiredDocument::create([
            'user_id' => Auth::id(),
            'document_name' => $request->document_name,
            'file_path' => $filePath,
        ]);

        return redirect()->back()->with('success', 'Document Uploaded!');
    }

    public function storeBankDetails(Request $request)
    {
        EmployeeBankDetails::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only('bank_name', 'account_number', 'account_holder')
        );

        return redirect()->back()->with('success', 'Bank Details Saved!');
    }

    

}


