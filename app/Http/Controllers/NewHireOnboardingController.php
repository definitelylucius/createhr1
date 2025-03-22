<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Onboarding;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class NewHireOnboardingController extends Controller
{
    public function showForm()
    {
        return view('employee-onboarding');
    }

    public function submitForm(Request $request)
    {
        // Validate and save data to the database
        $validatedData = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'department' => 'required',
            'position' => 'required',
            'hire_date' => 'required|date',
            'nda' => 'required|file',
            'welcome_letter' => 'required|file',
            'contract' => 'required|file',
        ]);

        // Save employee onboarding information
        $onboarding = Onboarding::create($validatedData);

        // Handle file uploads
        if ($request->hasFile('nda')) {
            $onboarding->nda = $request->file('nda')->store('nda_documents');
        }
        if ($request->hasFile('welcome_letter')) {
            $onboarding->welcome_letter = $request->file('welcome_letter')->store('welcome_letters');
        }
        if ($request->hasFile('contract')) {
            $onboarding->contract = $request->file('contract')->store('contracts');
        }

        $onboarding->save();

        return redirect()->route('employee.onboarding.form')->with('status', 'Onboarding form submitted successfully');
    }

    public function generateNDA(Request $request)
{
    try {
        $employee = $request->employee_id; // Assuming you pass employee_id
        Log::info("Generating NDA for employee ID: $employee");

        // Load employee data, or you can retrieve it from the database
        $employeeData = Employee::findOrFail($employee); // Example query

        // Check if employee data is found
        if (!$employeeData) {
            Log::error("Employee not found with ID: $employee");
            return response()->json(['error' => 'Employee not found'], 404);
        }

        // Pass data to the view
        $pdf = PDF::loadView('documents.nda', compact('employeeData'));

        // Return PDF as a download
        return $pdf->download('nda_' . $employeeData->name . '.pdf');
    } catch (\Exception $e) {
        Log::error('Error generating NDA PDF: ' . $e->getMessage());
        return response()->json(['error' => 'Something went wrong'], 500);
    }
}
}
