<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Recruitment System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Smooth transitions */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }
        
        /* Card hover effect */
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
@include('layouts.partials.admin-navbar')
@include('layouts.partials.admin-sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 overflow-y-auto lg:ml-64 transition-all duration-200 bg-gray-50">
            <!-- Main Content -->
            <main class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Recruitment Dashboard</h1>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">Last updated: {{ now()->format('M d, Y h:i A') }}</span>
                        <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                    <!-- Applicants -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 card-hover transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Applicants</p>
                                <h3 class="text-2xl font-bold mt-1">{{ $stats['applicants'] ?? 0 }}</h3>
                            </div>
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <i class="fi fi-sr-document text-blue-500 text-lg"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.recruitment.applicants') }}" class="inline-flex items-center mt-4 text-sm font-medium text-blue-600 hover:text-blue-800">
                            View all <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>

                    <!-- Initial Interviews -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 card-hover transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Initial Interviews</p>
                                <h3 class="text-2xl font-bold mt-1">{{ $stats['initial_interviews'] ?? 0 }}</h3>
                            </div>
                            <div class="bg-purple-50 p-3 rounded-lg">
                                <i class="fi fi-sr-comment-alt text-purple-500 text-lg"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.recruitment.initial_interviews') }}" class="inline-flex items-center mt-4 text-sm font-medium text-purple-600 hover:text-purple-800">
                            View all <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>

                    <!-- Practical Demos -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 card-hover transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Practical Demos</p>
                                <h3 class="text-2xl font-bold mt-1">{{ $stats['demos'] ?? 0 }}</h3>
                            </div>
                            <div class="bg-yellow-50 p-3 rounded-lg">
                                <i class="fi fi-sr-presentation text-yellow-500 text-lg"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.recruitment.demos') }}" class="inline-flex items-center mt-4 text-sm font-medium text-yellow-600 hover:text-yellow-800">
                            View all <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>

                    <!-- Written Exams -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 card-hover transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Written Exams</p>
                                <h3 class="text-2xl font-bold mt-1">{{ $stats['exams'] ?? 0 }}</h3>
                            </div>
                            <div class="bg-green-50 p-3 rounded-lg">
                                <i class="fi fi-sr-clipboard text-green-500 text-lg"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.recruitment.exams') }}" class="inline-flex items-center mt-4 text-sm font-medium text-green-600 hover:text-green-800">
                            View all <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Second Row Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                    <!-- Final Interviews -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 card-hover transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Final Interviews</p>
                                <h3 class="text-2xl font-bold mt-1">{{ $stats['final_interviews'] ?? 0 }}</h3>
                            </div>
                            <div class="bg-indigo-50 p-3 rounded-lg">
                                <i class="fi fi-sr-users-alt text-indigo-500 text-lg"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.recruitment.final_interviews') }}" class="inline-flex items-center mt-4 text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            View all <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>

                    <!-- Pre-Employment -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 card-hover transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pre-Employment</p>
                                <h3 class="text-2xl font-bold mt-1">{{ $stats['pre_employment'] ?? 0 }}</h3>
                            </div>
                            <div class="bg-orange-50 p-3 rounded-lg">
                                <i class="fi fi-sr-file-contract text-orange-500 text-lg"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.recruitment.pre_employment') }}" class="inline-flex items-center mt-4 text-sm font-medium text-orange-600 hover:text-orange-800">
                            View all <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>

                    <!-- Onboarding -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 card-hover transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Onboarding</p>
                                <h3 class="text-2xl font-bold mt-1">{{ $stats['onboarding'] ?? 0 }}</h3>
                            </div>
                            <div class="bg-teal-50 p-3 rounded-lg">
                                <i class="fi fi-sr-user-add text-teal-500 text-lg"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.recruitment.onboarding') }}" class="inline-flex items-center mt-4 text-sm font-medium text-teal-600 hover:text-teal-800">
                            View all <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>

                    <!-- Hired -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 card-hover transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Hired</p>
                                <h3 class="text-2xl font-bold mt-1">{{ $stats['hired'] ?? 0 }}</h3>
                            </div>
                            <div class="bg-emerald-50 p-3 rounded-lg">
                                <i class="fi fi-sr-badge-check text-emerald-500 text-lg"></i>
                            </div>
                        </div>
                      
                    </div>
                </div>

                <!-- Candidate Stages Sections -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Initial Interview Candidates -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-5 border-b border-gray-100">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-800">Initial Interview Candidates</h3>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2.5 py-0.5 rounded-full">{{ $initialInterviewCandidates->count() }}</span>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($initialInterviewCandidates as $candidate)
                            <div class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                                        <span class="text-purple-600 font-medium">{{ substr($candidate->firstname, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $candidate->firstname }} {{ $candidate->lastname }}</p>
                                        <p class="text-sm text-gray-500">{{ $candidate->job->title ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                    {{ str_replace('_', ' ', $candidate->status) }}
                                </span>
                            </div>
                            @empty
                            <div class="p-4 text-center text-gray-500">
                                No candidates in this stage
                            </div>
                            @endforelse
                        </div>
                        <div class="p-4 border-t border-gray-100">
                            <a href="{{ route('admin.recruitment.initial_interviews') }}" class="text-sm font-medium text-purple-600 hover:text-purple-800 inline-flex items-center">
                                View all initial interviews <i class="fas fa-chevron-right ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Demo Candidates -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-5 border-b border-gray-100">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-800">Demo Candidates</h3>
                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2.5 py-0.5 rounded-full">{{ $demoCandidates->count() }}</span>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($demoCandidates as $candidate)
                            <div class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 h-10 w-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <span class="text-yellow-600 font-medium">{{ substr($candidate->firstname, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $candidate->firstname }} {{ $candidate->lastname }}</p>
                                        <p class="text-sm text-gray-500">{{ $candidate->job->title ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                    {{ str_replace('_', ' ', $candidate->status) }}
                                </span>
                            </div>
                            @empty
                            <div class="p-4 text-center text-gray-500">
                                No candidates in this stage
                            </div>
                            @endforelse
                        </div>
                        <div class="p-4 border-t border-gray-100">
                            <a href="{{ route('admin.recruitment.demos') }}" class="text-sm font-medium text-yellow-600 hover:text-yellow-800 inline-flex items-center">
                                View all demos <i class="fas fa-chevron-right ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- More Candidate Stages -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Exam Candidates -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Exam Candidates</h3>
                        <div class="space-y-4">
                            @forelse($examCandidates as $candidate)
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded">
                                <div>
                                    <p class="font-medium">{{ $candidate->firstname }} {{ $candidate->lastname }}</p>
                                    <p class="text-sm text-gray-500">{{ $candidate->job->title ?? 'N/A' }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                    {{ str_replace('_', ' ', $candidate->status) }}
                                </span>
                            </div>
                            @empty
                            <p class="text-gray-500">No candidates in this stage</p>
                            @endforelse
                        </div>
                        <a href="{{ route('admin.recruitment.exams') }}" class="text-sm font-medium text-green-600 hover:text-green-800 inline-flex items-center">
                            View all exams <i class="fas fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>

                    <!-- Final Interview Candidates -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Final Interview Candidates</h3>
                        <div class="space-y-4">
                            @forelse($finalInterviewCandidates as $candidate)
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded">
                                <div>
                                    <p class="font-medium">{{ $candidate->firstname }} {{ $candidate->lastname }}</p>
                                    <p class="text-sm text-gray-500">{{ $candidate->job->title ?? 'N/A' }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs bg-indigo-100 text-indigo-800 rounded-full">
                                    {{ str_replace('_', ' ', $candidate->status) }}
                                </span>
                            </div>
                            @empty
                            <p class="text-gray-500">No candidates in this stage</p>
                            @endforelse
                        </div>
                        <a href="{{ route('admin.recruitment.final_interviews') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 inline-flex items-center">
                            View all final interviews <i class="fas fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Pre-Employment & Onboarding -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Pre-Employment Candidates -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Pre-Employment Candidates</h3>
                        <div class="space-y-4">
                            @forelse($preEmploymentCandidates as $candidate)
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded">
                                <div>
                                    <p class="font-medium">{{ $candidate->firstname }} {{ $candidate->lastname }}</p>
                                    <p class="text-sm text-gray-500">{{ $candidate->job->title ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    @if($candidate->preEmploymentDocument)
                                        @php
                                            $progress = $candidate->preEmploymentStatus();
                                            $color = [
                                                'not-started' => 'bg-gray-100 text-gray-800',
                                                'in-progress' => 'bg-blue-100 text-blue-800',
                                                'documents-completed' => 'bg-teal-100 text-teal-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                            ][$progress];
                                        @endphp
                                        <span class="px-3 py-1 text-xs rounded-full {{ $color }}">
                                            {{ str_replace('-', ' ', $progress) }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">
                                            not started
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500">No candidates in this stage</p>
                            @endforelse
                        </div>
                        <a href="{{ route('admin.recruitment.pre_employment') }}" class="text-sm font-medium text-orange-600 hover:text-orange-800 inline-flex items-center">
                            View all pre-employments <i class="fas fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>

                    <!-- Onboarding Candidates -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Onboarding Candidates</h3>
                        <div class="space-y-4">
                            @forelse($onboardingCandidates as $candidate)
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded">
                                <div>
                                    <p class="font-medium">{{ $candidate->firstname }} {{ $candidate->lastname }}</p>
                                    <p class="text-sm text-gray-500">{{ $candidate->job->title ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    @if($candidate->onboarding)
                                        @php
                                            $progress = $candidate->onboarding->calculateProgress();
                                        @endphp
                                        <div class="flex items-center">
                                            <div class="w-16 mr-2">
                                                <div class="h-2 bg-gray-200 rounded-full">
                                                    <div class="h-2 bg-teal-500 rounded-full" style="width: {{ $progress }}%"></div>
                                                </div>
                                            </div>
                                            <span class="text-xs">{{ $progress }}%</span>
                                        </div>
                                    @else
                                        <span class="px-3 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">
                                            not started
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500">No candidates in this stage</p>
                            @endforelse
                        </div>
                        <a href="{{ route('admin.recruitment.onboarding') }}" class="text-sm font-medium text-teal-600 hover:text-teal-800 inline-flex items-center">
                            View all onboarding <i class="fas fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>

              <!-- Recent Activity -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="border-b border-gray-100 p-5">
        <h2 class="font-semibold text-gray-800">Recent Recruitment Activity</h2>
    </div>
    <div class="p-5">
        <div class="space-y-4">
            @forelse($recentActivities ?? [] as $activity)
            <div class="flex items-start pb-4 last:pb-0 last:border-b-0 border-b border-gray-100">
                <div class="p-2 rounded-lg mr-4 flex-shrink-0 {{ $activity->status_class }}">
                    <i class="{{ $activity->status_icon }}"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800">
                        <span class="font-semibold">{{ $activity->firstname }} {{ $activity->lastname }}</span> - {{ $activity->description }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-6">
                <i class="fi fi-sr-calendar-clock text-gray-300 text-3xl mb-2"></i>
                <p class="text-sm text-gray-500">No recent activities</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
            </main>
        </div>

    <script>
        // Sidebar toggle functionality
        document.querySelector('.sidebar-toggle').addEventListener('click', function() {
            const sidebar = document.querySelector('aside');
            sidebar.classList.toggle('-translate-x-full');
            
            // Adjust main content margin when sidebar is shown/hidden on mobile
            if (window.innerWidth < 1024) {
                const mainContent = document.querySelector('.flex-1');
                if (sidebar.classList.contains('-translate-x-full')) {
                    mainContent.classList.remove('ml-64');
                } else {
                    mainContent.classList.add('ml-64');
                }
            }
        });

        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const icon = document.getElementById(`${dropdownId}Icon`);
            
            dropdown.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
            
            // Close other dropdowns when opening a new one
            if (!dropdown.classList.contains('hidden')) {
                document.querySelectorAll('[id$="Dropdown"]').forEach(otherDropdown => {
                    if (otherDropdown.id !== dropdownId && !otherDropdown.classList.contains('hidden')) {
                        otherDropdown.classList.add('hidden');
                        const otherIcon = document.getElementById(`${otherDropdown.id}Icon`);
                        if (otherIcon) otherIcon.classList.remove('rotate-180');
                    }
                });
            }
        }
        
        // Automatically open dropdown if current route matches
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = ['jobRequisitionDropdown'];
            
            dropdowns.forEach(dropdownId => {
                const dropdown = document.getElementById(dropdownId);
                if (dropdown) {
                    const links = dropdown.querySelectorAll('a');
                    
                    links.forEach(link => {
                        if (link.classList.contains('bg-blue-50')) {
                            document.getElementById(dropdownId).classList.remove('hidden');
                            const icon = document.getElementById(`${dropdownId}Icon`);
                            if (icon) icon.classList.add('rotate-180');
                        }
                    });
                }
            });
        });

        // Dropdown menu functionality
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
</body>
</html>