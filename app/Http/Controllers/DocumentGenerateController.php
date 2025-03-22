<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DocumentGenerateController extends Controller
{
    // ✅ Generate NDA PDF and Download
   
 // ✅ Generate NDA PDF and Download
 public function generateNDA()
    {
        $userId = Auth::id(); // Get authenticated user ID
        Log::info("Generating NDA for User ID: " . $userId);

        $employee = Employee::where('user_id', $userId)->first();

        if (!$employee) {
            Log::error("Employee record not found for User ID: " . $userId);
            abort(404, "Employee record not found");
        }

        $pdf = Pdf::loadView('documents.nda', compact('employee'));

        return response()->streamDownload(
            function () use ($pdf) {
                echo $pdf->output();
            },
            "NDA_{$employee->id}.pdf"
        );
    }

    // ✅ Generate Onboarding Letter PDF and Download (Using User ID)// ✅ Generate Onboarding Letter PDF and Download
public function generateOnboardingLetter($id)
{

    $userId = Auth::id();
    Log::info("Generating Onboarding Letter for Employee ID: " . $userId);

    $employee = Employee::where('user_id', $userId)->first();

    if (!$employee) {
        Log::error("Employee record not found for ID: " . $userId);
        abort(404, "Employee record not found");
    }

    $pdf = Pdf::loadView('documents.onboarding', compact('employee'));

    return response()->streamDownload(
        function () use ($pdf) {
            echo $pdf->output();
        },
        "Onboarding_Letter_{$employee->id}.pdf",
        ['Content-Type' => 'application/pdf']
    );
}

// ✅ Generate Hiring Contract PDF and Download
public function generateHiringContract($id)
{
    $userId = Auth::id();
    Log::info("Generating Hiring Contract for Employee ID: " . $userId);

    $employee = Employee::where('user_id', $userId)->first();

    if (!$employee) {
        Log::error("Employee record not found for ID: " . $userId);
        abort(404, "Employee record not found");
    }

    $pdf = Pdf::loadView('documents.contract', compact('employee'));

    return response()->streamDownload(
        function () use ($pdf) {
            echo $pdf->output();
        },
        "Hiring_Contract_{$employee->id}.pdf",
        ['Content-Type' => 'application/pdf']
    );
}
}