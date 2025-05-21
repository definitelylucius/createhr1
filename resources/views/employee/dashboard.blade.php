<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Tailwind & DaisyUI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@2.51.2/dist/full.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')

    <title>Employee Dashboard - Onboarding</title>
</head>
<body class="bg-gray-100 font-[Poppins]">

    <!-- Navbar -->
    @include('employeecomponent.nav-bar')
    <div class="wrapper">
        @include('layouts.partials.employee_sidebar')
        
    

    <div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold">Employee Dashboard</h2>
        </div>
    </div>

    <div class="row">
        <!-- Welcome Card -->
        <div class="col-md-12 mb-4">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="card-title">Welcome, {{ Auth::user()->name }}!</h3>
                            <p class="card-text">
                                @if($onboardingComplete)
                                You've completed all onboarding steps. We're glad to have you on board!
                                @else
                                Please complete your onboarding process to access all employee features.
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            @if($onboardingComplete)
                            <div class="bg-success text-white rounded-circle p-4 d-inline-block">
                                <i class="fas fa-check fa-3x"></i>
                            </div>
                            @else
                            <div class="bg-warning text-white rounded-circle p-4 d-inline-block">
                                <i class="fas fa-tasks fa-3x"></i>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!$onboardingComplete)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Onboarding Progress</h5>
                </div>
                <div class="card-body">
                    <div class="progress mb-3" style="height: 30px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" 
                             style="width: {{ $onboardingProgress }}%;" 
                             aria-valuenow="{{ $onboardingProgress }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ $onboardingProgress }}% Complete
                        </div>
                    </div>
                    
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Employment Contract</h6>
                                <small>Review and acknowledge your employment terms</small>
                            </div>
                            @if($onboarding->employment_contract)
                            <span class="badge bg-success rounded-pill"><i class="fas fa-check"></i></span>
                            @else
                            <a href="{{ route('employee.onboarding.contract') }}" class="btn btn-sm btn-outline-primary">Complete</a>
                            @endif
                        </div>
                        
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Tax Forms</h6>
                                <small>Submit required tax documentation</small>
                            </div>
                            @if($onboarding->tax_forms)
                            <span class="badge bg-success rounded-pill"><i class="fas fa-check"></i></span>
                            @else
                            <a href="{{ route('employee.onboarding.tax') }}" class="btn btn-sm btn-outline-primary">Complete</a>
                            @endif
                        </div>
                        
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Company Policies</h6>
                                <small>Review and acknowledge company policies</small>
                            </div>
                            @if($onboarding->company_policies)
                            <span class="badge bg-success rounded-pill"><i class="fas fa-check"></i></span>
                            @else
                            <a href="{{ route('employee.onboarding.policies') }}" class="btn btn-sm btn-outline-primary">Complete</a>
                            @endif
                        </div>
                        
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Training Materials</h6>
                                <small>Complete required training modules</small>
                            </div>
                            @if($onboarding->training_materials)
                            <span class="badge bg-success rounded-pill"><i class="fas fa-check"></i></span>
                            @else
                            <a href="{{ route('employee.onboarding.training') }}" class="btn btn-sm btn-outline-primary">Complete</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Upcoming Schedule -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Your Schedule</h5>
                </div>
                <div class="card-body">
                    @if(count($schedule) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Activity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedule as $event)
                                <tr>
                                    <td>{{ $event->date->format('M d, Y') }}</td>
                                    <td>{{ $event->time }}</td>
                                    <td>{{ $event->activity }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted">No upcoming schedule items found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Documents -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Your Documents</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('employee.documents.offer-letter') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Offer Letter
                            <i class="fas fa-file-pdf text-danger"></i>
                        </a>
                        <a href="{{ route('employee.documents.contract') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Employment Contract
                            <i class="fas fa-file-pdf text-danger"></i>
                        </a>
                        <a href="{{ route('employee.documents.policies') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Company Policies
                            <i class="fas fa-file-pdf text-danger"></i>
                        </a>
                        <a href="{{ route('employee.documents.tax-forms') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Tax Forms
                            <i class="fas fa-file-pdf text-danger"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
</body>
</html>



