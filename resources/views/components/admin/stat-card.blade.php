@props(['title', 'icon', 'count', 'color', 'route'])

<div class="bg-white rounded-xl shadow-sm border-l-4 border-{{ $color }}-500 overflow-hidden">
    <div class="p-5">
        <div class="flex items-center justify-between">
            <h3 class="text-gray-500 font-medium">{{ $title }}</h3>
            <div class="bg-{{ $color }}-100 p-2 rounded-lg">
                <svg class="w-5 h-5 text-{{ $color }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <use href="#icon-{{ $icon }}" />
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-3xl font-bold text-gray-800">{{ $count }}</p>
        </div>
        <a href="{{ route($route) }}" class="mt-4 inline-block text-{{ $color }}-500 text-sm font-medium hover:text-{{ $color }}-700">View all →</a>
    </div>
</div>
