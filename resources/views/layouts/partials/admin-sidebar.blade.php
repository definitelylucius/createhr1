
<div class="flex pt-16 h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="fixed left-0 top-16 h-[calc(100vh-4rem)] w-64 bg-white border-r border-gray-100 shadow-sm overflow-y-auto z-20 transition-all duration-200 transform -translate-x-full lg:translate-x-0">
            <div class="flex flex-col p-4 space-y-1 h-full">
                <!-- Dashboard -->
                  <!-- Dashboard -->
        <div class="mb-2">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center p-3 w-full rounded-lg transition-all duration-200 
               {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fi fi-sr-home text-lg"></i>
                <span class="ml-3 text-md">Dashboard</span>
            </a>
        </div>

  <!-- Recruitment Process Section -->
<div class="mb-2">
    <p class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Recruitment Process</p>
    <div class="space-y-1">
        @php
            $items = [
                ['route' => 'admin.recruitment.applicants',        'icon' => 'document',     'label' => 'Applicants',         'badge' => 'applicants',        'badgeColor' => 'blue'],
                ['route' => 'admin.recruitment.initial_interviews','icon' => 'comment-alt',  'label' => 'Initial Interviews', 'badge' => 'initial_interviews','badgeColor' => 'purple'],
                ['route' => 'admin.recruitment.demos',             'icon' => 'presentation', 'label' => 'Practical Demos',    'badge' => 'demos',             'badgeColor' => 'yellow'],
                ['route' => 'admin.recruitment.exams',             'icon' => 'clipboard',    'label' => 'Written Exams',      'badge' => 'exams',             'badgeColor' => 'green'],
                ['route' => 'admin.recruitment.final_interviews',  'icon' => 'users-alt',    'label' => 'Final Interviews',   'badge' => 'final_interviews',  'badgeColor' => 'indigo'],
                ['route' => 'admin.recruitment.pre_employment',    'icon' => 'file-contract','label' => 'Pre-Employment',     'badge' => 'pre_employment',    'badgeColor' => 'orange'],
                ['route' => 'admin.recruitment.onboarding',        'icon' => 'user-add',     'label' => 'Onboarding',         'badge' => 'onboarding',        'badgeColor' => 'teal'],
            ];
        @endphp

        @foreach ($items as $item)
            <div class="relative group">
                <a href="{{ route($item['route']) }}"
                   class="flex items-center p-3 w-full rounded-lg transition-all duration-200
                   {{ request()->routeIs($item['route'].'*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fi fi-sr-{{ $item['icon'] }} text-lg"></i>
                    <span class="ml-3 text-md">{{ $item['label'] }}</span>
                    <span class="ml-auto text-xs px-2 py-0.5 rounded-full 
                        bg-{{ $item['badgeColor'] }}-100 text-{{ $item['badgeColor'] }}-800">
                        {{ $stats[$item['badge']] ?? 0 }}
                    </span>
                    @if(isset($item['submenu']))
                        <i class="fi fi-sr-angle-small-down ml-2 transition-transform duration-200 group-hover:rotate-180"></i>
                    @endif
                </a>
                
                @if(isset($item['submenu']))
                    <div class="ml-4 pl-4 mt-1 hidden group-hover:block hover:block">
                        @foreach($item['submenu'] as $subitem)
                            <a href="{{ route($subitem['route']) }}"
                               class="flex items-center p-2 w-full rounded-lg transition-all duration-200 text-sm
                               {{ request()->routeIs($subitem['route']) ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                                <span class="ml-6">{{ $subitem['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
        <!-- Tools Section -->
        <div class="mb-2">
            <p class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tools</p>
            <div class="space-y-1">
                <a href="{{ route('tools.resume-parser') }}"
                   class="flex items-center p-3 w-full rounded-lg transition-all duration-200
                   {{ request()->routeIs('tools.resume-parser') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fi fi-sr-file-search text-lg"></i>
                    <span class="ml-3 text-md">Resume Parser</span>
                </a>
            </div>
        </div>


                <!-- Job Requisitions -->
                <div class="mb-2">
                    <p class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Job Management</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.jobs.create') }}" class="flex items-center p-3 w-full rounded-lg transition-all duration-200 {{ request()->routeIs('admin.jobs.create') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="fi fi-sr-file-plus text-lg"></i>
                            <span class="text-md ml-3">Create Job</span>
                        </a>
                        <a href="{{ route('admin.jobs.manage') }}" class="flex items-center p-3 w-full rounded-lg transition-all duration-200 {{ request()->routeIs('admin.jobs.manage') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="fi fi-sr-briefcase text-lg"></i>
                            <span class="text-md ml-3">Manage Jobs</span>
                        </a>
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