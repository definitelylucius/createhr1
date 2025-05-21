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
  <title>Create Offer Letter| Bus Transportation</title>
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
        <h1 class="text-2xl font-bold">Create Offer Letter for {{ $candidate->full_name }}</h1>
        <a href="{{ route('admin.candidates.show', $candidate) }}" class="text-blue-600 hover:text-blue-800">Back to Candidate</a>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.offer-letters.store', $candidate) }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="title" value="Employment Offer Letter" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                    <input type="text" name="position" id="position" value="{{ $candidate->job->title }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="salary" class="block text-sm font-medium text-gray-700">Salary</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="salary" id="salary" step="0.01" min="0" 
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">USD</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" id="start_date" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>
                
                <div>
                    <label for="benefits" class="block text-sm font-medium text-gray-700">Benefits (one per line)</label>
                    <textarea name="benefits" id="benefits" rows="3" 
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">Health Insurance
Dental Insurance
Paid Time Off
Retirement Plan</textarea>
                </div>
                
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Letter Content</label>
                    <textarea name="content" id="content" rows="12" 
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
Dear {{ $candidate->first_name }},

We are pleased to offer you the position of [Position] at NexFleet Dynamics. 

**Position:** [Position]
**Start Date:** [Start Date]
**Salary:** $[Salary] per [year/month/hour]
**Benefits:** [List of Benefits]

This offer is contingent upon satisfactory completion of all pre-employment requirements. 

Please sign this letter to indicate your acceptance of this offer. We look forward to having you join our team!

Sincerely,
[Your Name]
[Your Title]
NexFleet Dynamics
                    </textarea>
                </div>
            </div>
            
            <div class="mt-6">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create Offer Letter
                </button>
            </div>
        </form>
    </div>
</div>



</body>
</html>