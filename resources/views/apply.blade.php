<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.10/dayjs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.10/plugin/relativeTime.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

    <script>
        dayjs.extend(dayjs_plugin_relativeTime);
    </script>
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/login.css">
    <title>NexFleetDynamics</title>
</head>

<body>
    <header class="bg-white py-4 px-6 flex justify-between items-center border-b border-gray-300 shadow-lg relative">
        <div class="font-bold text-2xl text-center text-[#00446b]">Nexfleet Dynamics</div>
        <div class="flex items-center space-x-4 relative">
            <!-- Profile and Settings Icons -->
        </div>
    </header>

    <div class="container mx-auto px-6 py-12">
        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
            <p class="font-medium">{{ session('success') }}</p>
            <div class="mt-3 p-3 bg-white rounded border border-green-200">
                <h4 class="font-medium text-green-800">Thank you for applying!</h4>
                <p class="text-sm text-gray-700 mt-1">
                    Your application has been received and is currently under review. We'll contact you if you're shortlisted for the next step.
                </p>
            </div>
        </div>
        @endif

        <!-- Department Badge -->
        <div class="flex items-center mb-4">
            <h2 class="text-2xl font-bold text-blue-700 mr-4">Apply for {{ $job->title ?? 'Position' }}</h2>
            @if(!empty($job->department))
            <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                {{ $job->department }} 
            </span>
            @endif
        </div>

        <!-- Department-Specific Requirements -->
        <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-100">
            <h3 class="font-semibold text-lg text-blue-800 mb-2">Key Qualifications:</h3>
            @php
                // Handle qualifications display with proper line breaks
                $qualifications = [];
                
                if (isset($job->qualifications)) {
                    if (is_array($job->qualifications)) {
                        $qualifications = $job->qualifications;
                    } elseif (is_string($job->qualifications)) {
                        // First try JSON decode if it might be encoded
                        $decoded = json_decode($job->qualifications, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $qualifications = $decoded;
                        } else {
                            // Fallback to splitting by newlines
                            $qualifications = array_filter(
                                preg_split("/\r\n|\n|\r/", $job->qualifications),
                                function($item) { return trim($item) !== ''; }
                            );
                        }
                    }
                }
            @endphp

            @if(count($qualifications) > 0)
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($qualifications as $qualification)
                        <li>{{ trim($qualification) }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">No specific qualifications listed</p>
            @endif
        </div>

        @if(in_array($job->department ?? null, ['Bus Transportation', 'Safety and Compliance']))
        <p class="mt-2 text-sm text-blue-700 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
            </svg>
            CDL license preferred for this position
        </p>
        @endif

        <!-- Application Form -->
       <form method="POST" action="{{ route('applications.store', ['job' => $job->id]) }}" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
    @csrf

    <!-- Job ID hidden input -->
    <input type="hidden" name="job_id" value="{{ $job->id }}">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-gray-700 font-medium mb-1">First Name *</label>
            <input type="text" name="firstname" 
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                required value="{{ old('firstname') }}">
            @error('firstname')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Last Name *</label>
            <input type="text" name="lastname" 
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                required value="{{ old('lastname') }}">
            @error('lastname')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Email *</label>
            <input type="email" name="email" 
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                required value="{{ old('email') }}">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Phone (Optional)</label>
            <input type="text" name="phone" 
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                value="{{ old('phone') }}">
        </div>
    </div>

    <div x-data="{ fileName: '' }" class="mt-6">
        <label class="block text-gray-700 font-medium mb-1">Upload Resume *</label>
        <div class="flex items-center justify-center w-full"
            @click="$refs.fileInput.click()"
            @dragover.prevent="$event.dataTransfer.dropEffect = 'copy';"
            @drop.prevent="
                fileName = $event.dataTransfer.files[0]?.name;
                $refs.fileInput.files = $event.dataTransfer.files;
            ">
            <div class="flex flex-col w-full border-2 border-dashed rounded-lg cursor-pointer hover:bg-gray-50">
                <template x-if="!fileName">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6 px-4">
                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                        <p class="text-xs text-gray-500">PDF or DOCX (MAX. 5MB)</p>
                    </div>
                </template>
                <template x-if="fileName">
                    <div class="p-4 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-2 font-medium" x-text="fileName"></p>
                    </div>
                </template>
            </div>
        </div>
        <input type="file" name="resume" x-ref="fileInput" class="hidden" accept=".pdf,.doc,.docx" 
            @change="fileName = $refs.fileInput.files[0]?.name" required />
        @error('resume')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Submit buttons -->
    <div class="mt-8 flex justify-end space-x-4">
        <button type="button" onclick="window.history.back()" 
                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
            Cancel
        </button>
        <button type="submit" 
                class="bg-[#00446b] hover:bg-[#003355] text-white font-medium py-2 px-6 rounded-lg transition duration-200 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            Submit Application
        </button>
    </div>
</form>
    </div>

    <script>
        function toggleSettingsDropdown() {
            const dropdown = document.getElementById('settingsDropdown');
            dropdown.classList.toggle('hidden');
        }
    </script>
</body>
</html>