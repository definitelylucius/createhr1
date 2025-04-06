<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDocument;
use App\Models\EmployeeOnboarding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OnboardingController extends Controller
{
    public function dashboard()
    {
        $employee = auth()->user();
        $tasks = $employee->onboardingTasks()
            ->orderBy('due_date')
            ->get();
            
        $documents = $employee->documents;
        
        return view('employee.onboarding.dashboard', compact('tasks', 'documents'));
    }

    public function completeTask(EmployeeOnboarding $task)
    {
        if ($task->employee_id !== auth()->id()) {
            abort(403);
        }

        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completed_by' => auth()->id()
        ]);

        return back()->with('success', 'Task marked as completed');
    }

    public function uploadDocument(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:contract,tax_forms,id_proof,education_certificates,professional_certifications,nda,other',
            'document' => 'required|file|max:2048',
            'notes' => 'nullable|string'
        ]);

        $path = $request->file('document')->store('employee-documents');
        
        auth()->user()->documents()->create([
            'type' => $validated['type'],
            'file_path' => $path,
            'original_name' => $request->file('document')->getClientOriginalName(),
            'notes' => $validated['notes']
        ]);

        return back()->with('success', 'Document uploaded successfully');
    }

    public function deleteDocument(EmployeeDocument $document)
    {
        if ($document->employee_id !== auth()->id()) {
            abort(403);
        }

        Storage::delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Document deleted');
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'address' => 'required|string',
            'emergency_contact_name' => 'required|string',
            'emergency_contact_phone' => 'required|string'
        ]);

        auth()->user()->update($validated);

        // Mark profile completion task if exists
        $profileTask = auth()->user()->onboardingTasks()
            ->where('name', 'Complete Profile')
            ->first();
            
        if ($profileTask && $profileTask->pivot->status !== 'completed') {
            $profileTask->pivot->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);
        }

        return back()->with('success', 'Profile information updated');
    }
}