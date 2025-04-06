<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .detail-card {
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }
        .detail-card:hover {
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
        }
        body {
            background-color: #f9fafb;
        }
        .form-group {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="font-[Poppins]">
    @include('staffcomponent.nav-bar')

    <div class="flex min-h-screen bg-gray-50">
        @include('staffcomponent.side-bar')
        
        <div class="flex-1 p-4 md:p-6 overflow-y-auto">
            <!-- Header Section -->
            <div class="bg-white p-4 md:p-6 rounded-lg shadow-sm mb-6 border border-gray-100">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-gray-800">Interview Details</h1>
                        <p class="text-gray-500 text-sm md:text-base">Viewing interview for {{ $interview->candidate->full_name }}</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <a href="{{ route('staff.final-interviews.index') }}" 
                           class="px-4 py-2 border border-gray-200 rounded-md text-gray-700 hover:bg-gray-50 text-center text-sm md:text-base transition-colors">
                            ← Back to List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
                <!-- Candidate Information Card -->
                <div class="detail-card bg-white p-4 md:p-5 rounded-lg">
                    <h2 class="text-lg font-semibold mb-3 pb-2 border-b border-gray-100 text-gray-700">Candidate Information</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Full Name</p>
                            <p class="font-medium text-gray-800">{{ $interview->candidate->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Email</p>
                            <a href="mailto:{{ $interview->candidate->email }}" class="font-medium text-blue-600 hover:underline block text-sm md:text-base">
                                {{ $interview->candidate->email }}
                            </a>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Position</p>
                            <p class="font-medium text-gray-800">{{ $interview->candidate->job->title ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Current Status</p>
                            <span class="status-badge inline-block rounded-full font-medium
                                @if($interview->candidate->status === 'final_interview_scheduled') bg-blue-50 text-blue-700 border border-blue-100
                                @elseif($interview->candidate->status === 'final_interview_completed') bg-green-50 text-green-700 border border-green-100
                                @else bg-gray-50 text-gray-700 border border-gray-100 @endif">
                                {{ str_replace('_', ' ', $interview->candidate->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Interview Details Card -->
                <div class="detail-card bg-white p-4 md:p-5 rounded-lg">
                    <h2 class="text-lg font-semibold mb-3 pb-2 border-b border-gray-100 text-gray-700">Interview Details</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Interviewer</p>
                            <p class="font-medium text-gray-800">{{ $interview->interviewer->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Scheduled Date & Time</p>
                            <p class="font-medium text-gray-800">
                                {{ $interview->scheduled_at->format('l, F j, Y \a\t g:i A') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Status</p>
                            <span class="status-badge inline-block rounded-full font-medium
                                @if($interview->status === 'scheduled') bg-yellow-50 text-yellow-700 border border-yellow-100
                                @elseif($interview->status === 'completed') bg-green-50 text-green-700 border border-green-100
                                @endif">
                                {{ ucfirst($interview->status) }}
                            </span>
                        </div>
                        @if($interview->notes)
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Notes</p>
                            <div class="mt-1 p-3 bg-gray-50 rounded-md text-sm whitespace-pre-line text-gray-700 border border-gray-100">
                                {{ $interview->notes }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Results Section -->
                @if($interview->status === 'scheduled')
                <!-- Interview Completion Form -->
                <div class="detail-card bg-white p-4 md:p-5 rounded-lg lg:col-span-3">
                    <h2 class="text-lg font-semibold mb-3 pb-2 border-b border-gray-100 text-gray-700">Complete Interview</h2>
                    <form method="POST" action="{{ route('staff.final-interviews.complete', $interview) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Result</label>
                            <select name="result" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-2">
                                <option value="">Select Result</option>
                                <option value="recommended">Recommended</option>
                                <option value="not_recommended">Not Recommended</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Feedback</label>
                            <textarea name="feedback" required rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-2"></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Submit Results
                        </button>
                    </form>
                </div>
                @elseif($interview->status === 'completed')
                <!-- Interview Results Card -->
                <div class="detail-card bg-white p-4 md:p-5 rounded-lg lg:col-span-3">
                    <h2 class="text-lg font-semibold mb-3 pb-2 border-b border-gray-100 text-gray-700">Interview Results</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Result</p>
                            <span class="status-badge inline-block rounded-full font-medium
                                @if($interview->result === 'recommended') bg-green-50 text-green-700 border border-green-100
                                @else bg-red-50 text-red-700 border border-red-100 @endif">
                                {{ str_replace('_', ' ', $interview->result) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Completion Date</p>
                            <p class="font-medium text-gray-800">
                                {{ $interview->updated_at->format('l, F j, Y \a\t g:i A') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Duration</p>
                            <p class="font-medium text-gray-800">
                                @if($interview->scheduled_at && $interview->updated_at)
                                    {{ $interview->scheduled_at->diffForHumans($interview->updated_at, true) }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        <div class="md:col-span-3">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Feedback</p>
                            <div class="mt-1 p-3 bg-gray-50 rounded-md text-sm whitespace-pre-line text-gray-700 border border-gray-100">
                                {{ $interview->feedback }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>