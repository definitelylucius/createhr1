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
            <div class="container mx-auto px-4 py-6"> <!-- Added overflow-y-auto here -->
            <main class="flex-1">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Header Section -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Document Verification</h1>
                            <p class="text-gray-500 mt-1">Review and verify documents for {{ $application->firstname }} {{ $application->lastname }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('staff.recruitment.pre-employment.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <!-- Document Verification Card -->
                    <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                        <div class="p-6">
                            <!-- Applicant Info -->
                            <div class="mb-6 pb-6 border-b border-gray-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-medium text-lg">
                                        {{ substr($application->firstname, 0, 1) }}{{ substr($application->lastname, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ $application->firstname }} {{ $application->lastname }}</div>
                                        <div class="text-sm text-gray-500">{{ $application->email }}</div>
                                        <div class="text-sm text-gray-500">{{ $application->job->title ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>

  <!-- Documents Section -->
<div class="mb-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Requested Documents</h3>
    
    @php
        // Properly decode the requested_documents JSON
        $requestedDocs = json_decode($document->requested_documents ?? '[]', true) ?? [];
    @endphp
    
    @if(empty($requestedDocs))
        <p class="text-gray-500">No documents have been requested yet.</p>
    @else
        <div class="space-y-4">
            @php
                $docCount = count($requestedDocs);
                $docUploaded = 0;

                // Count the uploaded documents
                foreach ($requestedDocs as $docType) {
                    if (!empty($document->{$docType.'_path'})) {
                        $docUploaded++;
                    }
                }

                // Calculate upload progress
                $progressPercent = $docCount > 0 ? ($docUploaded / $docCount) * 100 : 0;
            @endphp
            
            <div class="flex items-center">
                <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progressPercent }}%"></div>
                </div>
                <span class="text-sm font-medium {{ $docUploaded == $docCount ? 'text-green-600' : 'text-blue-600' }}">
                    {{ $docUploaded }}/{{ $docCount }} Uploaded
                </span>
            </div>
            
            @foreach($requestedDocs as $docType)
                @php
                    $docName = ucwords(str_replace('_', ' ', $docType));
                    $verified = $document->{$docType.'_verified'} ?? false;
                    $path = $document->{$docType.'_path'} ?? null;
                    $notes = $document->{$docType.'_notes'} ?? null;
                @endphp
                
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-medium text-gray-800">{{ $docName }}</h4>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $verified ? 'Verified' : 'Pending' }}
                        </span>
                    </div>
                    
                    @if($path)
    <div class="mb-3">
        <a href="{{ \Illuminate\Support\Facades\Storage::url($path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            View {{ $docName }}
        </a>
    </div>
@else
    <p class="text-sm text-red-500 mb-3">Document not uploaded yet</p>
@endif

                    
                    <form action="{{ route('staff.recruitment.pre-employment.verify-document', ['application' => $application->id, 'documentType' => $docType]) }}" method="POST" class="flex items-center gap-4" onsubmit="return confirm('Are you sure you want to verify this document?')">
                        @csrf
                        <div class="flex items-center">
                            <input type="checkbox" id="verify_{{ $docType }}" name="verified" value="1" {{ $verified ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="verify_{{ $docType }}" class="ml-2 block text-sm text-gray-700">Verify Document</label>
                        </div>
                        <div class="flex-1">
                            <input type="text" name="notes" placeholder="Notes (optional)" value="{{ $notes }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>


                          <!-- Additional Verifications -->
<div>
    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Verifications</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4"><!-- Changed back to 3 columns -->
        <!-- Reference Check -->
        <div class="border border-gray-200 rounded-lg p-4">
            <h4 class="font-medium text-gray-800 mb-2">Reference Check</h4>
            <form action="{{ route('staff.recruitment.pre-employment.update-reference-check', ['application' => $application->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to update the reference check status?')">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <select name="reference_check_verified" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="0" {{ !$document->reference_check_verified ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ $document->reference_check_verified ? 'selected' : '' }}>Verified</option>
                    </select>
                </div>
                <div class="mb-3">
                    <textarea name="reference_check_notes" rows="2" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Notes">{{ $document->reference_check_notes }}</textarea>
                </div>
                <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update Status
                </button>
            </form>
        </div>

       <!-- Drug Test -->
<div class="border border-gray-200 rounded-lg p-4">
    <h4 class="font-medium text-gray-800 mb-2">Drug Test</h4>
    
    @if($document->drug_test_path)
    <div class="mb-3">
        <a href="{{ \Illuminate\Support\Facades\Storage::url($document->drug_test_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            View Drug Test Result
        </a>
    </div>
    @else
    <p class="text-sm text-red-500 mb-3">Drug test result not uploaded yet</p>
    @endif

    <form action="{{ route('staff.recruitment.pre-employment.update-drug-test', ['application' => $application->id]) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure you want to update the drug test status?')">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <select name="drug_test_verified" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="0" {{ !$document->drug_test_verified ? 'selected' : '' }}>Pending</option>
                <option value="1" {{ $document->drug_test_verified ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <div class="mb-3">
            <select name="drug_test_result" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="negative" {{ $document->drug_test_result === 'negative' ? 'selected' : '' }}>Negative</option>
                <option value="positive" {{ $document->drug_test_result === 'positive' ? 'selected' : '' }}>Positive</option>
            </select>
        </div>
        <div class="mb-3">
            <input type="date" name="drug_test_date" value="{{ $document->drug_test_date ?? now()->format('Y-m-d') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        </div>
    
        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Update Status
        </button>
    </form>
</div>

<!-- Medical Exam -->
<div class="border border-gray-200 rounded-lg p-4">
    <h4 class="font-medium text-gray-800 mb-2">Medical Exam</h4>

    @if($document->medical_exam_path)
    <div class="mb-3">
        <a href="{{ \Illuminate\Support\Facades\Storage::url($document->medical_exam_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            View Medical Exam Result
        </a>
    </div>
    @else
    <p class="text-sm text-red-500 mb-3">Medical exam result not uploaded yet</p>
    @endif

    <form action="{{ route('staff.recruitment.pre-employment.update-medical-exam', ['application' => $application->id]) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure you want to update the medical exam status?')">
        @csrf
        @method('PUT')

        <!-- Medical Exam Status -->
        <div class="mb-3">
            <select name="medical_exam_verified" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="0" {{ !$document->medical_exam_verified ? 'selected' : '' }}>Pending</option>
                <option value="1" {{ $document->medical_exam_verified ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <!-- Medical Exam Result -->
        <div class="mb-3">
            <select name="medical_exam_result" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="fit" {{ $document->medical_exam_result === 'fit' ? 'selected' : '' }}>Fit to Work</option>
                <option value="unfit" {{ $document->medical_exam_result === 'unfit' ? 'selected' : '' }}>Unfit to Work</option>
                <option value="conditional" {{ $document->medical_exam_result === 'conditional' ? 'selected' : '' }}>Conditional</option>
            </select>
        </div>

        <!-- Medical Exam Date -->
        <div class="mb-3">
            <input type="date" name="medical_exam_date" value="{{ $document->medical_exam_date ?? now()->format('Y-m-d') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        </div>

        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Update Status
        </button>
    </form>
</div><!-- End of Medical Exam -->


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<!-- SweetAlert for confirmation dialogs -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // You can customize the confirmation dialogs further if needed
    function confirmAction(form, message = 'Are you sure you want to perform this action?') {
        Swal.fire({
            title: 'Confirm Action',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }
</script>
@endsection