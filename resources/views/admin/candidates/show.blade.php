<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Candidate Show | Bus Transportation</title>
  <style>
    .status-badge {
      @apply badge badge-sm font-medium gap-1;
    }
    .avatar-initials {
      @apply w-8 h-8 flex items-center justify-center rounded-full text-white font-medium text-sm;
    }
  </style>
</head>
<body class="bg-gray-100 font-[Poppins]">


<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">{{ $candidate->full_name }}</h1>
            <p class="text-gray-600">{{ $candidate->job->title }} - {{ $candidate->job->department }}</p>
        </div>
        <div>
            <span class="px-3 py-1 rounded-full text-sm font-medium 
                @if($candidate->status === 'hired') bg-green-100 text-green-800
                @elseif($candidate->status === 'rejected') bg-red-100 text-red-800
                @else bg-blue-100 text-blue-800 @endif">
                {{ ucfirst(str_replace('_', ' ', $candidate->status)) }}
            </span>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Candidate Info -->
        <div class="lg:col-span-1 bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Candidate Information</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Email</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $candidate->email }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Phone</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $candidate->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Applied On</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $candidate->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Resume</h3>
                    <div class="mt-1 flex space-x-2">
                        <a href="{{ Storage::url($candidate->resume_path) }}" target="_blank" 
                           class="text-blue-600 hover:text-blue-800 text-sm">
                            View Resume
                        </a>
                        <form action="{{ route('admin.candidates.parse-resume', $candidate) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm">
                                Parse Resume
                            </button>
                        </form>
                    </div>
                </div>
                
                @if($candidate->resume_text)
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-500">Parsed Resume Text</h3>
                    <div class="mt-1 p-3 bg-gray-50 rounded text-sm text-gray-800 max-h-40 overflow-y-auto">
                        {{ Str::limit($candidate->resume_text, 500) }}
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Hiring Process -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Process Timeline -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold mb-4">Hiring Process</h2>
                <div class="relative">
                    <!-- Timeline -->
                    <div class="flex items-center justify-between mb-6">
                        @foreach(['initial_interview', 'demo', 'exam', 'final_interview', 'pre_employment', 'hired', 'onboarding'] as $stage)
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center 
                                @if($candidate->hasStage($stage) && $candidate->getStage($stage)->result === 'pass') bg-green-500 text-white
                                @elseif($candidate->hasStage($stage) && $candidate->getStage($stage)->result === 'fail') bg-red-500 text-white
                                @elseif($candidate->status === $stage) bg-blue-500 text-white
                                @elseif(array_search($candidate->status, ['initial_interview', 'demo', 'exam', 'final_interview', 'pre_employment', 'hired', 'onboarding']) > array_search($stage, ['initial_interview', 'demo', 'exam', 'final_interview', 'pre_employment', 'hired', 'onboarding'])) bg-gray-300 text-gray-600
                                @else bg-white border-2 border-gray-300 text-gray-400 @endif">
                                {{ $loop->iteration }}
                            </div>
                            <span class="text-xs mt-1 text-center">{{ str_replace('_', ' ', ucfirst($stage)) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Current Stage Actions -->
                @if(!in_array($candidate->status, ['hired', 'rejected', 'onboarding']))
                <div class="mt-6 border-t pt-4">
                    <h3 class="font-medium mb-3">Current Stage: {{ ucfirst(str_replace('_', ' ', $candidate->status)) }}</h3>
                    
                    @if($candidate->hasStage($candidate->status) && $candidate->getStage($candidate->status)->scheduled_at)
                        <!-- Stage is scheduled -->
                        <div class="bg-blue-50 p-4 rounded-lg mb-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-medium">Scheduled for</h4>
                                    <p class="text-sm">
                                        {{ $candidate->getStage($candidate->status)->scheduled_at->format('M d, Y H:i') }}
                                        with {{ $candidate->getStage($candidate->status)->interviewer }}
                                    </p>
                                    @if($candidate->getStage($candidate->status)->calendarEvent && $candidate->getStage($candidate->status)->calendarEvent->meeting_link)
                                    <p class="text-sm mt-1">
                                        <a href="{{ $candidate->getStage($candidate->status)->calendarEvent->meeting_link }}" target="_blank" class="text-blue-600 hover:underline">
                                            Join Meeting
                                        </a>
                                    </p>
                                    @endif
                                </div>
                                <form action="{{ route('admin.hiring-process.complete-stage', [$candidate, $candidate->getStage($candidate->status)]) }}" method="POST">
                                    @csrf
                                    <div class="flex space-x-2">
                                        <button type="submit" name="result" value="pass" 
                                            class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm">
                                            Pass
                                        </button>
                                        <button type="submit" name="result" value="fail" 
                                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                                            Fail
                                        </button>
                                    </div>
                                    <textarea name="feedback" placeholder="Feedback..." 
                                        class="mt-2 w-full p-2 border rounded text-sm" rows="2"></textarea>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Schedule this stage -->
                        <form action="{{ route('admin.hiring-process.schedule-stage', $candidate) }}" method="POST">
                            @csrf
                            <input type="hidden" name="stage" value="{{ $candidate->status }}">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Date & Time</label>
                                    <input type="datetime-local" name="scheduled_at" 
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Interviewer</label>
                                    <input type="text" name="interviewer" 
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Location/Meeting Link</label>
                                    <input type="text" name="location" placeholder="Physical location or meeting link"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                                    <input type="text" name="notes" placeholder="Any special instructions"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Schedule {{ ucfirst(str_replace('_', ' ', $candidate->status)) }}
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
                @elseif($candidate->status === 'hired')
                    <!-- Offer letter section -->
                    <div class="mt-6 border-t pt-4">
                        <h3 class="font-medium mb-3">Hiring Completed</h3>
                        
                        @if($candidate->offerLetter)
                            <div class="bg-green-50 p-4 rounded-lg mb-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="font-medium">Offer Letter</h4>
                                        <p class="text-sm">
                                            Status: {{ ucfirst($candidate->offerLetter->status) }}
                                            @if($candidate->offerLetter->signed_at)
                                                - Signed on {{ $candidate->offerLetter->signed_at->format('M d, Y') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('offer-letters.show', $candidate->offerLetter) }}" target="_blank" 
                                           class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                            View
                                        </a>
                                        @if($candidate->offerLetter->status === 'draft')
                                        <form action="{{ route('admin.offer-letters.send', $candidate->offerLetter) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm">
                                                Send to Candidate
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('admin.offer-letters.create', $candidate) }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Create Offer Letter
                            </a>
                        @endif
                    </div>
                @endif
                
                <!-- Process History -->
                <div class="mt-6 border-t pt-4">
                    <h3 class="font-medium mb-3">Process History</h3>
                    <div class="space-y-4">
                        @foreach($candidate->stages->sortBy('scheduled_at') as $stage)
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <div class="flex justify-between">
                                <h4 class="font-medium">{{ ucfirst(str_replace('_', ' ', $stage->stage)) }}</h4>
                                <span class="text-sm text-gray-500">
                                    @if($stage->scheduled_at)
                                        {{ $stage->scheduled_at->format('M d, Y H:i') }}
                                    @else
                                        Not scheduled
                                    @endif
                                </span>
                            </div>
                            @if($stage->completed_at)
                                <p class="text-sm mt-1">
                                    <span class="font-medium">Result:</span> 
                                    <span class="{{ $stage->result === 'pass' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ ucfirst($stage->result) }}
                                    </span>
                                </p>
                                @if($stage->feedback)
                                <p class="text-sm mt-1">
                                    <span class="font-medium">Feedback:</span> 
                                    {{ is_array($stage->feedback) ? implode(', ', $stage->feedback) : $stage->feedback }}
                                </p>
                                @endif
                            @endif
                            @if($stage->notes)
                            <p class="text-sm mt-1">
                                <span class="font-medium">Notes:</span> {{ $stage->notes }}
                            </p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Status Update -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold mb-4">Update Status</h2>
                <form action="{{ route('admin.candidates.update-status', $candidate) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        @foreach(['applied', 'initial_interview', 'demo', 'exam', 'final_interview', 'pre_employment', 'hired', 'onboarding', 'rejected'] as $status)
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="{{ $status }}" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                   {{ $candidate->status === $status ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


</body>
</html>