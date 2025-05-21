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
            <!-- Completion Confirmation Modal -->
            <div id="completeDemoModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 max-w-sm w-full">
                    <h3 class="text-lg font-medium mb-4">Complete Demo Session</h3>
                    <p class="mb-4">Are you sure you want to mark this demo as complete?</p>
                    
                    <form id="completeDemoForm" method="POST" action="">
                        @csrf
                        <div class="mb-4">
                            <label class="block mb-2">Result:</label>
                            <div class="flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="passed" value="1" class="form-radio" checked>
                                    <span class="ml-2">Passed</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="passed" value="0" class="form-radio">
                                    <span class="ml-2">Failed</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="hideCompleteModal()" class="btn btn-secondary">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Confirm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Demo Sessions</h1>
                        <p class="text-gray-500 mt-1">Manage all demo sessions and evaluations</p>
                    </div>
                </div>
<!-- Enhanced Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
    <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Total Demos</h3>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $demoStats['total'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Pending</h3>
                <p class="text-2xl font-bold text-orange-600 mt-1">{{ $demoStats['pending'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Completed</h3>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $demoStats['completed'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Total Passed</h3>
                <p class="text-2xl font-bold text-purple-600 mt-1">{{ $demoStats['passed'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Failed</h3>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ $demoStats['failed'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Scheduled Demos Table -->
<div class="bg-white shadow-lg border border-gray-200 rounded-xl overflow-hidden mb-8">
    <div class="p-6 bg-gray-50 border-b">
        <h2 class="text-xl font-bold text-gray-700">Scheduled Demos</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Candidate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($scheduledDemos as $application)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $application->firstname ?? 'N/A' }} {{ $application->lastname ?? '' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $application->job->title ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            Scheduled
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <button onclick="showCompleteModal('{{ route('staff.recruitment.completeDemo', $application->id) }}')" 
                                class="btn btn-sm btn-primary">
                            Mark Complete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        No scheduled demos found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($scheduledDemos->count() > 0)
    <div class="px-6 py-4 border-t bg-gray-50">
        {{ $scheduledDemos->links() }}
    </div>
    @endif
</div>

<!-- Unscheduled Demos Table -->
<div class="bg-white shadow-lg border border-gray-200 rounded-xl overflow-hidden">
    <div class="p-6 bg-gray-50 border-b">
        <h2 class="text-xl font-bold text-gray-700">Unscheduled Demos</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Candidate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applied On</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($unscheduledDemos as $application)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $application->firstname ?? 'N/A' }} {{ $application->lastname ?? '' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $application->job->title ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $application->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <a href="{{ route('staff.recruitment.scheduleDemo', $application->id) }}" 
                           class="btn btn-sm btn-primary">
                            Schedule Demo
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        No unscheduled demos found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($unscheduledDemos->count() > 0)
    <div class="px-6 py-4 border-t bg-gray-50">
        {{ $unscheduledDemos->links() }}
    </div>
    @endif
</div>
        </main>
    </div>
</div>
<script>
    function showCompleteModal(actionUrl) {
        document.getElementById('completeDemoForm').action = actionUrl;
        document.getElementById('completeDemoModal').classList.remove('hidden');
    }

    function hideCompleteModal() {
        document.getElementById('completeDemoModal').classList.add('hidden');
    }

    // Update the passed value when radio buttons change
    document.querySelectorAll('#completeDemoForm input[name="passed"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelector('#completeDemoForm input[name="passed"]').value = this.value;
        });
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('completeDemoModal');
        if (event.target === modal) {
            hideCompleteModal();
        }
    });

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

</script>
@endsection