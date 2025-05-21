<!-- Sidebar for Admin -->
<aside class="fixed left-0 top-16 h-[calc(100vh-4rem)] w-64 bg-white border-r border-gray-200 shadow-lg overflow-y-auto z-40 transition-all duration-200">
    <div class="p-6 font-bold text-xl text-center border-b border-gray-200">
        Admin Panel
    </div>

    <ul class="flex flex-col p-4 space-y-2 h-full">
        <!-- Home -->
        <li>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 w-full rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fi fi-sr-home text-lg"></i>
                <span class="text-md ml-3">Home</span>
            </a>
        </li>

        <!-- Job Requisitions -->
        <li>
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
        </li>
    </ul>
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
        const dropdowns = ['jobRequisitionDropdown'];
        
        dropdowns.forEach(dropdownId => {
            const dropdown = document.getElementById(dropdownId);
            if (dropdown) {
                const links = dropdown.querySelectorAll('a');
                
                links.forEach(link => {
                    if (link.classList.contains('bg-blue-50')) {
                        dropdown.classList.remove('hidden');
                        const icon = document.getElementById(`${dropdownId}Icon`);
                        if (icon) icon.classList.add('rotate-180');
                    }
                });
            }
        });
    });
</script>

