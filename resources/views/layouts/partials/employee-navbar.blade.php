<div class="flex items-center justify-between h-full px-4">
    <div class="flex items-center">
        <button class="sidebar-toggle mr-4 text-gray-600 lg:hidden">
            <i class="fas fa-bars"></i>
        </button>
        <h4 class="text-lg font-semibold text-gray-800">@yield('title')</h4>
    </div>
    
    <div class="flex items-center">
        <div class="relative mr-4">
            <button class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-bell"></i>
                <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
            </button>
        </div>
        
        <div class="relative">
            <div class="dropdown">
                <button class="flex items-center text-gray-600 hover:text-gray-900 focus:outline-none">
                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-2">
                        <span class="text-green-600 font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down ml-1 text-xs"></i>
                </button>
                
                <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                    <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2"></i> Profile
                    </a>
                    <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog mr-2"></i> Settings
                    </a>
                    <div class="border-t border-gray-200"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle dropdown menu
        const dropdownBtn = document.querySelector('.dropdown button');
        const dropdownMenu = document.querySelector('.dropdown-menu');
        
        if (dropdownBtn && dropdownMenu) {
            dropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                dropdownMenu.classList.add('hidden');
            });
        }
    });
</script>
@endpush