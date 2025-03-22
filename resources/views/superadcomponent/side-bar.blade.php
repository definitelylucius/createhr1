<aside class="w-64 bg-white h-screen p-6 shadow-lg border-r border-gray-200">
    <h2 class="text-gray-800 text-lg font-bold">Navigation</h2>
    <ul class="mt-4 space-y-2">
        <!-- Dashboard -->
        <li>
            <a href="{{ route('superadmin.dashboard') }}" 
               class="flex items-center space-x-2 p-2 rounded-md transition-all 
                      {{ Request::is('superadmin/dashboard') ? 'bg-[#00446b] text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                🏠 <span>Dashboard</span>
            </a>
        </li>

        <!-- User & Role Management -->
        <li class="relative cursor-pointer">
            <div onclick="toggleDropdown('userManagementDropdown')" 
                 class="flex items-center justify-between p-2 rounded-md transition-all cursor-pointer 
                        {{ Request::is('superadmin/users*') ? 'text:text:#00446b' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <span class="flex items-center gap-2">
                    👥 <span>User & Role Management</span>
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </div>

            <!-- Dropdown -->
            <div id="userManagementDropdown" 
                 class="ml-4 mt-1 space-y-2 transition-all 
                        {{ Request::is('superadmin/users*') ? '' : 'hidden' }}">
                <a href="{{ route('superadmin.createUser') }}" 
                   class="flex items-center space-x-2 p-2 rounded-md transition-all 
                          {{ Request::is('superadmin/users/create') ? 'bg-[#00446b] text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    ➕ <span>Create User</span>
                </a>
            </div>
        </li>
    </ul>
</aside>

<script>
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        dropdown.classList.toggle('hidden');
    }
</script>




