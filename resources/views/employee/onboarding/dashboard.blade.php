<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Tailwind & DaisyUI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@2.51.2/dist/full.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <title>Employee Dashboard - Onboarding</title>
</head>
<body class="bg-gray-100 font-[Poppins]">

    <!-- Navbar -->
    @include('employeecomponent.nav-bar')

    <div class="flex">
        <!-- Sidebar -->
        @include('employeecomponent.side-bar')


        <div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Onboarding Dashboard</h1>
            <p class="text-muted">Welcome to your onboarding process. Please complete all required tasks.</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">Progress</h5>
                    @php
                        $completed = $tasks->where('pivot.status', 'completed')->count();
                        $total = $tasks->count();
                        $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;
                    @endphp
                    <div class="progress mb-2">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%" 
                            aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $percentage }}%
                        </div>
                    </div>
                    <p class="mb-0">{{ $completed }} of {{ $total }} tasks completed</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Onboarding Tasks</h3>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($tasks as $task)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1">{{ $task->name }}</h5>
                                        <small class="text-muted">Due: {{ $task->pivot->due_date->format('M d, Y') }}</small>
                                        @if($task->pivot->status === 'completed')
                                            <span class="badge bg-success ms-2">Completed</span>
                                        @endif
                                    </div>
                                    @if($task->pivot->status !== 'completed')
                                        <form action="{{ route('employee.onboarding.complete-task', $task->pivot) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                Mark Complete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                @if($task->description)
                                    <p class="mt-2 mb-0">{{ $task->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Upload Documents</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('employee.onboarding.upload-document') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="type" class="form-label">Document Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="contract">Employment Contract</option>
                                <option value="tax_forms">Tax Forms</option>
                                <option value="id_proof">ID Proof</option>
                                <option value="education_certificates">Education Certificates</option>
                                <option value="professional_certifications">Professional Certifications</option>
                                <option value="nda">NDA Agreement</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="document" class="form-label">Document</label>
                            <input class="form-control" type="file" id="document" name="document" required>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Upload Document</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Complete Profile</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('employee.onboarding.update-profile') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                value="{{ old('phone', auth()->user()->phone) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2" required>
                                {{ old('address', auth()->user()->address) }}
                            </textarea>
                        </div>

                        <div class="mb-3">
                            <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
                            <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" 
                                value="{{ old('emergency_contact_name', auth()->user()->emergency_contact_name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone</label>
                            <input type="text" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" 
                                value="{{ old('emergency_contact_phone', auth()->user()->emergency_contact_phone) }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($documents->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h3>Submitted Documents</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Document Type</th>
                                <th>File Name</th>
                                <th>Uploaded At</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $document->type)) }}</td>
                                    <td>{{ $document->original_name }}</td>
                                    <td>{{ $document->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($document->is_verified)
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending Review</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ Storage::url($document->file_path) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                        <form action="{{ route('employee.onboarding.delete-document', $document) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

</body>
</html>