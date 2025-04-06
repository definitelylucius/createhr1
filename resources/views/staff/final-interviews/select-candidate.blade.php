<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>Select Candidate</title>
</head>
<body class="bg-gray-50 font-[Poppins]">
    @include('staffcomponent.nav-bar')

    <div class="flex min-h-screen">
        @include('staffcomponent.side-bar')
        
        <div class="flex-1 p-6 overflow-y-auto">
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Select Candidate</h1>
                <p class="text-gray-600">Choose an approved candidate for final interview</p>
            </div>

            @if($candidates->isEmpty())
                <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                    <i class="fi fi-rr-user-check text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 mb-4">No approved candidates available for final interview</p>
                    <a href="{{ route('staff.candidates.index') }}" class="text-blue-500 hover:underline">
                        View all candidates
                    </a>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($candidates as $candidate)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fi fi-rr-user text-blue-500"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $candidate->full_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $candidate->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $candidate->job->title ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $candidate->job->department ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('staff.final-interviews.create', $candidate) }}" 
                                       class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                                        <i class="fi fi-rr-calendar-plus mr-1"></i> Schedule
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    @if($candidates->hasPages())
                        <div class="flex items-center justify-between">
                            {{-- Previous Page Link --}}
                            @if($candidates->onFirstPage())
                                <span class="px-4 py-2 text-gray-400 cursor-not-allowed">
                                    &larr; Previous
                                </span>
                            @else
                                <a href="{{ $candidates->previousPageUrl() }}" 
                                   class="px-4 py-2 text-blue-600 hover:text-blue-800">
                                    &larr; Previous
                                </a>
                            @endif

                            {{-- Pagination Elements --}}
                            <div class="flex space-x-2">
                                @foreach($candidates->getUrlRange(1, $candidates->lastPage()) as $page => $url)
                                    @if($page == $candidates->currentPage())
                                        <span class="px-4 py-2 bg-blue-600 text-white rounded-md">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}" 
                                           class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-md">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>

                            {{-- Next Page Link --}}
                            @if($candidates->hasMorePages())
                                <a href="{{ $candidates->nextPageUrl() }}" 
                                   class="px-4 py-2 text-blue-600 hover:text-blue-800">
                                    Next &rarr;
                                </a>
                            @else
                                <span class="px-4 py-2 text-gray-400 cursor-not-allowed">
                                    Next &rarr;
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</body>
</html>