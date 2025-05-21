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
                            <h1 class="text-2xl font-bold text-gray-800">Written Exams</h1>
                            <p class="text-gray-500 mt-1">Manage all scheduled written examinations</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('staff.recruitment.scheduleExam') }}" class="btn btn-primary shadow-sm hover:shadow-md transition-shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                </svg>
                                Schedule New Exam
                            </a>
                        </div>
                    </div>

                    <!-- Status Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                        <!-- Total Exams -->
                        <div class="bg-white p-4 rounded-lg shadow border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Total Exams</p>
                                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalExams }}</p>
                                </div>
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Completed -->
                        <div class="bg-white p-4 rounded-lg shadow border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Completed</p>
                                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $completedExams }}</p>
                                </div>
                                <div class="p-3 rounded-full bg-green-100 text-green-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pending -->
                        <div class="bg-white p-4 rounded-lg shadow border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Pending</p>
                                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $pendingExams }}</p>
                                </div>
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Exams Table -->
                    <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden mb-8">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-800">Scheduled Exams</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidate</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($applications as $application)
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
                                            <span class="px-2.5 py-1 text-xs font-semibold leading-4 rounded-full 
                                                {{ $application->status === 'exam_scheduled' ? 'bg-blue-100 text-blue-800' : 
                                                   ($application->status === 'exam_completed' ? 'bg-purple-100 text-purple-800' :
                                                   ($application->status === 'exam_passed' ? 'bg-green-100 text-green-800' :
                                                   ($application->status === 'exam_failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                                {{ str_replace('_', ' ', $application->status) }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                @if($application->status === 'exam_scheduled')
                                                    <button onclick="openCompleteModal({{ $application->id }})" 
                                                       class="btn btn-success btn-sm flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Complete
                                                    </button>
                                                @endif
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

                    <!-- Evaluation Results Section -->
                    <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-800">Exam Evaluation History</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Candidate</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Result</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Evaluated On</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Details</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($evaluations as $evaluation)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-medium">
                                                    {{ substr($evaluation->application->firstname, 0, 1) }}{{ substr($evaluation->application->lastname, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="font-medium text-gray-900">{{ $evaluation->application->firstname }} {{ $evaluation->application->lastname }}</div>
                                                    <div class="text-sm text-gray-500">{{ $evaluation->application->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold 
                                                  {{ $evaluation->score >= 70 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $evaluation->score }}%
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold 
                                                  {{ $evaluation->passed ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                                {{ $evaluation->passed ? 'Passed' : 'Failed' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $evaluation->completed_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button onclick="showEvaluationDetails({{ $evaluation->id }})" 
                                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($evaluations->hasPages())
                        <div class="px-6 py-4 border-t bg-gray-50">
                            {{ $evaluations->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<!-- Evaluation Details Modal -->
<div id="evaluationDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-xl font-bold">Exam Evaluation Details</h3>
            <button onclick="hideModal('evaluationDetailsModal')" class="text-gray-500 hover:text-gray-700">
                &times;
            </button>
        </div>
        <div id="evaluationDetailsContent"></div>
    </div>
</div>

<!-- Complete Exam Modal -->
<div id="completeExamModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
        <h3 class="text-xl font-bold mb-4">Complete Exam Evaluation</h3>
        <form id="completeExamForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Score Input -->
                <div>
                    <label class="block text-gray-700 mb-2">Overall Score (0-100)*</label>
                    <input type="number" name="score" min="0" max="100" step="0.1" required
                           class="w-full px-3 py-2 border rounded-md">
                </div>
                
                <!-- Pass/Fail Selection -->
                <div>
                    <label class="block text-gray-700 mb-2">Final Result*</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="passed" value="1" checked 
                                   class="form-radio h-5 w-5 text-green-500">
                            <span class="ml-2">Passed</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="passed" value="0"
                                   class="form-radio h-5 w-5 text-red-500">
                            <span class="ml-2">Failed</span>
                        </label>
                    </div>
                </div>
                
                <!-- Criteria Breakdown -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Criteria Evaluation*</label>
                    <div class="space-y-3 bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Knowledge (0-25)</label>
                                <input type="number" name="criteria[knowledge]" min="0" max="25" required
                                       class="w-full px-2 py-1 border rounded">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Problem Solving (0-25)</label>
                                <input type="number" name="criteria[problem_solving]" min="0" max="25" required
                                       class="w-full px-2 py-1 border rounded">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Technical Skills (0-25)</label>
                                <input type="number" name="criteria[technical_skills]" min="0" max="25" required
                                       class="w-full px-2 py-1 border rounded">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Communication (0-15)</label>
                                <input type="number" name="criteria[communication]" min="0" max="15" required
                                       class="w-full px-2 py-1 border rounded">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Time Management (0-10)</label>
                                <input type="number" name="criteria[time_management]" min="0" max="10" required
                                       class="w-full px-2 py-1 border rounded">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Feedback Sections -->
                <div>
                    <label class="block text-gray-700 mb-2">Strengths</label>
                    <textarea name="strengths" rows="3" 
                              class="w-full px-3 py-2 border rounded-md" placeholder="Candidate's strong points"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Areas for Improvement</label>
                    <textarea name="weaknesses" rows="3" 
                              class="w-full px-3 py-2 border rounded-md" placeholder="Areas needing work"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Additional Feedback</label>
                    <textarea name="feedback" rows="3" 
                              class="w-full px-3 py-2 border rounded-md" placeholder="Overall comments"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Submit Evaluation
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCompleteModal(applicationId) {
        const modal = document.getElementById('completeExamModal');
        const form = document.getElementById('completeExamForm');
        
        form.action = `/staff/recruitment/exams/${applicationId}/complete`;
        modal.classList.remove('hidden');
        
        // Reset form when opening
        form.reset();
        document.querySelector('input[name="passed"][value="1"]').checked = true;
    }

    function closeModal() {
        document.getElementById('completeExamModal').classList.add('hidden');
    }

    // Auto-calculate total score based on criteria
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('completeExamForm');
        if (form) {
            const criteriaInputs = form.querySelectorAll('input[name^="criteria"]');
            const scoreInput = form.querySelector('input[name="score"]');
            
            criteriaInputs.forEach(input => {
                input.addEventListener('input', updateTotalScore);
            });
            
            function updateTotalScore() {
                let total = 0;
                criteriaInputs.forEach(input => {
                    total += parseFloat(input.value) || 0;
                });
                scoreInput.value = total.toFixed(1);
            }
        }
    });

    function showEvaluationDetails(evaluationId) {
        fetch(`/staff/recruitment/exams/evaluations/${evaluationId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('evaluationDetailsContent').innerHTML = data.html;
                document.getElementById('evaluationDetailsModal').classList.remove('hidden');
            })
            .catch(error => console.error('Error:', error));
    }

    function hideModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Close modals when clicking outside
    document.getElementById('evaluationDetailsModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            hideModal('evaluationDetailsModal');
        }
    });

    document.getElementById('completeExamModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Dropdown handling (keep your existing dropdown code)
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.dropdown').forEach(dropdown => {
            const btn = dropdown.querySelector('button');
            const menu = dropdown.querySelector('.dropdown-menu');

            if (btn && menu) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    document.querySelectorAll('.dropdown-menu').forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });
                    menu.classList.toggle('hidden');
                });
            }
        });

        document.addEventListener('click', function () {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        });
    });
</script>
@endsection