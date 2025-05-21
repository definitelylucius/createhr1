@extends('layouts.staff')

@section('content')
<!-- Main Layout Structure -->
<div class="flex h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-20 w-64 bg-white shadow transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out">
        @include('layouts.partials.staff-sidebar')
    </aside>
    
    <div class="flex-1 flex flex-col overflow-hidden lg:ml-64">
        <!-- Navbar -->
        @include('layouts.partials.staff-navbar')
        
        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto pt-16">
            <div class="container mx-auto px-4 py-6">

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold">New Hire Onboarding</h4>
            @isset($application)
                <p>For: {{ $application->name ?? 'N/A' }} ({{ $application->job->title ?? 'N/A' }})</p>
            @else
                <div class="alert alert-warning">No application data found</div>
            @endisset
        </div>
    </div>

    @isset($application)
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Onboarding Checklist</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th width="50px"></th>
                                    <th>Task</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        @if($onboarding->employment_contract ?? false)
                                        <span class="badge bg-success rounded-pill"><i class="fas fa-check"></i></span>
                                        @else
                                        <span class="badge bg-secondary rounded-pill">1</span>
                                        @endif
                                    </td>
                                    <td>Employment Contract</td>
                                    <td>
                                        @if($onboarding->employment_contract ?? false)
                                        <span class="badge bg-success">Completed</span>
                                        @else
                                        <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ now()->addDays(2)->format('M j, Y') }}</td>
                                    <td>
                                        @if($onboarding->employment_contract ?? false)
                                        <a href="{{ Storage::url($onboarding->employment_contract) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            View Document
                                        </a>
                                        @else
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadContractModal">
                                            Upload
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                <!-- Other table rows... -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Complete Onboarding</h5>
                </div>
                <div class="card-body">
                    @if(($onboarding->allDocumentsUploaded() ?? false))
                    <form action="{{ route('onboarding.complete', $application->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">First Day Instructions</label>
                            <textarea class="form-control" name="first_day_instructions" rows="3" required>
- Arrive at 8:30 AM at the main office
- Bring your valid ID and bank details
- Dress code: Business casual
                            </textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Work Location</label>
                            <input type="text" class="form-control" name="work_location" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Supervisor</label>
                            <select class="form-select" name="supervisor_id" required>
                                <option value="">Select Supervisor</option>
                                @foreach($supervisors ?? [] as $supervisor)
                                <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Complete Onboarding</button>
                    </form>
                    @else
                    <div class="alert alert-warning">
                        <strong>Pending:</strong> Please complete all onboarding tasks before finalizing.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-danger">
        No application data available. Please check the application ID and try again.
    </div>
    @endisset
</div>

<!-- Modals -->
@isset($application)
<!-- Upload Contract Modal -->
<div class="modal fade" id="uploadContractModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Employment Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('onboarding.uploadContract', $application->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Contract File</label>
                        <input type="file" class="form-control" name="contract" accept=".pdf,.doc,.docx" required>
                        <small class="text-muted">PDF or Word document</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endisset

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.dropdown').forEach(dropdown => {
            const btn = dropdown.querySelector('button');
            const menu = dropdown.querySelector('.dropdown-menu');

            if (btn && menu) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    document.querySelectorAll('.dropdown-menu').forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });
                    menu.classList.toggle('hidden');
                });
            }
        });

        document.addEventListener('click', function () {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        });
    });
</script>
@endsection