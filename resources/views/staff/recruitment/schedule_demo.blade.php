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
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">Schedule Demo Session</h1>
                    <p class="text-gray-600">Schedule a practical demo for {{ $application->user->name }}</p>
                </div>

                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-800">Candidate Information</h2>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Name</p>
                                <p class="font-medium">{{ $application->firstname ?? 'N/A' }} {{ $application->lastname ?? '' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Position</p>
                                <p class="font-medium">{{ $application->job->title }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium">{{ $application->user->email }}</p>
                            </div>
                            
                        </div>
                    </div>

                    <form method="POST" action="{{ route('staff.recruitment.storeDemo', $application->id) }}" class="p-6">
    @csrf

    <div class="space-y-6">
        <!-- Date and Time -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="interview_date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="interview_date" id="interview_date" 
                       class="form-input w-full" 
                       min="{{ now()->format('Y-m-d') }}" required>
            </div>
            <div>
                <label for="interview_time" class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                <input type="time" name="interview_time" id="interview_time" 
                       class="form-input w-full" required>
            </div>
        </div>

        <!-- Location -->
        <div>
            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
            <input type="text" name="location" id="location" class="form-input w-full" required>
        </div>

        <!-- Preparation Instructions -->
        <div>
            <label for="preparation_instructions" class="block text-sm font-medium text-gray-700 mb-1">Preparation Instructions</label>
            <textarea name="preparation_instructions" id="preparation_instructions" 
                      rows="3" class="form-textarea w-full"
                      placeholder="What should the candidate prepare?" required></textarea>
        </div>

        <!-- Additional Notes -->
        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
            <textarea name="notes" id="notes" rows="3" 
                      class="form-textarea w-full"
                      placeholder="Any special instructions..."></textarea>
        </div>

        <!-- Send Email (hidden since we always want to send) -->
        <input type="hidden" name="send_email" value="1">
    </div>

    <div class="mt-8 flex justify-end space-x-3">
        <a href="{{ route('staff.recruitment.demos') }}" class="btn btn-secondary">
            Cancel
        </a>
        <button type="submit" class="btn btn-primary">
            Schedule Demo
        </button>
    </div>
</form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    // Show/hide location fields based on demo type
    document.querySelectorAll('input[name="interview_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'in_person') {
                document.getElementById('location_fields').style.display = 'block';
                document.getElementById('virtual_fields').style.display = 'none';
            } else {
                document.getElementById('location_fields').style.display = 'none';
                document.getElementById('virtual_fields').style.display = 'block';
            }
        });
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