<aside class="fixed left-0 top-16 h-[calc(100vh-4rem)] w-64 bg-white border-r border-gray-200 shadow-lg overflow-y-auto z-40 transition-all duration-200">
    <div class="flex flex-col p-4 space-y-2 h-full">
        <!-- Home -->
        <div class="relative">
            <a href="/admin/dashboard" class="flex items-center p-2 w-full rounded-lg transition-colors duration-200 {{ request()->is('admin/dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fi fi-sr-home text-lg"></i>
                <span class="text-md ml-3">Home</span>
            </a>
        </div>

        <!-- Candidate Review -->
        <div class="relative">
            <button class="flex justify-between items-center p-2 w-full rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.candidates.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}" onclick="toggleDropdown('candidateReviewDropdown')">
                <span class="flex items-center gap-2">
                    <i class="fi fi-sr-user-check text-lg"></i>
                    <span class="text-md">Candidate Review</span>
                </span>
                <i class="fi fi-sr-angle-small-down text-sm transition-transform duration-200" id="candidateReviewDropdownIcon"></i>
            </button>
            <div id="candidateReviewDropdown" class="hidden flex-col ml-8 mt-1 space-y-1 transition-all duration-200">
                <a href="{{ route('admin.candidates.index') }}" class="flex items-center p-2 rounded-lg text-sm {{ request()->routeIs('admin.candidates.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                    Review Candidates
                </a>
                <a href="{{ route('admin.candidates.approvalQueue') }}" class="flex items-center p-2 rounded-lg text-sm {{ request()->routeIs('admin.candidates.approvalQueue') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                    Approval Queue
                </a>
            </div>
        </div>

        <!-- Job Requisitions -->
        <div class="relative">
            <button class="flex justify-between items-center p-2 w-full rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.jobs.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}" onclick="toggleDropdown('jobRequisitionDropdown')">
                <span class="flex items-center gap-2">
                    <i class="fi fi-sr-briefcase text-lg"></i>
                    <span class="text-md">Job Requisitions</span>
                </span>
                <i class="fi fi-sr-angle-small-down text-sm transition-transform duration-200" id="jobRequisitionDropdownIcon"></i>
            </button>
            <div id="jobRequisitionDropdown" class="hidden flex-col ml-8 mt-1 space-y-1 transition-all duration-200">
                <a href="{{ route('admin.jobs.create') }}" class="flex items-center p-2 rounded-lg text-sm {{ request()->routeIs('admin.jobs.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                    Create New Requisition
                </a>
                <a href="{{ route('admin.jobs.manage') }}" class="flex items-center p-2 rounded-lg text-sm {{ request()->routeIs('admin.jobs.manage') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                    View All Requisitions
                </a>
            </div>
        </div>

       

        <!-- Hiring Decisions -->
        <div class="relative">
            <button class="flex justify-between items-center p-2 w-full rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.hiring-decisions.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}" onclick="toggleDropdown('hiringDecisionsDropdown')">
                <span class="flex items-center gap-2">
                    <i class="fi fi-sr-user-check text-lg"></i>
                    <span class="text-md">Hiring Decisions</span>
                </span>
                <i class="fi fi-sr-angle-small-down text-sm transition-transform duration-200" id="hiringDecisionsDropdownIcon"></i>
            </button>

            <div id="hiringDecisionsDropdown" class="hidden flex-col ml-8 mt-1 space-y-1 transition-all duration-200">
            <a href="{{ route('admin.hiring-decisions.ready') }}" class="flex items-center p-2 w-full rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.hiring-decisions.ready') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fi fi-sr-user-check text-lg"></i>
                <span class="text-md ml-3">Ready for Hire</span>
                @php
                    $readyCount = \App\Models\Candidate::where('status', 'final_interview_completed')
                        ->whereDoesntHave('hiringDecision')
                        ->count();
                @endphp
                @if($readyCount > 0)
                    <span class="ml-auto bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                        {{ $readyCount }}
                    </span>
                @endif
            </a>
                <a href="{{ route('admin.hiring-decisions.index') }}" class="flex items-center p-2 rounded-lg text-sm {{ request()->routeIs('admin.hiring-decisions.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                    All Decisions
                </a>
                @can('make_hiring_decision')
                <a href="{{ route('admin.hiring-decisions.create', ['candidate' => 0]) }}" class="flex items-center p-2 rounded-lg text-sm {{ request()->routeIs('admin.hiring-decisions.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                    Create Decision
                </a>
                @endcan
            </div>
        </div>
    </div>
</aside>

<script>
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
        const dropdowns = ['candidateReviewDropdown', 'jobRequisitionDropdown', 'hiringDecisionsDropdown'];
        
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
</script>