<div class="sidebar-header p-4 border-b border-gray-700">
    <h4 class="text-xl font-bold text-white">{{ config('app.name') }}</h4>
    <p class="text-xs text-gray-400">Employee Portal</p>
</div>

<ul class="sidebar-menu py-2">
    <li class="sidebar-item">
        <a href="{{ route('employee.dashboard') }}" class="sidebar-link px-4 py-3 flex items-center text-gray-300 hover:bg-gray-700 hover:text-white">
            <i class="fas fa-tachometer-alt mr-3"></i>
            Dashboard
        </a>
    </li>
    
    <li class="sidebar-item">
        <a href="{{ route('employee.profile') }}" class="sidebar-link px-4 py-3 flex items-center text-gray-300 hover:bg-gray-700 hover:text-white">
            <i class="fas fa-user mr-3"></i>
            My Profile
        </a>
    </li>
    
    <li class="sidebar-item has-submenu">
        <a href="#" class="sidebar-link px-4 py-3 flex items-center justify-between text-gray-300 hover:bg-gray-700 hover:text-white">
            <span class="flex items-center">
                <i class="fas fa-file-contract mr-3"></i>
                My Documents
            </span>
            <i class="fas fa-chevron-down text-xs"></i>
        </a>
        <ul class="sidebar-submenu pl-4 bg-gray-800 hidden">
            <li>
                <a href="{{ route('employee.documents.offer-letter') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                    Offer Letter
                </a>
            </li>
            <li>
                <a href="{{ route('employee.documents.contract') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                    Employment Contract
                </a>
            </li>
            <li>
                <a href="{{ route('employee.documents.tax-forms') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                    Tax Forms
                </a>
            </li>
        </ul>
    </li>
    
    <li class="sidebar-item">
        <a href="{{ route('employee.schedule') }}" class="sidebar-link px-4 py-3 flex items-center text-gray-300 hover:bg-gray-700 hover:text-white">
            <i class="fas fa-calendar-alt mr-3"></i>
            My Schedule
        </a>
    </li>
    
    <li class="sidebar-item">
        <a href="{{ route('employee.payroll') }}" class="sidebar-link px-4 py-3 flex items-center text-gray-300 hover:bg-gray-700 hover:text-white">
            <i class="fas fa-money-bill-wave mr-3"></i>
            Payroll Information
        </a>
    </li>
    
    <li class="sidebar-item">
        <a href="{{ route('employee.training') }}" class="sidebar-link px-4 py-3 flex items-center text-gray-300 hover:bg-gray-700 hover:text-white">
            <i class="fas fa-graduation-cap mr-3"></i>
            Training Materials
        </a>
    </li>
    
    <li class="sidebar-item">
        <a href="{{ route('employee.policies') }}" class="sidebar-link px-4 py-3 flex items-center text-gray-300 hover:bg-gray-700 hover:text-white">
            <i class="fas fa-book mr-3"></i>
            Company Policies
        </a>
    </li>
    
    <li class="sidebar-item">
        <a href="{{ route('employee.requests') }}" class="sidebar-link px-4 py-3 flex items-center text-gray-300 hover:bg-gray-700 hover:text-white">
            <i class="fas fa-paper-plane mr-3"></i>
            Requests
        </a>
    </li>
</ul>

<div class="sidebar-footer absolute bottom-0 w-full p-4 border-t border-gray-700">
    <p class="text-xs text-gray-400 text-center">v{{ config('app.version') }}</p>
</div>