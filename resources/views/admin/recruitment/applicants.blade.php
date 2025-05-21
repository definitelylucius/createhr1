@extends('layouts.staff')

@section('content')
<!-- Main Layout Structure -->
<div class="flex h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-20 w-64 bg-white shadow transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out">
        @include('layouts.partials.admin-sidebar')
    </aside>
    
    <div class="flex-1 flex flex-col overflow-hidden lg:ml-64">
        <!-- Navbar -->
        <header class="fixed top-0 left-0 right-0 h-16 bg-white shadow-sm z-30 border-b border-gray-100 lg:left-64">
            <div class="flex items-center justify-between h-full px-6">
                <!-- Left Section -->
                <div class="flex items-center space-x-4">
                    <button class="sidebar-toggle text-gray-600 lg:hidden focus:outline-none">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <h4 class="text-lg font-semibold text-gray-800">@yield('title')</h4>
                </div>

                <!-- Center Title -->
                <div class="flex-1 text-center">
                    <div class="font-bold text-2xl text-[#00446b]">Nexfleet Dynamics</div>
                </div>

                <!-- Right Section -->
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <button class="text-gray-600 hover:text-gray-900 relative focus:outline-none">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
                        </button>
                    </div>

                    <div class="relative">
                        <div class="dropdown">
                            <button class="flex items-center text-gray-600 hover:text-gray-900 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-2">
                                    <span class="text-blue-600 font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <span class="hidden md:inline font-medium">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>

                            <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden border border-gray-100">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-user mr-2"></i> Profile
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-cog mr-2"></i> Settings
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto pt-16">
            <div class="container mx-auto px-4 py-6">
                <!-- Status Cards -->
                <div class="bg-white shadow-lg rounded-xl mb-8 p-6">
                    <h2 class="text-2xl font-bold mb-4">Applicant Status Overview</h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <!-- Total Applicants -->
                        <div class="bg-blue-100 p-4 rounded-lg text-center">
                            <p class="text-lg font-semibold text-blue-800">Total Applicants</p>
                            <p class="text-3xl font-bold text-blue-500">{{ $totalApplicants }}</p>
                        </div>

                        <!-- Total Initial Interviews -->
                        <div class="bg-yellow-100 p-4 rounded-lg text-center">
                            <p class="text-lg font-semibold text-yellow-800">Total Initial Interviews</p>
                            <p class="text-3xl font-bold text-yellow-500">{{ $totalInitialInterviews }}</p>
                        </div>

                        <!-- Total Interviews -->
                        <div class="bg-green-100 p-4 rounded-lg text-center">
                            <p class="text-lg font-semibold text-green-800">Total Interviews</p>
                            <p class="text-3xl font-bold text-green-500">{{ $totalInterviews }}</p>
                        </div>

                        <!-- Total Offers -->
                        <div class="bg-orange-100 p-4 rounded-lg text-center">
                            <p class="text-lg font-semibold text-orange-800">Total Offers</p>
                            <p class="text-3xl font-bold text-orange-500">{{ $totalOffers }}</p>
                        </div>

                        <!-- Total Hires -->
                        <div class="bg-purple-100 p-4 rounded-lg text-center">
                            <p class="text-lg font-semibold text-purple-800">Total Hires</p>
                            <p class="text-3xl font-bold text-purple-500">{{ $totalHires }}</p>
                        </div>
                    </div>
                </div>

                <!-- Applicants Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-800">Applicants List</h2>
                        <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full">
                            Total: {{ $applicants->total() }}
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stage</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($applicants as $applicant)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 font-medium">{{ substr($applicant->firstname, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $applicant->firstname }} {{ $applicant->lastname }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $applicant->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded 
                                            @if($applicant->current_stage == 'Initial Interview') bg-blue-100 text-blue-800
                                            @elseif($applicant->current_stage == 'Hired') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $applicant->current_stage }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($applicant->status === \App\Models\JobApplication::STATUS_APPLIED && $applicant->current_stage !== 'initial_interview')
                                        <form method="POST" action="{{ route('admin.applicants.status', $applicant->id) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="initial_interview_scheduled">
                                            <input type="hidden" name="current_stage" value="initial_interview">
                                            <input type="hidden" name="rejection_reason" value="">
                                            <button type="submit" class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Set Initial Interview
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                        {{ $applicants->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile sidebar toggle
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const sidebar = document.querySelector('#sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
            });
        }

        // Dropdown functionality
        document.querySelectorAll('.dropdown').forEach(dropdown => {
            const btn = dropdown.querySelector('button');
            const menu = dropdown.querySelector('.dropdown-menu');

            if (btn && menu) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    document.querySelectorAll('.dropdown-menu').forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });
                    menu.classList.toggle('hidden');
                });
            }
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        });

        // Close dropdowns with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });
    });
</script>
@endsection