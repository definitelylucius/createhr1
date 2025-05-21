<header class="fixed top-0 left-0 right-0 h-16 bg-white shadow-sm z-30 border-b border-gray-100">
        <div class="flex items-center justify-between h-full px-6">
            <!-- Left Section -->
            <div class="flex items-center space-x-4">
                <button class="sidebar-toggle text-gray-600 lg:hidden focus:outline-none">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <h4 class="text-lg font-semibold text-gray-800 hidden md:block">Admin</h4>
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