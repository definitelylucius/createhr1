@php
    use Illuminate\Support\Facades\Request;
@endphp

<aside class="w-64 bg-white h-screen p-6 shadow-lg border-r border-gray-200">
    <h2 class="text-gray-800 text-lg font-bold">Navigation</h2>
    <ul class="mt-4 space-y-2">
        <!-- Dashboard -->
        <li>
            <a href="{{ route('employee.dashboard') }}" 
               class="flex items-center space-x-2 p-2 rounded-md transition-all 
                      {{ Request::is('employee/dashboard') ? 'bg-[#00446b] text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Onboarding -->
        <li>
            <a href="{{ route('employee.onboarding.orientation') }}" 
               class="flex items-center space-x-2 p-2 rounded-md transition-all 
                      {{ Request::is('employee/onboarding*') ? 'bg-[#00446b] text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <span>Onboarding</span>
            </a>
        </li>

        <!-- Profile Management -->
        <li>
            <a href="{{ route('employee.profile') }}" 
               class="flex items-center space-x-2 p-2 rounded-md transition-all 
                      {{ Request::is('employee/profile') ? 'bg-[#00446b] text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <span>Profile Management</span>
            </a>
        </li>
        <li>
        <a href="{{ route('employee.onboarding') }}" 
   class="flex items-center space-x-2 p-2 rounded-md transition-all 
          {{ Request::is('employee/onboarding') ? 'bg-[#00446b] text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
    <span>Onboarding</span>
</a>
        </li>

      
</aside>

<script>
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        dropdown.classList.toggle('hidden');
    }
</script>
