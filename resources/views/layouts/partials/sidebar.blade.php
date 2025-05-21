<aside class="w-64 bg-indigo-800 text-white shadow-lg">
    <div class="p-4">
        <h1 class="text-2xl font-bold">{{ config('app.name') }}</h1>
    </div>
    <nav class="mt-6">
        @if(auth()->user()->hasRole('admin'))
            <!-- Admin Menu -->
            <div>
                <p class="px-4 py-2 text-indigo-200 uppercase text-xs font-semibold">Admin</p>
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-indigo-700 {{ request()->is('admin/dashboard') ? 'bg-indigo-900' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.candidates.index') }}" class="block px-4 py-2 hover:bg-indigo-700 {{ request()->is('admin/candidates*') ? 'bg-indigo-900' : '' }}">
                    <i class="fas fa-users mr-2"></i> Candidates
                </a>
                <a href="#" class="block px-4 py-2 hover:bg-indigo-700">
                    <i class="fas fa-calendar-alt mr-2"></i> Calendar
                </a>
                <a href="#" class="block px-4 py-2 hover:bg-indigo-700">
                    <i class="fas fa-file-signature mr-2"></i> Documents
                </a>
            </div>
        @endif

        @if(auth()->user()->hasRole('staff'))
            <!-- Staff Menu -->
            <div>
                <p class="px-4 py-2 text-indigo-200 uppercase text-xs font-semibold">Staff</p>
                <a href="{{ route('staff.dashboard') }}" class="block px-4 py-2 hover:bg-indigo-700 {{ request()->is('staff/dashboard') ? 'bg-indigo-900' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="{{ route('staff.candidates.index') }}" class="block px-4 py-2 hover:bg-indigo-700 {{ request()->is('staff/candidates*') ? 'bg-indigo-900' : '' }}">
                    <i class="fas fa-users mr-2"></i> My Candidates
                </a>
                <a href="#" class="block px-4 py-2 hover:bg-indigo-700">
                    <i class="fas fa-tasks mr-2"></i> Onboarding Tasks
                </a>
            </div>
        @endif

        @if(auth()->user()->hasRole('employee'))
            <!-- Employee Menu -->
            <div>
                <p class="px-4 py-2 text-indigo-200 uppercase text-xs font-semibold">Employee</p>
                <a href="{{ route('employee.dashboard') }}" class="block px-4 py-2 hover:bg-indigo-700 {{ request()->is('employee/dashboard') ? 'bg-indigo-900' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="{{ route('employee.onboarding') }}" class="block px-4 py-2 hover:bg-indigo-700 {{ request()->is('employee/onboarding') ? 'bg-indigo-900' : '' }}">
                    <i class="fas fa-clipboard-list mr-2"></i> Onboarding
                </a>
            </div>
        @endif

        <!-- Common Menu Items -->
        <div class="mt-6">
            <p class="px-4 py-2 text-indigo-200 uppercase text-xs font-semibold">Account</p>
            <a href="#" class="block px-4 py-2 hover:bg-indigo-700">
                <i class="fas fa-user mr-2"></i> Profile
            </a>
            <a href="#" class="block px-4 py-2 hover:bg-indigo-700">
                <i class="fas fa-cog mr-2"></i> Settings
            </a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 hover:bg-indigo-700">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </nav>
</aside>