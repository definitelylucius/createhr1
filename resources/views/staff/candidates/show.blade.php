<?php
use Illuminate\Support\Facades\Storage;
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Candidate - Bus Transportation</title>
  <style>
    .card {
      transition: all 0.3s ease;
    }
    .card:hover {
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .badge {
      transition: all 0.2s ease;
    }
  </style>
</head>
<body class="font-[Poppins] bg-gray-50 min-h-screen">

    <!-- Navbar -->
    @include('staffcomponent.nav-bar')

    <div class="flex">
        <!-- Sidebar -->
        @include('staffcomponent.side-bar')

        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header Card -->
                <div class="card bg-white rounded-xl shadow-sm mb-6">
                    <div class="card-body p-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">{{ $candidate->full_name }}</h1>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="badge badge-lg badge-{{ $candidate->status_badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $candidate->status)) }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        Applied: {{ $candidate->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="btn btn-sm btn-outline">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                    Actions
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Basic Information Card -->
                        <div class="card bg-white rounded-xl shadow-sm">
                            <div class="card-body p-6">
                                <h2 class="card-title text-lg font-semibold mb-4">Basic Information</h2>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <span class="text-gray-500 w-24 flex-shrink-0">Email:</span>
                                        <span class="font-medium">{{ $candidate->email }}</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="text-gray-500 w-24 flex-shrink-0">Phone:</span>
                                        <span class="font-medium">{{ $candidate->phone ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="divider my-6"></div>

                                <h3 class="font-semibold mb-3">Tags</h3>
                                <form method="POST" action="{{ route('staff.candidates.add-tag', $candidate) }}" class="mb-4">
                                    @csrf
                                    <div class="flex gap-2">
                                        <select name="tag_id" class="select select-bordered flex-1" required>
                                            <option value="">Select a tag</option>
                                            @foreach($allTags as $tag)
                                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    </div>
                                </form>
                                
                                <div class="flex flex-wrap gap-2">
                                    @foreach($candidate->tags as $tag)
                                        <div class="badge badge-outline" style="border-color: {{ $tag->color }}; color: {{ $tag->color }};">
                                            {{ $tag->name }}
                                            <form method="POST" action="{{ route('staff.candidates.remove-tag', [$candidate, $tag]) }}" class="inline ml-1" onsubmit="return confirm('Remove this tag?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-gray-600">
                                                    ×
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- License Verification Card -->
                        <div class="card bg-white rounded-xl shadow-sm">
                            <div class="card-body p-6">
                                <h2 class="card-title text-lg font-semibold mb-4">License Verification</h2>
                                @if($candidate->licenseVerification)
                                    <div class="space-y-3">
                                        <div class="flex items-start">
                                            <span class="text-gray-500 w-24 flex-shrink-0">Type:</span>
                                            <span class="font-medium">{{ $candidate->licenseVerification->license_type }}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="text-gray-500 w-24 flex-shrink-0">Number:</span>
                                            <span class="font-medium">{{ $candidate->licenseVerification->license_number }}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="text-gray-500 w-24 flex-shrink-0">Expires:</span>
                                            <span class="font-medium">{{ $candidate->licenseVerification->expiration_date?->format('M d, Y') ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="text-gray-500 w-24 flex-shrink-0">Status:</span>
                                            @if($candidate->licenseVerification->is_verified)
                                                <span class="badge badge-success gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    Verified
                                                </span>
                                            @else
                                                <span class="badge badge-warning gap-1">
                                                    Pending
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if(!$candidate->licenseVerification->is_verified)
                                        <div class="mt-6">
                                            <form method="POST" action="{{ route('staff.candidates.verify-license', $candidate) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-success w-full">
                                                    Mark as Verified
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @else
                                    <form method="POST" action="{{ route('staff.candidates.verify-license', $candidate) }}">
                                        @csrf
                                        <div class="space-y-4">
                                            <div class="form-control">
                                                <label class="label">
                                                    <span class="label-text">License Type</span>
                                                </label>
                                                <input type="text" name="license_type" class="input input-bordered w-full" required>
                                            </div>
                                            <div class="form-control">
                                                <label class="label">
                                                    <span class="label-text">License Number</span>
                                                </label>
                                                <input type="text" name="license_number" class="input input-bordered w-full" required>
                                            </div>
                                            <div class="form-control">
                                                <label class="label">
                                                    <span class="label-text">Expiration Date</span>
                                                </label>
                                                <input type="date" name="expiration_date" class="input input-bordered w-full">
                                            </div>
                                            <button type="submit" class="btn btn-primary w-full mt-2">
                                                Record License
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Tests Card -->
                        <div class="card bg-white rounded-xl shadow-sm">
                            <div class="card-body p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="card-title text-lg font-semibold">Tests</h2>
                                    @if(in_array($candidate->status, ['under_review', 'license_verified']))
                                        <button class="btn btn-sm btn-primary" onclick="document.getElementById('scheduleTestModal').showModal()">
                                            Schedule Test
                                        </button>
                                    @endif
                                </div>
                                
                                @if($candidate->tests->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($candidate->tests as $test)
                                            <div class="collapse collapse-plus bg-base-100 rounded-box">
                                                <input type="checkbox" />
                                                <div class="collapse-title font-medium flex items-center justify-between pr-4">
                                                    <span>{{ $test->test_type }}</span>
                                                    <span class="badge badge-{{ $test->is_passed ? 'success' : ($test->completed_at ? 'error' : 'warning') }}">
                                                        {{ $test->status }}
                                                    </span>
                                                </div>
                                                <div class="collapse-content">
                                                    <div class="space-y-3 pt-4">
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">Scheduled:</span>
                                                            <span>{{ $test->scheduled_at->format('M d, Y g:i A') }}</span>
                                                        </div>
                                                        
                                                        @if($test->completed_at)
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-500">Score:</span>
                                                                <span>{{ $test->score }}</span>
                                                            </div>
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-500">Result:</span>
                                                                <span class="{{ $test->is_passed ? 'text-success' : 'text-error' }}">
                                                                    {{ $test->is_passed ? 'Passed' : 'Failed' }}
                                                                </span>
                                                            </div>
                                                            @if($test->notes)
                                                                <div>
                                                                    <p class="text-gray-500 mb-1">Notes:</p>
                                                                    <p class="text-sm">{{ $test->notes }}</p>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <form method="POST" action="{{ route('staff.tests.record', $test) }}">
                                                                @csrf
                                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                                    <div class="form-control">
                                                                        <label class="label">
                                                                            <span class="label-text">Score</span>
                                                                        </label>
                                                                        <input type="number" name="score" class="input input-bordered" required>
                                                                    </div>
                                                                    <div class="form-control">
                                                                        <label class="label">
                                                                            <span class="label-text">Result</span>
                                                                        </label>
                                                                        <select name="is_passed" class="select select-bordered" required>
                                                                            <option value="">Select</option>
                                                                            <option value="1">Pass</option>
                                                                            <option value="0">Fail</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-control">
                                                                        <label class="label">
                                                                            <span class="label-text invisible">Submit</span>
                                                                        </label>
                                                                        <button type="submit" class="btn btn-primary">
                                                                            Record
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="form-control mt-3">
                                                                    <textarea name="notes" class="textarea textarea-bordered" placeholder="Notes"></textarea>
                                                                </div>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="mt-2 text-gray-500">No tests scheduled yet</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                       
<!-- Documents Card -->
@if($candidate && $candidate->documents->count() > 0)
    <!-- Documents Card -->
    <div class="card bg-white rounded-xl shadow-sm">
        <div class="card-body p-6">
            <h2 class="card-title text-lg font-semibold mb-4">Documents</h2>

            <div class="space-y-3">
                @foreach($candidate->documents as $document)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <!-- Document Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            
                            <div class="min-w-0">
                                <a href="{{ route('staff.candidates.documents.download', [$candidate, $document]) }}" 
                                   class="font-medium text-gray-800 hover:text-primary hover:underline truncate block"
                                   title="{{ $document->original_name }}">
                                    {{ $document->original_name }}
                                </a>
                                <p class="text-xs text-gray-500">
                                    Uploaded {{ $document->created_at->diffForHumans() }} • 
                                    {{ round($document->size / 1024, 1) }} KB
                                </p>
                            </div>
                        </div>
                        <span class="badge badge-outline capitalize">{{ $document->type }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

                        <!-- Staff Notes Card -->
<div class="card bg-white rounded-xl shadow-sm">
    <div class="card-body p-6">
        <h2 class="card-title text-lg font-semibold mb-4">Staff Notes</h2>
        <form method="POST" action="{{ route('staff.candidates.update-notes', $candidate) }}">
            @csrf
            @method('PATCH')
            <textarea name="staff_notes" 
                     class="textarea textarea-bordered w-full h-32" 
                     placeholder="Enter notes about this candidate..."
                     >{{ old('staff_notes', $candidate->staff_notes) }}</textarea>
            <button type="submit" class="btn btn-primary mt-3 w-full">
                Save Notes
            </button>
        </form>
    </div>
</div>

                <!-- Ready for Approval Banner -->
                @if($candidate->status === 'pending_approval')
                    <div class="card bg-success text-success-content mt-6">
                        <div class="card-body">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h3 class="font-bold text-lg">Ready for Admin Approval</h3>
                                    <p>This candidate has completed all requirements and is ready for admin review.</p>
                                </div>
                                <form method="POST" action="{{ route('staff.candidates.update-status', [$candidate, 'status' => 'pending_approval']) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-outline btn-success">
                                        Confirm Approval
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Schedule Test Modal -->
    <dialog id="scheduleTestModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Schedule New Test</h3>
            <form method="POST" action="{{ route('staff.candidates.schedule-test', $candidate) }}">
                @csrf
                <div class="space-y-4 mt-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Test Type</span>
                        </label>
                        <select name="test_type" class="select select-bordered w-full" required>
                            <option value="">Select test type</option>
                            @foreach($testTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Date & Time</span>
                        </label>
                        <input type="datetime-local" name="scheduled_at" class="input input-bordered w-full" required>
                    </div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="document.getElementById('scheduleTestModal').close()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule Test</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

</body>
</html>