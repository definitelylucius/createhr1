<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexFleetDynamics | Bus Staff Recruitment System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/dayjs@1.11.7/dayjs.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .hero-gradient {
            background: linear-gradient(135deg, #00446b 0%, #1e81b0 50%, #6fb3d2 100%);
        }
        .scroll-smooth {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="scroll-smooth">
    <!-- Navigation -->
    <header class="bg-white shadow-md fixed w-full z-50 border-b border-gray-300">
    <nav class="container mx-auto px-6 py-3 flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center">
            <button onclick="scrollToSection('hero')" class="text-[#00446b] text-xl font-bold md:text-2xl flex items-center">
                NexFleetDynamics
            </button>
        </div>

        <!-- Desktop Navigation Links -->
        <div class="hidden md:flex space-x-8">
            <button onclick="scrollToSection('features')" class="text-gray-700 hover:text-[#00446b] font-medium">How It Works</button>
            <button onclick="scrollToSection('jobs')" class="text-gray-700 hover:text-[#00446b] font-medium">Job Board</button>
            <button onclick="scrollToSection('faq')" class="text-gray-700 hover:text-[#00446b] font-medium">FAQs</button>
        </div>

        <!-- Auth Section -->
        <div class="hidden md:flex items-center space-x-4">
            @guest
                <a href="{{ route('login') }}" class="bg-[#00446b] text-white px-4 py-2 rounded-md hover:bg-[#1F2936] transition">Login</a>
            @else
                <!-- Profile Button -->
                <button type="button" class="p-2 rounded-full hover:bg-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>

                <!-- Settings Dropdown -->
                <div class="relative">
                    <button type="button" onclick="toggleSettingsDropdown()" class="p-2 rounded-full hover:bg-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                    <div id="settingsDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-md shadow-lg z-20">
                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Settings</a>
                        
                        @php
    $latestApplication = auth()->user()->jobApplications()->latest()->first();
    $hasRequestedDocuments = $latestApplication && 
                            $latestApplication->preEmploymentDocument &&
                            !empty(json_decode($latestApplication->preEmploymentDocument->requested_documents ?? '[]', true));
@endphp

@if($hasRequestedDocuments)
    <a href="{{ route('applicant.documents.upload', ['applicationId' => $latestApplication->id]) }}" 
       class="block px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors duration-200">
        Upload Required Documents
    </a>
@else
    <p class="block px-4 py-2 text-gray-600 bg-gray-100 rounded-md">
    @if($latestApplication)
        @if($latestApplication->status === \App\Models\JobApplication::STATUS_PROCESSING)
            Documents submitted - application in progress
        @elseif($latestApplication->status === \App\Models\JobApplication::STATUS_PENDING)
            Waiting for document request
        @elseif($latestApplication->status === \App\Models\JobApplication::STATUS_FINAL_INTERVIEW_PASSED)
            Final interview passed - please wait for document request
        @else
            No documents required at this time
        @endif
    @else
        No active application found
    @endif
    </p>
@endif


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

        <!-- Mobile Menu Button -->
        <div class="md:hidden">
            <button id="mobile-menu-button" class="text-gray-700 hover:text-[#00446b]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>
</header>




   <!-- Hero Section - Redesign -->
<section id="hero" class="relative bg-gradient-to-br from-[#00446b] to-[#00263e] pt-28 pb-24 text-white overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute top-0 left-0 w-full h-full opacity-10">
        <div class="absolute top-20 left-20 w-64 h-64 rounded-full bg-blue-300 mix-blend-overlay filter blur-3xl"></div>
        <div class="absolute bottom-10 right-20 w-80 h-80 rounded-full bg-blue-400 mix-blend-overlay filter blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <!-- Text Content -->
            <div class="lg:w-1/2 text-center lg:text-left">
                <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full mb-6 border border-white/20">
                    <span class="mr-2"></span>
                    <span class="font-medium">Hiring Bus Professionals</span>
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                    <span class="block">Find & Hire</span>
                    <span class="block text-blue-200">Skilled Bus Staff</span>
                </h1>
                
                <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-2xl mx-auto lg:mx-0">
                    Connecting transportation companies with qualified drivers and crew through our efficient recruitment platform
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <button onclick="scrollToSection('jobs')" 
                            class="bg-white text-[#00446b] px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl active:scale-95">
                        View Job Openings
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button onclick="scrollToSection('features')" 
                            class="bg-transparent border-2 border-white px-8 py-4 rounded-lg font-semibold hover:bg-white/10 transition-all shadow-lg hover:shadow-xl active:scale-95">
                        How It Works
                    </button>
                </div>
                
                <!-- Trust indicators -->
                <div class="mt-12 flex flex-wrap justify-center lg:justify-start gap-6">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-300 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Verified Professionals</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-300 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Fast Hiring Process</span>
                    </div>
                </div>
            </div>
            
            <!-- Image -->
            <div class="lg:w-1/2 flex justify-center relative">
                <div class="relative max-w-xl w-full">
                   
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-blue-400 rounded-xl -z-10 opacity-30"></div>
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-yellow-400 rounded-xl -z-10 opacity-30"></div>
                </div>
            </div>
        </div>
    </div>
</section>

   
<!-- How It Works Section -->
<section id="features" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">How It Works</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Follow these simple steps to join our transportation team
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="group bg-gray-50 p-8 rounded-xl border border-gray-200 hover:border-blue-300 transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-100 text-blue-600 w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl mr-4 group-hover:bg-blue-600 group-hover:text-white transition">
                        1
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Create Account</h3>
                </div>
                <p class="text-gray-600 mb-6">
                Login your account to get started on our platform.
                </p>
                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition">
                    Get Started
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>

           <!-- Step 2 -->
<div class="group bg-gray-50 p-8 rounded-xl border border-gray-200 hover:border-blue-300 transition-all duration-300">
    <div class="flex items-center mb-6">
        <div class="bg-green-100 text-green-600 w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl mr-4 group-hover:bg-green-600 group-hover:text-white transition">
            2
        </div>
        <h3 class="text-xl font-bold text-gray-800">Find Positions</h3>
    </div>
    <p class="text-gray-600 mb-6">
        Find the perfect job that fits your skills and passion.
    </p>
    <button onclick="scrollToSection('jobs')" class="inline-flex items-center px-4 py-2 bg-green-50 text-green-600 rounded-lg font-medium hover:bg-green-100 transition cursor-pointer">
        View Jobs
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>
</div>
            <!-- Step 3 -->
            <div class="group bg-gray-50 p-8 rounded-xl border border-gray-200 hover:border-blue-300 transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-purple-100 text-purple-600 w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl mr-4 group-hover:bg-purple-600 group-hover:text-white transition">
                        3
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Submit Application</h3>
                </div>
                <p class="text-gray-600 mb-6">
                    Upload your documents and apply for your preferred transportation roles.
                </p>
                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-purple-50 text-purple-600 rounded-lg font-medium hover:bg-purple-100 transition">
                    Apply Now
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>

       
</section>

     <!-- Careers Section (with Job Openings) -->
<section id="jobs" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Join Our Team</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                We're always looking for talented individuals to join our growing team
            </p>
        </div>

        <!-- Job Openings Section -->
        <div x-data='{
            jobs: @json($jobs),
            selectedJob: null,
            searchQuery: "",
            currentPage: 1,
            perPage: 5,
            
            get filteredJobs() {
                return this.jobs.filter(j => j.title.toLowerCase().includes(this.searchQuery.toLowerCase()))
            },
            
            get paginatedJobs() {
                const start = (this.currentPage - 1) * this.perPage
                const end = start + this.perPage
                return this.filteredJobs.slice(start, end)
            },
            
            get totalPages() {
                return Math.ceil(this.filteredJobs.length / this.perPage)
            }
        }' class="bg-white rounded-xl shadow-lg overflow-hidden">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                <!-- Left Column: Job Listings -->
                <div class="p-6 border-r border-gray-200">
                    <h3 class="text-2xl font-bold mb-6 text-gray-800">Current Openings</h3>

                    <!-- Search Bar -->
                    <div class="flex items-center bg-gray-100 rounded-lg w-full shadow-sm mb-6">
                        <input type="text"
                               placeholder="Search jobs..."
                               class="w-full text-gray-700 px-4 py-3 bg-white rounded-l-lg focus:outline-none focus:ring-2 focus:ring-[#00446b]"
                               x-model="searchQuery"
                               @input="currentPage = 1" />
                        <button class="bg-[#00446b] text-white px-4 py-3 rounded-r-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Job Listings -->
                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                        <template x-for="job in paginatedJobs" :key="job.id">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 cursor-pointer hover:bg-blue-50 transition-all"
                                 @click="selectedJob = job">
                                <h3 class="text-lg font-bold text-[#00446b]" x-text="job.title"></h3>
                                <p class="text-gray-500 text-sm">
                                    <strong>Location:</strong> <span x-text="job.location"></span>
                                </p>
                                <p class="text-gray-500 text-sm">
                                    <strong>Department:</strong> <span x-text="job.department"></span>
                                </p>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full" x-text="job.type"></span>
                                    <span class="text-gray-500 text-xs" x-text="dayjs(job.created_at).fromNow()"></span>
                                </div>
                            </div>
                        </template>

                        <p x-show="filteredJobs.length === 0"
                           class="text-gray-500 text-center py-8">
                            No matching jobs found. Please try a different search.
                        </p>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200" 
                         x-show="filteredJobs.length > perPage">
                        <div class="text-sm text-gray-500">
                            <span x-text="(currentPage - 1) * perPage + 1"></span>-<span 
                            x-text="Math.min(currentPage * perPage, filteredJobs.length)"></span> of 
                            <span x-text="filteredJobs.length"></span> jobs
                        </div>
                        <div class="flex space-x-1">
                            <button @click="currentPage--" 
                                    :disabled="currentPage === 1"
                                    class="px-3 py-1 rounded border disabled:opacity-50">
                                Prev
                            </button>
                            <template x-for="page in totalPages" :key="page">
                                <button @click="currentPage = page"
                                        :class="{'bg-[#00446b] text-white': currentPage === page}"
                                        class="w-10 h-10 rounded border">
                                    <span x-text="page"></span>
                                </button>
                            </template>
                            <button @click="currentPage++" 
                                    :disabled="currentPage === totalPages"
                                    class="px-3 py-1 rounded border disabled:opacity-50">
                                Next
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Job Details (unchanged) -->
                <div x-show="selectedJob" class="p-6 bg-gray-50" x-cloak>
                    <div x-show="selectedJob" class="h-full">
                        <h2 class="text-2xl font-bold text-[#00446b]" x-text="selectedJob.title"></h2>
                        <p class="text-gray-700 font-medium" x-text="selectedJob.company"></p>
                        <div class="flex items-center space-x-4 mt-2 mb-4">
                            <span class="text-gray-500 text-sm" x-text="selectedJob.location"></span>
                            <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full" x-text="selectedJob.type"></span>
                        </div>

                        <!-- Job Details -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Job Description</h3>
                                <p class="text-gray-600" x-html="selectedJob.description.replace(/\n/g, '<br>')"></p>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Responsibilities</h3>
                                <p class="text-gray-600" x-html="selectedJob.responsibilities.replace(/\n/g, '<br>')"></p>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Qualifications</h3>
                                <p class="text-gray-600" x-html="selectedJob.qualifications.replace(/\n/g, '<br>')"></p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Salary Range</h3>
                                    <p class="text-gray-600">
                                        ₱<span x-text="Number(selectedJob.min_salary).toLocaleString('en-US', { minimumFractionDigits: 0 })"></span> 
                                        - 
                                        ₱<span x-text="Number(selectedJob.max_salary).toLocaleString('en-US', { minimumFractionDigits: 0 })"></span> 
                                        <span class="text-gray-500">per month</span>
                                    </p>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Experience Level</h3>
                                    <p class="text-gray-600" x-text="selectedJob.experience_level"></p>
                                </div>
                            </div>

                            <button @click="window.location.href = '/apply/' + selectedJob.id" 
                                class="mt-6 bg-[#00446b] text-white px-6 py-3 rounded-lg hover:bg-[#1F2936] transition w-full">
                                Apply Now
                            </button>
                        </div>
                    </div>

                    <div x-show="!selectedJob" class="h-full flex items-center justify-center text-gray-500" x-cloak>
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <p>Select a job from the list to view details</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Frequently Asked Questions</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Common questions about our transportation hiring system
                </p>
            </div>

            <div class="max-w-3xl mx-auto">
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <!-- FAQ Item 1 -->
                    <div class="border-b border-gray-200">
                        <button class="flex justify-between items-center w-full px-6 py-4 text-left hover:bg-gray-50">
                            <span class="font-medium text-gray-800">What documents do I need to apply?</span>
                            <svg class="h-5 w-5 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="px-6 pb-4 hidden">
                            <p class="text-gray-600">
                                You'll need a valid driver's license (CDL if required for the position), your driving record, and any relevant certifications. The system will guide you through the specific requirements for each position.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="border-b border-gray-200">
                        <button class="flex justify-between items-center w-full px-6 py-4 text-left hover:bg-gray-50">
                            <span class="font-medium text-gray-800">How long does the hiring process take?</span>
                            <svg class="h-5 w-5 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="px-6 pb-4 hidden">
                            <p class="text-gray-600">
                                Most candidates complete the initial application in 15-20 minutes. The screening process typically takes 3-5 business days. You'll receive email updates at each stage.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="border-b border-gray-200">
                        <button class="flex justify-between items-center w-full px-6 py-4 text-left hover:bg-gray-50">
                            <span class="font-medium text-gray-800">Can I check my application status?</span>
                            <svg class="h-5 w-5 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="px-6 pb-4 hidden">
                            <p class="text-gray-600">
                                Yes, once you create an account you can log in anytime to view your application status, see pending tasks, and check for messages from the hiring team.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="border-b border-gray-200">
                        <button class="flex justify-between items-center w-full px-6 py-4 text-left hover:bg-gray-50">
                            <span class="font-medium text-gray-800">What training is provided?</span>
                            <svg class="h-5 w-5 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="px-6 pb-4 hidden">
                            <p class="text-gray-600">
                                All new hires complete safety training and route familiarization. Additional training is provided based on position requirements, including defensive driving and passenger assistance.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @php
use Illuminate\Support\Facades\Storage;

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
   
@endif

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                    
                        NexFleetDynamics
                    </h3>
                    <p class="text-gray-400">
                        Transportation staff recruitment and management system
                    </p>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><button onclick="scrollToSection('features')" class="text-gray-400 hover:text-white">How It Works</button></li>
                        <li><button onclick="scrollToSection('jobs')" class="text-gray-400 hover:text-white">Job Board</button></li>
                        <li><button onclick="scrollToSection('faq')" class="text-gray-400 hover:text-white">FAQs</button></li>
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white">Login</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>nexfleet.dynamics9@gmail.com</li>
                        <li>(555) 123-4567</li>
                        <li>8:00 AM - 5:00 PM, Mon-Fri</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>© 2025 NexFleetDynamics. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // FAQ accordion functionality
        document.querySelectorAll('#faq button').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.nextElementSibling;
                const icon = button.querySelector('svg');
                
                content.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            });
        });

        // Smooth scrolling function
        function scrollToSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                // Close mobile menu if open
                document.getElementById('mobile-menu').classList.add('hidden');
                
                // Calculate position accounting for fixed header
                const headerHeight = document.querySelector('header').offsetHeight;
                const sectionPosition = section.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: sectionPosition,
                    behavior: 'smooth'
                });
            }
        }

        function toggleSettingsDropdown() {
            const dropdown = document.getElementById('settingsDropdown');
            dropdown.classList.toggle('hidden');
        }
    </script>
</body>
</html>