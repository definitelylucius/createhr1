@extends('layouts.admin')

@section('content')
@include('layouts.partials.admin-navbar')
@include('layouts.partials.admin-sidebar')
 <!-- Main Content Area -->
 <div class="flex-1 overflow-y-auto lg:ml-64 transition-all duration-200 bg-gray-50">
 
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Onboarding Candidates</h2>
        <div class="bg-teal-100 text-teal-800 px-4 py-2 rounded-full">
            Total: {{ $applications->total() }}
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($applications as $application)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-teal-100 rounded-full flex items-center justify-center">
                                    <span class="text-teal-600 font-medium">{{ substr($application->firstname, 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $application->firstname }} {{ $application->lastname }}</div>
                                    <div class="text-sm text-gray-500">{{ $application->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $application->job->title ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $application->job->department ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($application->onboarding && $application->onboarding->start_date)
                                {{ $application->onboarding->start_date->format('M d, Y') }}
                            @else
                                <span class="text-yellow-600">Not set</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($application->onboarding)
                                @php
                                    $progress = $application->onboarding->calculateProgress();
                                    $color = $progress < 30 ? 'bg-red-500' : ($progress < 70 ? 'bg-yellow-500' : 'bg-green-500');
                                @endphp
                                <div class="flex items-center">
                                    <div class="w-32 bg-gray-200 rounded-full h-2.5 mr-2">
                                        <div class="h-2.5 rounded-full {{ $color }}" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium">{{ $progress }}%</span>
                                </div>
                            @else
                                <span class="text-sm text-gray-400">Not started</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full bg-teal-100 text-teal-800">
                                {{ str_replace('_', ' ', $application->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('applications.onboarding.show', $application->id) }}" class="text-teal-600 hover:text-teal-900 mr-3">Details</a>
                            <a href="#" class="text-blue-600 hover:text-blue-900">Update</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No candidates in onboarding stage</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            {{ $applications->links() }}
        </div>
    </div>
</div>
<script>
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
@endsection