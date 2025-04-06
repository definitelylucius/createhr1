<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>Schedule Final Interview</title>
</head>
<body class="bg-gray-50 font-[Poppins]">
    @include('staffcomponent.nav-bar')

    <div class="flex min-h-screen">
        @include('staffcomponent.side-bar')
        
        <div class="flex-1 p-6 overflow-y-auto">
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Schedule Final Interview</h1>
                <p class="text-gray-600">Set up a final interview for {{ $candidate->full_name }}</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('staff.final-interviews.store', $candidate) }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Candidate Information</h3>
                            <div class="space-y-2">
                                <p class="text-gray-600"><span class="font-medium">Name:</span> {{ $candidate->full_name }}</p>
                                <p class="text-gray-600"><span class="font-medium">Email:</span> {{ $candidate->email }}</p>
                                <p class="text-gray-600"><span class="font-medium">Position:</span> {{ $candidate->job->title ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Interview Details</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-1">Interview Date & Time</label>
                                    <input type="datetime-local" id="scheduled_at" name="scheduled_at" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           min="{{ now()->addDay()->format('Y-m-d\TH:i') }}"
                                           required>
                                    @error('scheduled_at')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                                    <textarea id="notes" name="notes" rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    @error('notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                    <a href="{{ route('staff.final-interviews.select-candidate') }}
                           class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Schedule Interview
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>