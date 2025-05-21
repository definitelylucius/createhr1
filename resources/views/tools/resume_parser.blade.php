@extends('layouts.staff')

@section('content')
<!-- Main Layout Structure -->
<div class="flex h-screen bg-gray-50" data-theme="light">
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-20 w-64 bg-white shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out">
    @if(auth()->user()->role === 'admin')
    @include('layouts.partials.admin-sidebar')
@elseif(auth()->user()->role === 'staff')
    @include('layouts.partials.staff-sidebar')
@endif

    </aside>
    
    <div class="flex-1 flex flex-col overflow-hidden lg:ml-64">
        <!-- Navbar -->
        @if(auth()->user()->role === 'admin')
    @include('layouts.partials.admin-navbar')
@elseif(auth()->user()->role === 'staff')
    @include('layouts.partials.staff-navbar')
@endif

        
        <!-- Debugging Alert -->
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mx-6 mt-4">
                <p class="font-bold">Error</p>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto pt-20">
            <div class="container mx-auto px-4 py-6">
                <div class="mb-8">
                    <h4 class="text-2xl font-bold text-gray-800">Resume Parser Tool</h4>
                    <p class="text-gray-600 mt-2">Upload a resume to extract candidate information</p>
                </div>

                <!-- Upload Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-8">
                        <form id="resumeUploadForm" action="{{ route('tools.parse-resume') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                                <!-- File Dropzone -->
                                <div>
                                    <div id="dropzone" class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-8 hover:border-blue-500 transition-colors h-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <h5 class="text-lg font-medium text-gray-700 mb-2">Drag and drop resume file here</h5>
                                        <p class="text-gray-500 mb-4">or</p>
                                        <input type="file" name="resume" id="resume" class="hidden" accept=".pdf,.docx" required>
                                        <label for="resume" class="cursor-pointer px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                            Select File
                                        </label>
                                        <small class="text-gray-500 mt-3 text-sm">Supports PDF and DOCX files (max 5MB)</small>
                                        <div id="fileInfo" class="mt-3 hidden">
                                            <p id="fileNameDisplay" class="text-sm font-medium text-gray-700"></p>
                                        </div>
                                        <div id="fileError" class="text-red-500 text-sm mt-2 hidden"></div>
                                    </div>
                                </div>
                                
                                <!-- Instructions -->
                                <div class="flex flex-col justify-center">
                                    <h5 class="text-lg font-medium mb-4 text-gray-700">How to use:</h5>
                                    <ol class="space-y-3">
                                        <li class="flex items-start">
                                            <span class="flex items-center justify-center bg-blue-100 text-blue-600 rounded-full w-6 h-6 mr-3 flex-shrink-0">1</span>
                                            <span class="text-gray-600">Upload a candidate's resume</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="flex items-center justify-center bg-blue-100 text-blue-600 rounded-full w-6 h-6 mr-3 flex-shrink-0">2</span>
                                            <span class="text-gray-600">The system will extract key information</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="flex items-center justify-center bg-blue-100 text-blue-600 rounded-full w-6 h-6 mr-3 flex-shrink-0">3</span>
                                            <span class="text-gray-600">Review and edit the parsed data</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="flex items-center justify-center bg-blue-100 text-blue-600 rounded-full w-6 h-6 mr-3 flex-shrink-0">4</span>
                                            <span class="text-gray-600">Save to candidate profile</span>
                                        </li>
                                    </ol>
                                    <div class="bg-blue-50 text-blue-800 p-4 rounded-lg mt-6 flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                                        </svg>
                                        <span>For best results, use resumes with clear section headings.</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-base">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                    </svg>
                                    Parse Resume
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Parsed Data Section -->
                @if(session('parsedData') || isset($parsedData))
                    @php
                        $dataToShow = session('parsedData') ?? $parsedData;
                        $rawTextToShow = session('rawText') ?? $rawText;
                    @endphp
                    
                    <div class="mt-10">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-gray-50 px-8 py-5 border-b border-gray-200">
                                <h5 class="font-semibold text-lg text-gray-800">Parsed Resume Information</h5>
                                @if(session('success'))
                                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mt-3 rounded">
                                        {{ session('success') }}
                                    </div>
                                @endif
                            </div>
                            <div class="p-8">
                                <form action="{{ route('applicants.store') }}" method="POST">
                                    @csrf
                                    
                                    <!-- Hidden field to store original file data -->
                                    <input type="hidden" name="original_file_data" value="{{ json_encode($dataToShow) }}">
                                    
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                                        <!-- Basic Information -->
                                        <div>
                                            <h6 class="font-medium text-gray-700 mb-5 text-lg">Basic Information</h6>
                                            <div class="mb-5">
                                                <label class="block text-gray-700 mb-2 font-medium">Full Name</label>
                                                <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                       name="name" value="{{ $dataToShow['name'] ?? '' }}" required>
                                            </div>
                                            <div class="mb-5">
                                                <label class="block text-gray-700 mb-2 font-medium">Email</label>
                                                <input type="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                       name="email" value="{{ $dataToShow['email'] ?? '' }}" required>
                                            </div>
                                            <div class="mb-5">
                                                <label class="block text-gray-700 mb-2 font-medium">Phone</label>
                                                <input type="tel" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                       name="phone" value="{{ $dataToShow['phone'] ?? '' }}">
                                            </div>
                                        </div>
                                        
                                        <!-- Professional Information -->
                                        <div>
                                            <h6 class="font-medium text-gray-700 mb-5 text-lg">Professional Information</h6>
                                            <div class="mb-5">
                                                <label class="block text-gray-700 mb-2 font-medium">Skills</label>
                                                <textarea class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                          name="skills" rows="4">{{ $dataToShow['skills'] ?? '' }}</textarea>
                                            </div>
                                            <div class="mb-5">
                                                <label class="block text-gray-700 mb-2 font-medium">Experience</label>
                                                <textarea class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                          name="experience" rows="4">{{ $dataToShow['experience'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Education -->
                                    <div class="mb-8">
                                        <h6 class="font-medium text-gray-700 mb-4 text-lg">Education</h6>
                                        <textarea class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                  name="education" rows="4">{{ $dataToShow['education'] ?? '' }}</textarea>
                                    </div>
                                    
                                    <!-- Raw Text -->
                                    <div class="mb-8">
                                        <div class="flex justify-between items-center mb-4">
                                            <h6 class="font-medium text-gray-700 text-lg">Raw Text</h6>
                                            <button type="button" onclick="copyRawText()" class="text-sm text-blue-600 hover:text-blue-800">
                                                Copy to Clipboard
                                            </button>
                                        </div>
                                        <div class="border border-gray-300 rounded-lg p-4 bg-gray-50 max-h-80 overflow-y-auto">
                                            <pre class="whitespace-pre-wrap text-sm text-gray-700 font-mono">{{ $rawTextToShow }}</pre>
                                        </div>
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <div class="text-right">
                                        <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z" />
                                            </svg>
                                            Save to Candidate Profile
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('resume');
        const fileInfo = document.getElementById('fileInfo');
        const fileNameDisplay = document.getElementById('fileNameDisplay');
        const fileError = document.getElementById('fileError');
        const form = document.getElementById('resumeUploadForm');

        // Handle file selection
        fileInput.addEventListener('change', function() {
            if (this.files.length) {
                const file = this.files[0];
                validateAndDisplayFile(file);
            }
        });

        // Drag and drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropzone.classList.add('border-blue-500', 'bg-blue-50');
        }

        function unhighlight() {
            dropzone.classList.remove('border-blue-500', 'bg-blue-50');
        }

        // Handle dropped files
        dropzone.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length) {
                fileInput.files = files;
                validateAndDisplayFile(files[0]);
            }
        });

        function validateAndDisplayFile(file) {
            // Validate file type
            const validTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (!validTypes.includes(file.type)) {
                fileError.textContent = 'Invalid file type. Please upload a PDF or DOCX file.';
                fileError.classList.remove('hidden');
                fileInfo.classList.add('hidden');
                return;
            }
            
            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                fileError.textContent = 'File size exceeds 5MB limit.';
                fileError.classList.remove('hidden');
                fileInfo.classList.add('hidden');
                return;
            }
            
            // If valid, display file name
            fileError.classList.add('hidden');
            fileNameDisplay.textContent = `Selected file: ${file.name}`;
            fileInfo.classList.remove('hidden');
        }

        // Form submission feedback
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="animate-spin h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                    </svg>
                    Processing...
                `;
            }
        });
    });

    function copyRawText() {
        const rawText = document.querySelector('pre').innerText;
        navigator.clipboard.writeText(rawText).then(() => {
            // Show a temporary notification
            const notification = document.createElement('div');
            notification.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg';
            notification.textContent = 'Copied to clipboard!';
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 2000);
        });
    }
</script>

@endsection