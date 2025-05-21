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
                            <h1 class="text-2xl font-bold text-gray-800">Schedule Document Verification</h1>
                            <p class="text-gray-500 mt-1">Schedule document verification for pre-employment candidates</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('staff.recruitment.pre-employment.index') }}" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to Pre-Employment
                            </a>
                        </div>
                    </div>

                    <!-- Main Card -->
                    <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                        <div class="p-6">
                            <form action="{{ route('staff.recruitment.pre-employment.schedule.store') }}" method="POST">
                                @csrf
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Left Column -->
                                    <div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Candidate</label>
                                            <select name="application_id" class="form-select w-full" required>
                                                <option value="">Select Candidate</option>
                                                @foreach($applications as $application)
                                                <option value="{{ $application->id }}" {{ old('application_id') == $application->id ? 'selected' : '' }}>
    {{ $application->firstname }} {{ $application->lastname }} - {{ $application->job->title ?? 'N/A' }}
</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Verification Type</label>
                                            <select name="verification_type" class="form-select w-full" required>
                                                <option value="">Select Verification Type</option>
                                                <option value="document_verification">Document Verification</option>
                                                <option value="background_check">Background Check</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Right Column -->
                                    <div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Verification Date</label>
                                            <input type="datetime-local" name="scheduled_date" class="form-input w-full" required>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                            <input type="text" name="location" class="form-input w-full" placeholder="Verification location" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                    <textarea name="notes" class="form-textarea w-full" rows="3" placeholder="Any special instructions for verification..."></textarea>
                                </div>
                                
                                <div class="flex justify-end mt-6">
                                    <button type="submit" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Schedule Verification
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Calendar Preview -->
                    <div class="mt-8">
                        <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                            <div class="px-6 py-4 border-b bg-gray-50">
                                <h2 class="text-lg font-medium text-gray-800">Upcoming Document Verifications</h2>
                            </div>
                            <div class="p-4">
                                <div id="calendar" style="min-height: 500px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

@section('scripts')
<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css' rel='stylesheet' />

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize calendar
    var calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [
    @foreach($scheduledApplications as $application)
    {
        title: '{{ $application->firstname }} {{ $application->lastname }} - Document Verification',
        start: '{{ $application->preEmploymentDocument->scheduled_date }}',
        extendedProps: {
            status: '{{ $application->preEmploymentStatus() }}',
            type: '{{ $application->preEmploymentDocument->verification_type ?? "document_verification" }}'
        },
        backgroundColor: function(arg) {
            switch(arg.event.extendedProps.status) {
                case 'completed': return '#28a745';
                case 'verified': return '#17a2b8';
                case 'pending': return '#ffc107';
                default: return '#dc3545';
            }
        },
        borderColor: '#ffffff'
    },
    @endforeach
],
            eventDidMount: function(info) {
                // Add tooltip with more information
                $(info.el).tooltip({
                    title: `
                        <strong>${info.event.title}</strong><br>
                        Type: ${info.event.extendedProps.type}<br>
                        Status: ${info.event.extendedProps.status}<br>
                        Time: ${info.event.start.toLocaleString()}
                    `,
                    html: true,
                    placement: 'top',
                    container: 'body'
                });
            }
        });
        calendar.render();
    }

    // Initialize date picker with min time of now
    const now = new Date();
    const timezoneOffset = now.getTimezoneOffset() * 60000;
    const localISOTime = (new Date(now - timezoneOffset)).toISOString().slice(0, 16);
    document.querySelector('input[type="datetime-local"]').min = localISOTime;
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
    }); document.addEventListener('DOMContentLoaded', function () {
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
@endsection