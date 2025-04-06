<div class="h-screen flex">
    <!-- Sidebar Container -->
    <aside class="w-64 bg-white border-r border-gray-200 shadow-lg flex flex-col">
        <!-- Sidebar Content -->
        <div class="p-4 flex-1 overflow-y-auto">
            <!-- Dashboard -->
            <div class="mb-4">
                <a href="{{ route('staff.dashboard') }}" class="flex items-center p-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('staff.dashboard') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fi fi-rr-home text-xl mr-3"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- Candidates Section -->
            <div class="mb-4">
                <div onclick="toggleDropdown('candidatesDropdown')" class="flex items-center justify-between p-3 rounded-lg cursor-pointer transition-colors duration-200 {{ request()->routeIs('staff.candidates.*') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <div class="flex items-center">
                        <i class="fi fi-rr-user-pen text-xl mr-3"></i>
                        <span>Candidates</span>
                    </div>
                    <i class="fi fi-rr-angle-small-down text-sm transition-transform duration-200" id="candidatesArrow"></i>
                </div>
                <div id="candidatesDropdown" class="ml-8 mt-1 space-y-1 {{ request()->routeIs('staff.candidates.*') ? 'block' : 'hidden' }}">
                    <a href="{{ route('staff.candidates.index') }}" class="block p-2 pl-4 rounded-lg transition-colors duration-200 {{ request()->routeIs('staff.candidates.index') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fi fi-rr-list text-lg mr-2"></i>
                        All Candidates
                    </a>
                </div>
                
            </div>

            <!-- Tags Management -->
            <div class="mb-4">
                <div onclick="toggleDropdown('tagsDropdown')" class="flex items-center justify-between p-3 rounded-lg cursor-pointer transition-colors duration-200 {{ request()->routeIs('staff.tags.*') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <div class="flex items-center">
                        <i class="fi fi-rr-tags text-xl mr-3"></i>
                        <span>Tags Management</span>
                    </div>
                    <i class="fi fi-rr-angle-small-down text-sm transition-transform duration-200" id="tagsArrow"></i>
                </div>
                <div id="tagsDropdown" class="ml-8 mt-1 space-y-1 {{ request()->routeIs('staff.tags.*') ? 'block' : 'hidden' }}">
                    <a href="{{ route('staff.tags.index') }}" class="block p-2 pl-4 rounded-lg transition-colors duration-200 {{ request()->routeIs('staff.tags.index') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fi fi-rr-list text-lg mr-2"></i>
                        All Tags
                    </a>
                    <a href="{{ route('staff.tags.create') }}" class="block p-2 pl-4 rounded-lg transition-colors duration-200 {{ request()->routeIs('staff.tags.create') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fi fi-rr-plus text-lg mr-2"></i>
                        Create New Tag
                    </a>
                </div>
            </div>

             <!-- Final Interviews Section -->
             <div class="mb-4">
                <div onclick="toggleDropdown('interviewsDropdown')" class="flex items-center justify-between p-3 rounded-lg cursor-pointer transition-colors duration-200 {{ request()->routeIs('staff.final-interviews.*') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <div class="flex items-center">
                        <i class="fi fi-rr-calendar text-xl mr-3"></i>
                        <span>Final Interviews</span>
                    </div>
                    <i class="fi fi-rr-angle-small-down text-sm transition-transform duration-200" id="interviewsArrow"></i>
                </div>
                <div id="interviewsDropdown" class="ml-8 mt-1 space-y-1 {{ request()->routeIs('staff.final-interviews.*') ? 'block' : 'hidden' }}">
                <a href="{{ route('staff.final-interviews.select-candidate') }}" class="btn btn-primary btn-sm">
    <i class="fi fi-rr-plus mr-1"></i> Schedule Interview
</a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
        @yield('content')
    </main>
</div>

<script>
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        const arrow = document.getElementById(`${dropdownId.replace('Dropdown', 'Arrow')}`);
        
        // Toggle current dropdown
        dropdown.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
        
        // Close other dropdowns
        document.querySelectorAll('.sidebar-dropdown').forEach(item => {
            if (item.id !== dropdownId && !item.classList.contains('hidden')) {
                item.classList.add('hidden');
                const otherArrow = document.getElementById(`${item.id.replace('Dropdown', 'Arrow')}`);
                if (otherArrow) otherArrow.classList.remove('rotate-180');
            }
        });
    }

    // Auto-open dropdowns for active routes
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.sidebar-dropdown').forEach(dropdown => {
            if (!dropdown.classList.contains('hidden')) {
                const arrow = document.getElementById(`${dropdown.id.replace('Dropdown', 'Arrow')}`);
                if (arrow) arrow.classList.add('rotate-180');
            }
        });
    });
</script>

<style>
    .rotate-180 {
        transform: rotate(180deg);
    }
</style>