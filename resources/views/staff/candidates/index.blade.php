<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Management | Staff Dashboard</title>
    
    <!-- Tailwind & DaisyUI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #7c3aed;
            --accent: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
        }
        
        .filter-chip {
            @apply px-5 py-3 rounded-xl text-sm font-medium transition-all duration-200 border-2;
        }
        .filter-chip-active {
            @apply bg-gradient-to-r from-primary to-secondary text-white border-transparent shadow-lg;
        }
        .hover-scale {
            @apply transition-transform duration-200 hover:scale-[1.02] hover:shadow-md;
        }
        .card-shadow {
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.1);
        }
        .status-badge {
            @apply badge font-medium gap-2 px-4 py-3 rounded-xl capitalize;
        }
        .table-row-hover {
            @apply hover:bg-primary/5 transition-colors duration-200;
        }
        .avatar-initials {
            @apply font-bold text-primary;
        }
        .table-cell {
            @apply py-6 align-middle;
        }
        .progress-track {
            @apply w-full bg-gray-200 rounded-full h-2.5;
        }
        .progress-fill {
            @apply bg-gradient-to-r from-primary to-secondary h-2.5 rounded-full;
        }
    </style>
</head>

<body class="font-[Poppins] bg-gray-50/50">

    <!-- Navbar -->
    @include('staffcomponent.nav-bar')

    <div class="flex">
        <!-- Sidebar -->
        @include('staffcomponent.side-bar')

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Header Area -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Candidate Management</h1>
                    <p class="text-gray-500 mt-2 text-lg font-medium">{{ $candidates->total() }} candidates found</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                    <div class="relative flex-1">
                        <input type="text" placeholder="Search candidates..." 
                               class="input input-bordered pl-12 pr-5 py-3 w-full focus:ring-2 focus:ring-primary focus:border-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-4 top-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <a href="{{ route('staff.tags.index') }}" class="btn btn-primary hover-scale gap-2 whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Manage Tags
                    </a>
                </div>
            </div>
            
            <!-- Filter Chips -->
            <div class="flex flex-wrap gap-3 mb-8">
                <a href="{{ route('staff.candidates.index') }}" 
                   class="filter-chip {{ !request('status') ? 'filter-chip-active' : 'bg-white text-gray-600 hover:bg-gray-100 border-gray-300' }} hover-scale">
                   All
                </a>
                @foreach([
                    'new' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'under_review' => 'bg-purple-100 text-purple-800 border-purple-200',
                    'license_verified' => 'bg-green-100 text-green-800 border-green-200',
                    'test_scheduled' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    'test_completed' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'pending_approval' => 'bg-orange-100 text-orange-800 border-orange-200'
                ] as $status => $colors)
                    <a href="{{ route('staff.candidates.index', ['status' => $status]) }}" 
                       class="filter-chip {{ request('status') === $status ? 'filter-chip-active' : $colors }} hover-scale">
                       {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </a>
                @endforeach
            </div>
            
            <!-- Candidates Table -->
            <div class="card bg-white card-shadow rounded-2xl overflow-hidden border border-gray-200/50">
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <!-- Table Header -->
                        <thead>
                            <tr class="bg-gradient-to-r from-primary/5 to-secondary/5">
                                <th class="font-semibold text-gray-700 pl-10 py-5 text-left">Candidate</th>
                                <th class="font-semibold text-gray-700 py-5 text-left">Status</th>
                                <th class="font-semibold text-gray-700 py-5 text-left">Tags</th>
                                <th class="font-semibold text-gray-700 py-5 text-left">License</th>
                                <th class="font-semibold text-gray-700 py-5 text-left">Tests</th>
                                <th class="font-semibold text-gray-700 pr-10 py-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($candidates as $candidate)
                            <tr class="table-row-hover border-b border-gray-200/50">
                                <!-- Candidate Column -->
                                <td class="pl-10 table-cell">
                                    <div class="flex items-center gap-4">
                                        <div class="avatar placeholder">
                                            <div class="bg-gradient-to-br from-primary/10 to-secondary/10 rounded-xl w-12 h-12 flex items-center justify-center">
                                                <span class="avatar-initials text-lg">{{ strtoupper(substr($candidate->first_name, 0, 1)) }}{{ strtoupper(substr($candidate->last_name, 0, 1)) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800 text-[15px]">
                                                <a href="{{ route('staff.candidates.show', $candidate) }}" class="hover:text-primary transition-colors">
                                                    {{ $candidate->full_name }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                {{ $candidate->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Status Column -->
                                <td class="table-cell">
                                    @php
                                        $statusConfig = [
                                            'new' => ['bg-blue-100 text-blue-800', 'M12 6v6m0 0v6m0-6h6m-6 0H6'],
                                            'under_review' => ['bg-purple-100 text-purple-800', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
                                            'license_verified' => ['bg-green-100 text-green-800', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                                            'test_scheduled' => ['bg-yellow-100 text-yellow-800', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                                            'test_completed' => ['bg-blue-100 text-blue-800', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'pending_approval' => ['bg-orange-100 text-orange-800', 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1'],
                                            'approved' => ['bg-green-100 text-green-800', 'M5 13l4 4L19 7'],
                                            'rejected' => ['bg-red-100 text-red-800', 'M6 18L18 6M6 6l12 12']
                                        ];
                                        $statusClass = $statusConfig[$candidate->status][0] ?? 'bg-gray-100 text-gray-800';
                                        $statusIcon = $statusConfig[$candidate->status][1] ?? 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z';
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <span class="status-badge {{ $statusClass }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusIcon }}" />
                                            </svg>
                                            {{ ucfirst(str_replace('_', ' ', $candidate->status)) }}
                                        </span>
                                    </div>
                                </td>
                                
                                <!-- Tags Column -->
                                <td class="table-cell">
                                    <div class="flex flex-wrap gap-2">
                                        @forelse($candidate->tags as $tag)
                                            <span class="badge py-1.5 px-3 rounded-lg text-xs font-medium border-2" style="border-color: {{ $tag->color }}; color: {{ $tag->color }}; background-color: {{ $tag->color }}20">
                                                {{ $tag->name }}
                                            </span>
                                        @empty
                                            <span class="text-sm text-gray-400">None</span>
                                        @endforelse
                                    </div>
                                </td>
                                
                                <!-- License Column -->
                                <td class="table-cell">
                                    @if($candidate->licenseVerification && $candidate->licenseVerification->is_verified)
                                        <div class="flex items-center gap-2 text-success font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Verified
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2 text-warning font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            Not Verified
                                        </div>
                                    @endif
                                </td>
                                
                                <!-- Tests Column -->
                                <td class="table-cell">
                                    @if($candidate->tests()->exists())
                                        <div class="flex items-center gap-3">
                                            <div class="w-full max-w-[120px]">
                                                <div class="progress-track">
                                                    <div class="progress-fill" style="width: {{ ($candidate->tests->where('is_passed', true)->count() / max(1, $candidate->tests->count())) * 100 }}%"></div>
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1 text-center">
                                                    {{ $candidate->tests->where('is_passed', true)->count() }}/{{ $candidate->tests->count() }} passed
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">No tests</span>
                                    @endif
                                </td>
                                
                                <!-- Actions Column -->
                                <td class="pr-10 table-cell">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('staff.candidates.show', $candidate) }}" class="btn btn-sm btn-outline btn-primary hover-scale gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center p-12 text-gray-400">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-lg">No candidates found matching your criteria</p>
                                        <a href="{{ route('staff.candidates.index') }}" class="btn btn-ghost mt-2 text-primary">Clear filters</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($candidates->hasPages())
                <div class="p-4 border-t border-gray-200/50">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            Showing {{ $candidates->firstItem() }} to {{ $candidates->lastItem() }} of {{ $candidates->total() }} candidates
                        </div>
                        <div class="join">
                            {{ $candidates->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </main>
    </div>

    <script>
        // Enhanced chip active state management
        document.addEventListener('DOMContentLoaded', function() {
            const chips = document.querySelectorAll('.filter-chip');
            
            chips.forEach(chip => {
                chip.addEventListener('click', function() {
                    chips.forEach(c => c.classList.remove('filter-chip-active', 'bg-primary', 'text-white', 'border-transparent'));
                    this.classList.add('filter-chip-active', 'bg-primary', 'text-white', 'border-transparent');
                });
            });
        });
    </script>
</body>
</html>