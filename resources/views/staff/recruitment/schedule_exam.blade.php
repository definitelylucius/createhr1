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
                        <h1 class="text-2xl font-bold text-gray-800">Schedule New Exam</h1>
                        <p class="text-gray-500 mt-1">Schedule a written examination for a candidate</p>
                    </div>
                    <div>
                        <a href="{{ route('staff.recruitment.exams') }}" class="btn btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Back to Exams
                        </a>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden p-6">
                    <form action="{{ route('staff.recruitment.storeExam') }}" method="POST">
                        @csrf

                        <!-- Rest of your form remains exactly the same -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Candidate Selection -->
                            <div class="col-span-1">
                                <label for="application_id" class="block text-sm font-medium text-gray-700 mb-1">Candidate</label>
                                <select name="application_id" id="application_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Select Candidate</option>
                                    @foreach($candidates as $candidate)
                                        <option value="{{ $candidate->id }}">
                                            {{ $candidate->firstname }} {{ $candidate->lastname }} - {{ $candidate->job->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('application_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Exam Type -->
                            <div class="col-span-1">
                                <label for="exam_type" class="block text-sm font-medium text-gray-700 mb-1">Exam Type</label>
                                <select name="exam_type" id="exam_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Select Exam Type</option>
                                    <option value="written_test">Written Test</option>
                                    <option value="technical_exam">Technical Exam</option>
                                    <option value="psychometric_test">Psychometric Test</option>
                                    <option value="language_test">Language Test</option>
                                </select>
                                @error('exam_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Scheduled Date -->
                            <div class="col-span-1">
                                <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-1">Scheduled Date & Time</label>
                                <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                @error('scheduled_at')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Duration -->
                            <div class="col-span-1">
                                <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Duration (minutes)</label>
                                <input type="number" name="duration" id="duration" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="120" required>
                                @error('duration')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div class="col-span-1 md:col-span-2">
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                <input type="text" name="location" id="location" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Exam Content Description -->
                            <div class="col-span-1 md:col-span-2">
                                <label for="exam_content" class="block text-sm font-medium text-gray-700 mb-1">Exam Content Description</label>
                                <textarea name="exam_content" id="exam_content" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="What will the exam cover?"></textarea>
                                @error('exam_content')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Location Map Link -->
                            <div class="col-span-1 md:col-span-2">
                                <label for="map_link" class="block text-sm font-medium text-gray-700 mb-1">Location Map Link (Optional)</label>
                                <input type="url" name="map_link" id="map_link" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="https://maps.google.com/...">
                                @error('map_link')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Special Instructions -->
                            <div class="col-span-1 md:col-span-2">
                                <label for="instructions" class="block text-sm font-medium text-gray-700 mb-1">Special Instructions</label>
                                <textarea name="instructions" id="instructions" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Any special instructions for the candidate"></textarea>
                                @error('instructions')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Schedule Exam
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</div>
<script>
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