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
                    <!-- Header Section -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Pre-Employment Status Tracking</h1>
                            <p class="text-gray-500 mt-1">Manage all pre-employment requirements and status</p>
                        </div>
                        <div class="flex gap-2">
                            <!-- Schedule New Activity Button -->
                            <a href="{{ route('staff.recruitment.pre-employment.schedule') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                </svg>
                                Schedule New Activity
                            </a>
                        </div>
                    </div>

                    <!-- Status Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                        <!-- Total Candidates -->
                        <div class="bg-white p-4 rounded-lg shadow border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Total Candidates</p>
                                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $applications->count() }}</p>
                                </div>
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Completed -->
                        <div class="bg-white p-4 rounded-lg shadow border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Completed</p>
                                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $completedCount ?? 0}}</p>
                                </div>
                                <div class="p-3 rounded-full bg-green-100 text-green-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- In Progress -->
                        <div class="bg-white p-4 rounded-lg shadow border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">In Progress</p>
                                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $inProgressCount ?? 0}} </p>
                                </div>
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Not Started -->
                        <div class="bg-white p-4 rounded-lg shadow border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Not Started</p>
                                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $notStartedCount ?? 0}}</p>
                                </div>
                                <div class="p-3 rounded-full bg-red-100 text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Card -->
                    <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidate</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documents</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference Check</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Drug Test</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medical</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled Date</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($applications as $application)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-medium">
                                                {{ substr($application->firstname, 0, 1) }}{{ substr($application->lastname, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="font-medium text-gray-900">{{ $application->firstname }} {{ $application->lastname }}</div>
                                                    <div class="text-sm text-gray-500">{{ $application->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                            {{ $application->job->title ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $status = $application->preEmploymentStatus();
                                                $statusClass = [
                                                    'not-started' => 'bg-red-100 text-red-800',
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'in-progress' => 'bg-blue-100 text-blue-800',
                                                    'documents-completed' => 'bg-indigo-100 text-indigo-800',
                                                    'completed' => 'bg-green-100 text-green-800'
                                                ][$status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2.5 py-1 text-xs font-semibold leading-4 rounded-full {{ $statusClass }}">
                                                {{ ucfirst(str_replace('-', ' ', $status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
    @php
        // Safely decode requested_documents with fallback to empty array
        $requestedDocs = $application->preEmploymentDocument 
            ? (json_decode($application->preEmploymentDocument->requested_documents ?? '[]', true) ?? [])
            : [];
    @endphp

    @if(!empty($requestedDocs))
        @php
            $docCount = count($requestedDocs);
            $docUploaded = 0;

            foreach ($requestedDocs as $docType) {
                // Check both path existence and non-empty value
                if (!empty($application->preEmploymentDocument->{"{$docType}_path"})) {
                    $docUploaded++;
                }
            }

            $progressPercent = $docCount > 0 ? ($docUploaded / $docCount) * 100 : 0;
            $allUploaded = $docUploaded == $docCount;
        @endphp

        <div class="flex items-center">
            <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                <div class="h-2.5 rounded-full {{ $allUploaded ? 'bg-green-600' : 'bg-blue-600' }}" 
                     style="width: {{ $progressPercent }}%">
                </div>
            </div>
            <span class="text-sm font-medium {{ $allUploaded ? 'text-green-600' : 'text-blue-600' }}">
                {{ $docUploaded }}/{{ $docCount }} Uploaded
            </span>
        </div>
    @else
        <span class="text-sm text-gray-500">
            {{ $application->preEmploymentDocument ? 'No documents requested' : 'Not started' }}
        </span>
    @endif
</td><td class="px-6 py-4 whitespace-nowrap">
    @php
        // Safely decode requested_documents with fallback to empty array
        $requestedDocs = $application->preEmploymentDocument 
            ? (json_decode($application->preEmploymentDocument->requested_documents ?? '[]', true) ?? [])
            : [];
    @endphp

    @if(!empty($requestedDocs))
        @php
            $docCount = count($requestedDocs);
            $docUploaded = 0;

            foreach ($requestedDocs as $docType) {
                // Check both path existence and non-empty value
                if (!empty($application->preEmploymentDocument->{"{$docType}_path"})) {
                    $docUploaded++;
                }
            }

            $progressPercent = $docCount > 0 ? ($docUploaded / $docCount) * 100 : 0;
            $allUploaded = $docUploaded == $docCount;
        @endphp

        <div class="flex items-center">
            <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                <div class="h-2.5 rounded-full {{ $allUploaded ? 'bg-green-600' : 'bg-blue-600' }}" 
                     style="width: {{ $progressPercent }}%">
                </div>
            </div>
            <span class="text-sm font-medium {{ $allUploaded ? 'text-green-600' : 'text-blue-600' }}">
                {{ $docUploaded }}/{{ $docCount }} Uploaded
            </span>
        </div>
    @else
        <span class="text-sm text-gray-500">
            {{ $application->preEmploymentDocument ? 'No documents requested' : 'Not started' }}
        </span>
    @endif
</td>

                                       

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($application->preEmploymentDocument)
                                                <a href="{{ route('staff.recruitment.pre-employment.documents', $application->id) }}" class="flex items-center focus:outline-none">
                                                    @if($application->preEmploymentDocument->reference_check_verified)
                                                        <span class="px-2.5 py-1 text-xs font-semibold leading-4 text-green-800 bg-green-100 rounded-full">
                                                            Verified
                                                        </span>
                                                    @else
                                                        <span class="px-2.5 py-1 text-xs font-semibold leading-4 text-yellow-800 bg-yellow-100 rounded-full">
                                                            Pending
                                                        </span>
                                                    @endif
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-500">Not started</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($application->preEmploymentDocument)
                                                <a href="{{ route('staff.recruitment.pre-employment.documents', $application->id) }}" class="flex items-center focus:outline-none">
                                                    @if($application->preEmploymentDocument->drug_test_verified)
                                                        <span class="px-2.5 py-1 text-xs font-semibold leading-4 text-green-800 bg-green-100 rounded-full">
                                                            Completed
                                                        </span>
                                                    @else
                                                        <span class="px-2.5 py-1 text-xs font-semibold leading-4 text-yellow-800 bg-yellow-100 rounded-full">
                                                            Pending
                                                        </span>
                                                    @endif
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-500">Not started</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($application->preEmploymentDocument)
                                                <a href="{{ route('staff.recruitment.pre-employment.documents', $application->id) }}" class="flex items-center focus:outline-none">
                                                    @if($application->preEmploymentDocument->medical_exam_verified)
                                                        <span class="px-2.5 py-1 text-xs font-semibold leading-4 text-green-800 bg-green-100 rounded-full">
                                                            Completed
                                                        </span>
                                                    @else
                                                        <span class="px-2.5 py-1 text-xs font-semibold leading-4 text-yellow-800 bg-yellow-100 rounded-full">
                                                            Pending
                                                        </span>
                                                    @endif
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-500">Not started</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                            @if($application->preEmploymentDocument && $application->preEmploymentDocument->scheduled_date)
                                                {{ \Carbon\Carbon::parse($application->preEmploymentDocument->scheduled_date)->format('M d, Y h:i A') }}
                                            @else
                                                <span class="text-gray-500">Not scheduled</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a 
                                                    href="{{ route('staff.recruitment.pre-employment.documents', $application->id) }}"
                                                    class="inline-flex items-center px-3 py-1 border border-blue-500 text-blue-600 rounded-md text-sm hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Verify
                                                </a>
                                                
                                                <button 
                                                    onclick="openDocumentRequestModal('{{ $application->id }}', '{{ $application->firstname }} {{ $application->lastname }}')"
                                                    class="inline-flex items-center px-3 py-1 border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Request Docs
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                            No pre-employment applications found
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($applications->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            {{ $applications->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
<!-- Document Request Modal -->
<div id="documentRequestModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form method="POST" action="{{ route('staff.recruitment.pre-employment.request-documents', ['application' => 'APPLICATION_ID_PLACEHOLDER']) }}" id="documentRequestForm">
                @csrf
                <input type="hidden" name="application_id" id="modalApplicationId">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Request Documents</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">
                                    Request documents from <span id="modalCandidateName" class="font-medium"></span>
                                </p>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Documents to Request</label>
                                    <div class="space-y-2">
                                        @foreach(['nbi_clearance', 'police_clearance', 'barangay_clearance', 'coe', 'drivers_license', 'medical_exam'] as $doc)
                                        <div class="flex items-center">
                                            <input type="checkbox" id="doc_{{ $doc }}" name="documents[]" value="{{ $doc }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <label for="doc_{{ $doc }}" class="ml-2 block text-sm text-gray-700">
                                                {{ ucwords(str_replace('_', ' ', $doc)) }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                                    <input type="date" name="deadline" class="form-input w-full" required min="{{ date('Y-m-d') }}">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Instructions</label>
                                    <textarea name="message" rows="3" class="form-textarea w-full" placeholder="Provide specific instructions for document submission..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Send Request
                    </button>
                    <button type="button" onclick="closeDocumentRequestModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show toast notifications
    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `mb-4 flex items-center p-4 w-full max-w-xs text-white rounded-lg shadow ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
        toast.innerHTML = `
            <div class="ml-3 text-sm font-normal">${message}</div>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8" onclick="this.parentElement.remove()">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `;
        
        toastContainer.appendChild(toast);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    // Display any flash messages
    @if(session('success')) 
        showToast('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
});

// Document Request Modal Functions
function openDocumentRequestModal(applicationId, candidateName) {
    document.getElementById('modalApplicationId').value = applicationId;
    document.getElementById('modalCandidateName').textContent = candidateName;
    
    // Set the form action with the correct route
    const form = document.getElementById('documentRequestForm');
    form.action = form.action.replace('APPLICATION_ID_PLACEHOLDER', applicationId);
    
    document.getElementById('documentRequestModal').classList.remove('hidden');
}

function closeDocumentRequestModal() {
    document.getElementById('documentRequestModal').classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('documentRequestModal');
    if (event.target === modal) {
        closeDocumentRequestModal();
    }
}
document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.dropdown').forEach(dropdown => {
            const btn = dropdown.querySelector('button');
            const menu = dropdown.querySelector('.dropdown-menu');

            if (btn && menu) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    // Close other dropdowns first
                    document.querySelectorAll('.dropdown-menu').forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });
                    // Toggle this one
                    menu.classList.toggle('hidden');
                });
            }
        });

        // Global click handler to close all dropdowns
        document.addEventListener('click', function () {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        });
    });
</script>

@endsection