<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.7/dayjs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.7/plugin/relativeTime.min.js"></script>
<script>
    dayjs.extend(window.dayjs_plugin_relativeTime);
</script>

    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/login.css">
    <title>NexFleetDynamics</title>
</head>

<body>
<header class="bg-white shadow-md fixed w-full z-50 border-b border-gray-300">
    <nav class="container mx-auto px-6 py-3 flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center">
            <button onclick="scrollToSection('were')" class="text-[#00446b] text-xl font-bold md:text-2xl">
                NexFleetDynamics
            </button>
        </div>

        <!-- Authentication Logic -->
        <div class="flex items-center space-x-4">
            @guest
                <!-- Show Sign In button when not logged in -->
                <a href="{{ route('login') }}" class="bg-[#00446b] text-white px-4 py-2 rounded-md">Sign In</a>
            @else
                <!-- Show Profile and Settings when logged in -->
                <!-- Profile Icon -->
                <button type="button" class="p-2 rounded-full hover:bg-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>

                <!-- Settings Icon with Dropdown -->
                <div class="relative">
                    <button type="button" onclick="toggleSettingsDropdown()" class="p-2 rounded-full hover:bg-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>

                    <!-- Settings Dropdown -->
                    <div id="settingsDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-md shadow-lg z-20">
                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </nav>
</header>

<div class="relative isolate px-6 pt-14 lg:px-8">
    <!-- Top Gradient Background Shape -->
    <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
        <div class="relative left-[calc(50%-50vw)] aspect-[1155/678] w-[80vw] -translate-x-1/2 rotate-[30deg] 
            bg-gradient-to-tr from-[#00446b] via-[#1e81b0] to-[#6fb3d2] opacity-30 sm:w-[100vw]"
            style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 
            72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 
            0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
        </div>
    </div>

    <!-- Main Content Section -->
    <div id="were" class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56 text-center">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl">
            We're Hiring: Join Our Team Today!
        </h1>
        <p class="mt-6 text-lg leading-8 text-gray-600">
            Looking for a new opportunity? We're recruiting drivers, fleet managers, and transportation staff to join our growing team. Apply now and drive your career forward with us.
        </p>
        <div class="mt-10 flex items-center justify-center gap-x-6">
            <a href="#" class="rounded-md bg-[#00446b] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#1F2936]">
                Get started
            </a>
            <a href="#" class="text-sm font-semibold leading-6 text-gray-900">
                Learn more <span aria-hidden="true">→</span>
            </a>
        </div>
    </div>
</div>

<!-- How It Works Section -->
<section id="about"  class="bg-gray-50 py-16">
    <div  class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">How It Works</h2>
            <p class="text-lg text-gray-600 mt-4">Follow these simple steps to get started with us</p>
        </div>

        <div  class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- First Card -->
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <div class="mx-auto bg-yellow-100 p-4 w-16 h-16 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-8 w-8 text-yellow-500">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mt-6">Login Your Account</h3>
                <p class="text-gray-600 mt-4">Login your account to get started on our platform.</p>
                <button onclick="openModal()" class="text-[#00446b] font-semibold mt-4 inline-block">Read More</button>
            </div>

            <!-- Second Card -->
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <div class="mx-auto bg-purple-100 p-4 w-16 h-16 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-8 w-8 text-purple-500">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6h8m0 0V4m0 2v6m0 0h-8M4 4h4m0 0v6m0 0H4m0 0v6m0 0h4" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mt-6">Search Job</h3>
                <p class="text-gray-600 mt-4">Find the perfect job that fits your skills and passion.</p>
                <a href="#" class="text-[#00446b] font-semibold mt-4 inline-block">Read More</a>
            </div>

            <!-- Third Card -->
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <div class="mx-auto bg-blue-100 p-4 w-16 h-16 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-8 w-8 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m-6 0h12m-6-6a3 3 0 100-6 3 3 0 000 6z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mt-6">Submit Resume</h3>
                <p class="text-gray-600 mt-4">Upload your resume and start applying to your desired jobs.</p>
                <a href="#" class="text-[#00446b] font-semibold mt-4 inline-block">Read More</a>
            </div>
        </div>
    </div>
</section>

<div x-data='{
    jobs: @json($jobs),  // ✅ Correct
    selectedJob: null,
    searchQuery: ""
}
' class="container mx-auto px-6 py-12">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Left Column: Job Listings -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Job Openings</h2>

            <!-- Search Bar (Only affects Job Openings) -->
            <div class="hidden md:flex items-center bg-gray-100 rounded-lg w-full shadow-sm mb-4">
                <input type="text"
                       placeholder="Search..."
                       class="input input-bordered w-full text-gray-700 px-4 py-2 bg-white rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary"
                       x-model="searchQuery" />
                <button class="btn btn-primary px-4 py-2 rounded-r-lg">Search</button>
            </div>

            <!-- Display filtered jobs -->
            <template x-for="job in jobs.filter(j => j.title.toLowerCase().includes(searchQuery.toLowerCase()))" :key="job.id">
                <div class="bg-gray-100 border border-gray-300 rounded-lg p-4 mb-4 cursor-pointer hover:bg-blue-100 transition-all"
                     @click="selectedJob = job">
                    <h3 class="text-lg font-bold text-blue-700" x-text="job.title"></h3>

                    <!-- Location -->
                    <p class="text-gray-500 text-sm">
                        <strong>Location:</strong> <span x-text="job.location"></span>
                    </p>

                    <!-- Department -->
                    <p class="text-gray-500 text-sm">
                        <strong>Department:</strong> <span x-text="job.department"></span>
                    </p>

                    <!-- Posted Date -->
                    <p class="text-gray-500 text-xs">
                        <strong>Posted:</strong>
                        <span x-text="dayjs(job.created_at).fromNow()"></span>
                    </p>

                    <!-- Job Type -->
                    <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full" x-text="job.type"></span>
                </div>
            </template>

            <!-- No jobs found message -->
            <p x-show="jobs.filter(j => j.title.toLowerCase().includes(searchQuery.toLowerCase())).length === 0"
               class="text-gray-500 text-sm text-center mt-4">
                No jobs found.
            </p>
        </div>

        <!-- Right Column: Job Details (Now inside the grid) -->
        <div x-show="selectedJob" class="bg-white p-6 rounded-lg shadow-lg border border-gray-300" x-cloak>
            <h2 class="text-2xl font-bold text-blue-700" x-text="selectedJob.title"></h2>
            <p class="text-gray-700 font-medium" x-text="selectedJob.company"></p>
            <p class="text-gray-500 text-sm" x-text="selectedJob.location"></p>
            <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full" x-text="selectedJob.type"></span>

            <!-- Additional Job Details -->
            <div class="mt-4">
                <h3 class="text-lg font-semibold text-gray-800">Department</h3>
                <p class="text-gray-600" x-text="selectedJob.department"></p>
            </div>

            <div class="mt-4">
                <h3 class="text-lg font-semibold text-gray-800">Experience Level</h3>
                <p class="text-gray-600" x-text="selectedJob.experience_level"></p>
            </div>

            <div class="mt-4">
    <h3 class="text-lg font-semibold text-gray-800">Job Description</h3>
    <p class="text-gray-600" x-html="selectedJob.description.replace(/\n/g, '<br>')"></p>
</div>

<div class="mt-4">
    <h3 class="text-lg font-semibold text-gray-800">Responsibilities</h3>
    <p class="text-gray-600" x-html="selectedJob.responsibilities.replace(/\n/g, '<br>')"></p>
</div>

<div class="mt-4">
    <h3 class="text-lg font-semibold text-gray-800">Qualifications</h3>
    <p class="text-gray-600" x-html="selectedJob.qualifications.replace(/\n/g, '<br>')"></p>
</div>


            <div class="mt-4">
                <h3 class="text-lg font-semibold text-gray-800">Salary Range</h3>
                <p class="text-gray-600">
                    ₱<span x-text="Number(selectedJob.min_salary).toLocaleString('en-US', { minimumFractionDigits: 0 })"></span> 
                    - 
                    ₱<span x-text="Number(selectedJob.max_salary).toLocaleString('en-US', { minimumFractionDigits: 0 })"></span> 
                    <span class="text-gray-500">per month</span>
                </p>
            </div>

           

            <div class="mt-4">
                <h3 class="text-lg font-semibold text-gray-800">Status</h3>
                <p class="text-gray-600">
                    <span x-text="selectedJob.status"></span>
                </p>
            </div>

         
   
    <!-- Apply Button -->
    <button @click="window.location.href = '/apply/' + selectedJob.id" 
        class="mt-4 bg-[#00446b] text-white px-6 py-2 rounded-md hover:bg-[#1F2936] transition">
        Apply Now
    </button>
</div>

<!-- Debug Database Parsing Results -->
@php
use Illuminate\Support\Facades\Storage;
@endphp

@php
    function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $bytes /= pow(1024, $pow);
        return number_format($bytes, $precision) . ' ' . $units[$pow];
    }
@endphp

@if(session('success'))
<div class="fixed bottom-4 right-4 w-96 bg-white shadow-lg rounded-lg border border-gray-300 z-50" style="cursor: grab">
    <div class="bg-green-500 text-white p-3 rounded-t-lg flex justify-between items-center">
        <h3 class="font-bold">Application Submitted</h3>
        <button onclick="this.closest('.fixed').remove();" class="text-white hover:text-gray-200">
            &times;
        </button>
    </div>

    <div class="p-4 max-h-96 overflow-y-auto">
        @php
            // Fetch the latest candidate details (submitted by the authenticated user)
            $candidate = auth()->user()->candidates()->latest()->first();
            
            // Fetch the candidate's resume document
            $candidateDocument = $candidate ? $candidate->documents()->where('type', 'resume')->latest()->first() : null;
            $fileExists = $candidateDocument && Storage::disk('candidate_documents')->exists($candidateDocument->file_path);
            $fileSize = $fileExists ? Storage::disk('candidate_documents')->size($candidateDocument->file_path) : 0;

            // Fetch parsed resume data if available
            $parsedResume = $candidate ? $candidate->parsedResumes()->latest()->first() : null;
        @endphp

       

        <!-- Candidate Info -->
        <div class="mb-4">
            <h4 class="font-medium text-sm text-gray-700 mb-1">Candidate Details</h4>
            <div class="text-xs space-y-1">
                <p><strong class="w-32 inline-block">First Name:</strong> {{ $candidate->first_name ?? 'NULL' }}</p>
                <p><strong class="w-32 inline-block">Last Name:</strong> {{ $candidate->last_name ?? 'NULL' }}</p>
                <p><strong class="w-32 inline-block">Email:</strong> {{ $candidate->email ?? 'NULL' }}</p>
                <p><strong class="w-32 inline-block">Phone:</strong> {{ $candidate->phone ?? 'NULL' }}</p>
                <p><strong class="w-32 inline-block">Status:</strong> {{ $candidate->status ?? 'NULL' }}</p>
            </div>
        </div>

        <!-- Resume File Storage -->
        @if($fileExists)
        <div class="mt-3 text-xs">
            <h4 class="font-medium text-sm text-gray-700 mb-1">File Storage</h4>
            <p>
                <strong>Exists:</strong> YES
            </p>
            <p>
                <strong>Size:</strong> {{ formatBytes($fileSize) }}
            </p>
        </div>
        @else
        <div class="mt-3 text-xs">
            <h4 class="font-medium text-sm text-gray-700 mb-1">File Storage</h4>
            <p><strong>Exists:</strong> NO</p>
            <p><strong>Size:</strong> 0 bytes</p>
        </div>
        @endif

        <!-- Parsed Resume Data -->
        @if($parsedResume)
        <div class="mt-4">
            <h4 class="font-medium text-sm text-gray-700 mb-1">Parsed Resume Data</h4>
            <div class="text-xs space-y-1">
                <p><strong class="w-32 inline-block">Skills:</strong> {{ implode(', ', $parsedResume->skills ?? []) }}</p>
                <p><strong class="w-32 inline-block">Experience Years:</strong> {{ $parsedResume->experience_years ?? 'N/A' }}</p>
                <p><strong class="w-32 inline-block">Education:</strong> {{ $parsedResume->education ?? 'N/A' }}</p>
                <p><strong class="w-32 inline-block">Job History:</strong> {{ implode(', ', $parsedResume->job_history ?? []) }}</p>
            </div>
        </div>
        @else
        <div class="mt-4 text-xs">
            <p>No parsed resume data available.</p>
        </div>
        @endif
    </div>
</div>
@endif

<script>
// Close the notification when the close button is clicked
document.querySelector('button').addEventListener('click', function() {
    this.parentElement.parentElement.remove();
});

// Auto-refresh debug info every 5 seconds (optional)
let debugRefreshInterval;
function startDebugRefresh() {
    debugRefreshInterval = setInterval(() => {
        // Refresh logic (optional)
        console.log('Notification panel active - would refresh now');
    }, 5000);
}

// Start the refresh if the panel is visible
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('[data-debug-panel]')) {
        startDebugRefresh();
    }
});

function toggleSettingsDropdown() {
            const dropdown = document.getElementById('settingsDropdown');
            dropdown.classList.toggle('hidden');
        }
</script>

</body>
</html>

