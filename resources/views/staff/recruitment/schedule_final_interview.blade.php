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
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Schedule Final Interview</h1>
                            <p class="text-gray-500 mt-1">Schedule final interviews for qualified candidates</p>
                        </div>
                        <a href="{{ route('staff.recruitment.finalInterviews') }}" class="btn btn-outline-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            Back to Interviews
                        </a>
                    </div>

                    <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden mb-8">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-800">Candidates Ready for Final Interview</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidate</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam Score</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($applications as $application)
                                    <tr class="hover:bg-gray-50">
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $application->job->title ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
    @if($application->examEvaluation && $application->examEvaluation->score)
        <span class="px-3 py-1 rounded-full text-sm font-semibold 
            {{ $application->examEvaluation->score >= 70 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
            {{ $application->examEvaluation->score }}%
        </span>
    @else
        <span class="text-gray-400">N/A</span>
    @endif
</td>

<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
    <div class="flex justify-end items-center">
        <button onclick="openScheduleModal({{ $application->id }})" 
                class="btn btn-primary btn-sm flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Schedule
        </button>
    </div>
</td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            {{ $applications->links() }}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<!-- Schedule Interview Modal -->
<div id="scheduleInterviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Schedule Final Interview</h3>
        <form id="scheduleInterviewForm" method="POST" action="">
            @csrf
            
            <div class="space-y-4">
                <!-- Date and Time -->
                <div>
                    <label for="scheduled_at" class="block text-gray-700 mb-2">Date & Time*</label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" required
                           class="w-full px-3 py-2 border rounded-md" min="{{ now()->format('Y-m-d\TH:i') }}">
                </div>
                
                <!-- Interviewer -->
                <div>
                    <label for="interviewers" class="block text-gray-700 mb-2">Interviewer(s)*</label>
                    <input type="text" name="interviewers" id="interviewers" required
                           class="w-full px-3 py-2 border rounded-md" placeholder="Interviewer names">
                </div>
                
                <!-- Location/Online Meeting -->
                <div>
                    <label class="block text-gray-700 mb-2">Interview Type</label>
                    <div class="flex space-x-4 mb-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="interview_type" value="in_person" checked 
                                   class="form-radio h-5 w-5 text-blue-500" onclick="toggleInterviewType('in_person')">
                            <span class="ml-2">In-Person</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="interview_type" value="virtual"
                                   class="form-radio h-5 w-5 text-blue-500" onclick="toggleInterviewType('virtual')">
                            <span class="ml-2">Virtual</span>
                        </label>
                    </div>
                    
                    <div id="locationField">
                        <label for="location" class="block text-gray-700 mb-2">Location*</label>
                        <input type="text" name="location" id="location"
                               class="w-full px-3 py-2 border rounded-md" placeholder="Interview location">
                    </div>
                    
                    <div id="meetingLinkField" class="hidden">
                        <label for="meeting_link" class="block text-gray-700 mb-2">Meeting Link*</label>
                        <input type="url" name="meeting_link" id="meeting_link"
                               class="w-full px-3 py-2 border rounded-md" placeholder="https://meet.example.com/your-meeting">
                    </div>
                </div>
                
                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-gray-700 mb-2">Additional Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-3 py-2 border rounded-md" placeholder="Any special instructions"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeScheduleModal()" 
                        class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Schedule Interview
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openScheduleModal(applicationId) {
        const modal = document.getElementById('scheduleInterviewModal');
        const form = document.getElementById('scheduleInterviewForm');
        
        form.action = `/staff/recruitment/schedule-final-interview/${applicationId}`;
        modal.classList.remove('hidden');
        
        // Reset form when opening
        form.reset();
        document.querySelector('input[name="interview_type"][value="in_person"]').checked = true;
        document.getElementById('meetingLinkField').classList.add('hidden');
        document.getElementById('locationField').classList.remove('hidden');
    }

    function closeScheduleModal() {
        document.getElementById('scheduleInterviewModal').classList.add('hidden');
    }

    function toggleInterviewType(type) {
        if (type === 'in_person') {
            document.getElementById('locationField').classList.remove('hidden');
            document.getElementById('meetingLinkField').classList.add('hidden');
        } else {
            document.getElementById('locationField').classList.add('hidden');
            document.getElementById('meetingLinkField').classList.remove('hidden');
        }
    }

    // Close modal when clicking outside
    document.getElementById('scheduleInterviewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeScheduleModal();
        }
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