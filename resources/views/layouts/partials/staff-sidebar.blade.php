<div class="fixed left-0 top-16 h-[calc(100vh-4rem)] w-64 bg-white border-r border-gray-100 shadow-sm overflow-y-auto z-20 transition-all duration-200 transform -translate-x-full lg:translate-x-0">
    <div class="flex flex-col p-4 space-y-1 h-full">
        
        <!-- Dashboard -->
        <div class="mb-2">
            <a href="{{ route('staff.recruitment.dashboard') }}"
               class="flex items-center p-3 w-full rounded-lg transition-all duration-200 
               {{ request()->routeIs('staff.recruitment.dashboard') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
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
                        ['route' => 'staff.recruitment.applicants',        'icon' => 'document',     'label' => 'Applicants',         'badge' => 'applicants',        'badgeColor' => 'blue'],
                        ['route' => 'staff.recruitment.initial_interviews','icon' => 'comment-alt',  'label' => 'Initial Interviews', 'badge' => 'initial_interviews','badgeColor' => 'purple'],
                        ['route' => 'staff.recruitment.demos',             'icon' => 'presentation', 'label' => 'Practical Demos',    'badge' => 'demos',             'badgeColor' => 'yellow'],
                        ['route' => 'staff.recruitment.exams',             'icon' => 'clipboard',    'label' => 'Written Exams',      'badge' => 'exams',             'badgeColor' => 'green'],
                        ['route' => 'staff.recruitment.final_interviews',  'icon' => 'users-alt',    'label' => 'Final Interviews',   'badge' => 'final_interviews',  'badgeColor' => 'indigo'],
                        ['route' => 'staff.recruitment.pre_employment',    'icon' => 'file-contract','label' => 'Pre-Employment',     'badge' => 'pre_employment',    'badgeColor' => 'orange'],
                        ['route' => 'staff.recruitment.onboarding',        'icon' => 'user-add',     'label' => 'Onboarding',         'badge' => 'onboarding',        'badgeColor' => 'teal'],
                    ];
                @endphp

                @foreach ($items as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center p-3 w-full rounded-lg transition-all duration-200
                       {{ request()->routeIs($item['route']) ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fi fi-sr-{{ $item['icon'] }} text-lg"></i>
                        <span class="ml-3 text-md">{{ $item['label'] }}</span>
                        <span class="ml-auto text-xs px-2 py-0.5 rounded-full 
                            bg-{{ $item['badgeColor'] }}-100 text-{{ $item['badgeColor'] }}-800">
                            {{ $stats[$item['badge']] ?? 0 }}
                        </span>
                    </a>
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

    </div>
</div>
