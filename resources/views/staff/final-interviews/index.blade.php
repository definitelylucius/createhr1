<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Interviews</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-[Poppins]">
    @include('staffcomponent.nav-bar')

    <div class="flex min-h-screen">
        @include('staffcomponent.side-bar')
        
        <div class="flex-1 p-6 overflow-y-auto">
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Final Interviews</h1>
                        <p class="text-gray-600">List of all scheduled final interviews</p>
                    </div>
                    <a href="{{ route('staff.final-interviews.select-candidate') }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Schedule New Interview
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($interviews as $interview)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fi fi-rr-user text-blue-500"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $interview->candidate->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $interview->candidate->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $interview->candidate->job->title ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $interview->candidate->job->department ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $interview->scheduled_at->format('M j, Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $interview->scheduled_at->format('g:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($interview->status === 'scheduled') bg-yellow-100 text-yellow-800
                                    @elseif($interview->status === 'completed') bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($interview->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('staff.final-interviews.show', $interview) }}" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                    View
                                </a>
                                @if($interview->status === 'scheduled')
                                <a href="#" 
                                   class="text-gray-400 hover:text-gray-600 cursor-not-allowed" 
                                   title="Edit coming soon">
                                    Edit
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                No interviews scheduled yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($interviews->hasPages())
            <div class="mt-4">
                {{ $interviews->links() }}
            </div>
            @endif
        </div>
    </div>
</body>
</html>