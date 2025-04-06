@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
  <!-- DaisyUI CDN -->
  <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Candidate - Bus Transportation</title>
  <style>
    .card-header {
      @apply px-6 py-4 border-b border-gray-200 font-semibold text-lg;
    }
    .card-body {
      @apply p-6;
    }
    .badge {
      @apply px-3 py-1 rounded-full text-xs font-medium;
    }
    .list-group-item {
      @apply px-4 py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors;
    }
  </style>
</head>
<body class="bg-gray-50 font-[Poppins]">

    <!-- Navbar -->
    @include('admincomponent.nav-bar')

    <div class="flex">
        <!-- Sidebar -->
        @include('admincomponent.side-bar')

        <div class="flex-1 p-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Review Candidate</h1>
                    <span class="badge bg-{{ $candidate->status_badge }} text-white">
                        {{ ucfirst(str_replace('_', ' ', $candidate->status)) }}
                    </span>
                </div>

                <!-- Candidate Name -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $candidate->full_name }}</h2>
                    <div class="flex items-center mt-2 text-gray-600">
                        <span class="mr-4"><i class="fi fi-sr-envelope mr-2"></i>{{ $candidate->email }}</span>
                        <span><i class="fi fi-sr-phone mr-2"></i>{{ $candidate->phone ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                       <!-- Candidate Information -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="card-header bg-gray-50">
        <h3 class="flex items-center gap-2">
            <i class="fi fi-sr-user"></i>
            Candidate Information
        </h3>
    </div>
    <div class="card-body">
        <div class="space-y-3">
            <!-- Name -->
            <div class="flex justify-between">
                <span class="text-gray-600">Full Name</span>
                <span class="font-medium">{{ $candidate->full_name }}</span>
            </div>
            
            <!-- Email -->
            <div class="flex justify-between">
                <span class="text-gray-600">Email</span>
                <span class="font-medium">{{ $candidate->email }}</span>
            </div>
            
            <!-- Phone -->
            <div class="flex justify-between">
                <span class="text-gray-600">Phone</span>
                <span class="font-medium">{{ $candidate->phone ?? 'N/A' }}</span>
            </div>
            
            <!-- Application Date -->
            <div class="flex justify-between">
                <span class="text-gray-600">Applied Date</span>
                <span class="font-medium">{{ $candidate->created_at->format('m/d/Y') }}</span>
            </div>
            
            <!-- Tags Section -->
            <div>
                <h4 class="text-gray-600 mb-2">Tags</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($candidate->tags as $tag)
                        <span class="badge" style="background-color: {{ $tag->color }}; color: white;">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
                          

                        <!-- License Verification -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="card-header bg-gray-50">
                                <h3 class="flex items-center gap-2">
                                    <i class="fi fi-sr-id-card"></i>
                                    License Verification
                                </h3>
                            </div>
                            <div class="card-body">
                                @if($candidate->licenseVerification)
                                    <div class="space-y-3">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">License Type</span>
                                            <span class="font-medium">{{ $candidate->licenseVerification->license_type }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">License Number</span>
                                            <span class="font-medium">{{ $candidate->licenseVerification->license_number }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Expiration Date</span>
                                            <span class="font-medium">{{ $candidate->licenseVerification->expiration_date?->format('m/d/Y') ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Verification Status</span>
                                            <span class="font-medium">
                                                @if($candidate->licenseVerification->is_verified)
                                                    <span class="text-green-600">Verified ({{ $candidate->licenseVerification->verified_at->format('m/d/Y') }})</span>
                                                @else
                                                    <span class="text-red-600">Not Verified</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-500">No license information recorded</p>
                                @endif
                            </div>
                        </div>

                        <!-- Staff Notes -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="card-header bg-gray-50">
                                <h3 class="flex items-center gap-2">
                                    <i class="fi fi-sr-comment-alt"></i>
                                    Staff Notes
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    @if($candidate->staff_notes)
                                        <p class="text-gray-700">{{ $candidate->staff_notes }}</p>
                                    @else
                                        <p class="text-gray-500 italic">No notes provided</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Test Results -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="card-header bg-gray-50">
                                <h3 class="flex items-center gap-2">
                                    <i class="fi fi-sr-test"></i>
                                    Test Results
                                </h3>
                            </div>
                            <div class="card-body">
                                @if($candidate->tests->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($candidate->tests as $test)
                                            <div class="border border-gray-200 rounded-lg p-4">
                                                <div class="flex justify-between items-start">
                                                    <h4 class="font-medium">{{ $test->test_type }}</h4>
                                                    <span class="badge bg-{{ $test->is_passed ? 'green-100 text-green-800' : 'red-100 text-red-800' }}">
                                                        {{ $test->is_passed ? 'Passed' : 'Failed' }}
                                                    </span>
                                                </div>
                                                <div class="mt-3 space-y-2 text-sm">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Score</span>
                                                        <span class="font-medium">{{ $test->score }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Administered by</span>
                                                        <span class="font-medium">{{ $test->administeredBy->name }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Completed</span>
                                                        <span class="font-medium">{{ $test->completed_at->format('m/d/Y g:i A') }}</span>
                                                    </div>
                                                </div>
                                                @if($test->notes)
                                                    <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                                        <p class="text-sm text-gray-700">{{ $test->notes }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8 text-gray-500">
                                        <i class="fi fi-sr-test text-4xl mb-2 opacity-50"></i>
                                        <p>No tests completed</p>
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

                        <!-- Approval Decision -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="card-header bg-gray-50">
                                <h3 class="flex items-center gap-2">
                                    <i class="fi fi-sr-badge-check"></i>
                                    Approval Decision
                                </h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.candidates.approve', $candidate) }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-gray-700 mb-2">Admin Notes</label>
                                        <textarea name="admin_notes" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" rows="3">{{ old('admin_notes', $candidate->admin_notes) }}</textarea>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <button type="submit" name="action" value="approve" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                            <i class="fi fi-sr-check"></i>
                                            Approve
                                        </button>
                                        <button type="submit" name="action" value="reject" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                            <i class="fi fi-sr-cross"></i>
                                            Reject
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>