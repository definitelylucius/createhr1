<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication; // Correct Model
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        // Fetch applications submitted by the logged-in user
        $applications = JobApplication::where('user_id', Auth::id())->get();

        return view('staff.recruitment.applicants.track', compact('applications'));
    }
}