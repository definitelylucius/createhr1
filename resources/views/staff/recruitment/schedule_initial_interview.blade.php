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
                <!-- Changed to overflow-y-auto for scrollable content -->
                <div class="flex-1 overflow-y-auto p-6 bg-white">  
                    <!-- Header Section -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">Schedule Initial Interview</h2>
                                <p class="text-gray-600">For: {{ $application->name }} - {{ $application->job->title }}</p>
                            </div>
                            <a href="{{ route('staff.recruitment.initial_interviews') }}" class="flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-arrow-left mr-2"></i> Back to Applicants
                            </a>
                        </div>
                        <hr class="border-gray-200 my-4">
                    </div>
                    <!-- Main Card -->
                    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 mb-6">
                        <h3 class="text-xl font-semibold mb-6 text-gray-800">Interview Details</h3>
                        
                        <!-- Schedule Interview Form -->
                        <form action="{{ route('staff.recruitment.storeInitialInterview', $application->id) }}" method="POST">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Date & Time -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Interview Date*</label>
                                    <input type="date" name="interview_date" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('interview_date') border-red-500 @enderror" 
                                           required min="{{ date('Y-m-d') }}" value="{{ old('interview_date') }}">
                                    @error('interview_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Interview Time*</label>
                                    <input type="time" name="interview_time" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('interview_time') border-red-500 @enderror" 
                                           required value="{{ old('interview_time') }}">
                                    @error('interview_time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Interviewer -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Interviewer*</label>
                                    <select name="interviewer_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('interviewer_id') border-red-500 @enderror" required>
                                        <option value="">Select Interviewer</option>
                                        @foreach($interviewers as $interviewer)
                                        <option value="{{ $interviewer->id }}" @selected(old('interviewer_id') == $interviewer->id)>
                                            {{ $interviewer->first_name }} {{ $interviewer->last_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('interviewer_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Interview Type -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Interview Type*</label>
                                    <select id="interview_type" name="interview_type" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="in_person" @selected(old('interview_type') == 'in_person')>In-Person</option>
                                        <option value="virtual" @selected(old('interview_type') == 'virtual')>Virtual</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Location/Meeting Link -->
                            <div class="grid grid-cols-1 gap-6 mb-6">
                                <div id="location-field" class="hidden">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Location*</label>
                                    <input type="text" name="location" id="location"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="Enter interview location">
                                </div>
                                
                                <div id="meeting-link-field" class="hidden">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Meeting Link*</label>
                                    <input type="url" name="meeting_link" id="meeting_link"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="https://meet.google.com/xxx-yyyy-zzz">
                                </div>
                            </div>
                            
                            <!-- Notes -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notes/Instructions</label>
                                <textarea name="notes" rows="3" 
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-500 @enderror" 
                                          placeholder="Any special instructions for the candidate">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                                <div class="flex items-center">
                                    <input id="send_email" name="send_email" type="checkbox" 
                                           class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked>
                                    <label for="send_email" class="ml-2 block text-sm text-gray-700">
                                        Send invitation email to candidate
                                    </label>
                                </div>
                                <div class="flex space-x-3">
                                    <button type="reset" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-redo mr-2"></i> Reset
                                    </button>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-calendar-check mr-2"></i> Schedule Interview
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        <!-- Additional Actions Section -->
                        <div class="mt-8 space-y-6">
                            <!-- Reschedule Card -->
                            <div class="bg-white rounded-lg shadow-md p-6 border border-blue-200 mb-6">
                                <h3 class="text-lg font-medium mb-4 text-blue-700">
                                    <i class="fas fa-calendar-alt mr-2"></i> Reschedule Interview
                                </h3>
                                <form action="{{ route('staff.recruitment.reschedule_interview', $application->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">New Date*</label>
                                            <input type="date" name="reschedule_date" 
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                   required min="{{ date('Y-m-d') }}">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">New Time*</label>
                                            <input type="time" name="reschedule_time" 
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Reschedule Reason</label>
                                        <textarea name="reschedule_reason" rows="2"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Reason for rescheduling..."></textarea>
                                    </div>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-calendar-plus mr-2"></i> Request Reschedule
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Result Card -->
                            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                                <h3 class="text-lg font-medium mb-4 text-gray-800">Interview Result</h3>
                                <form action="{{ route('staff.recruitment.mark_passed', $application->id) }}" method="POST">
                                    @csrf
                                    <div class="flex flex-wrap gap-4 mb-4">
                                        <button type="submit" name="passed" value="1" 
                                                class="flex-1 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <i class="fas fa-check mr-2"></i> Mark as Passed
                                        </button>
                                        <button type="submit" name="passed" value="0" 
                                                class="flex-1 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <i class="fas fa-times mr-2"></i> Mark as Failed
                                        </button>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Evaluation Notes</label>
                                        <textarea name="notes" rows="3" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Enter evaluation notes..."></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const interviewType = document.getElementById('interview_type');
        const locationField = document.getElementById('location-field');
        const meetingLinkField = document.getElementById('meeting-link-field');

        function toggleFields() {
            if (interviewType.value === 'in_person') {
                locationField.classList.remove('hidden');
                meetingLinkField.classList.add('hidden');
            } else if (interviewType.value === 'virtual') {
                meetingLinkField.classList.remove('hidden');
                locationField.classList.add('hidden');
            }
        }

        interviewType.addEventListener('change', toggleFields);
        toggleFields(); // run on load
    });

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