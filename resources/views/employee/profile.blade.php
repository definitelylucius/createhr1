    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- Tailwind & DaisyUI -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/daisyui@2.51.2/dist/full.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

        <title>Employee Profile - Onboarding</title>
    </head>
    <body class="bg-gray-100 font-[Poppins]">

        <!-- Navbar -->
        @include('employeecomponent.nav-bar')

        <div class="flex">
            <!-- Sidebar -->
            @include('employeecomponent.side-bar')

            <!-- Profile Section -->
            <div class="max-w-6xl mx-auto w-full bg-white p-6 shadow-md rounded-lg mt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- View Section (Left) -->
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Employee Profile</h2>

                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Display Fields -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-semibold">Name</label>
                                <p class="border border-gray-300 rounded-md p-2 bg-gray-100">{{ $user->name }}</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold">Email</label>
                                <p class="border border-gray-300 rounded-md p-2 bg-gray-100">{{ $user->email }}</p>
                            </div>

                        <!-- Phone -->
            <div>
                <label class="block text-gray-700 font-semibold">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="w-full border border-gray-300 rounded-md p-2" />
            </div>

            <!-- Address -->
            <div>
                <label class="block text-gray-700 font-semibold">Address</label>
                <textarea name="address" class="w-full h-32 border border-gray-300 rounded-md p-4 text-lg" rows="4">{{ old('address', $employee->address) }}</textarea>
            </div>

            <!-- Characteristics -->
            <div>
                <label class="block text-gray-700 font-semibold">Soft Skills</label>
                <textarea name="soft_skills" class="w-full h-32 border border-gray-300 rounded-md p-4 text-lg" rows="4">{{ old('soft_skills', $employee->soft_skills) }}</textarea>
            </div>

            <!-- Skills -->
            <div>
                <label class="block text-gray-700 font-semibold">Hard Skills</label>
                <textarea name="hard_skills" class="w-full h-32 border border-gray-300 rounded-md p-4 text-lg" rows="4">{{ old('hard_skills', $employee->hard_skills) }}</textarea>
            </div>

            <!-- Department -->
            <div>
                <label class="block text-gray-700 font-semibold">Department</label>
                <input type="text" name="department" value="{{ old('department', $employee->department) }}" class="w-full border border-gray-300 rounded-md p-2" />
            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold">Job Type</label>
                                <p class="border border-gray-300 rounded-md p-2 bg-gray-100">{{ $job->title ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold">Resume</label>
                                @if($resume)
        <button class="text-blue-500 hover:text-blue-700 font-medium"
            onclick="showResumeModal('{{ $user->name }}', '{{ $user->email }}', '{{ $job ? $job->title : 'No Job' }}', '{{ $resume }}')">
            View Resume
        </button>
    @else
        <p>No resume uploaded</p>
    @endif
                            </div>
                        </div>
                    </div>

                    <!-- Edit Section (Right) -->
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Edit Profile</h2>

                        <form method="POST" action="{{ route('employee.profile.update', $user->id) }}" class="space-y-4" enctype="multipart/form-data">

                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-gray-700 font-semibold">Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border border-gray-300 rounded-md p-2" />
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border border-gray-300 rounded-md p-2" />
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border border-gray-300 rounded-md p-2" />
                            </div>

                            <div>
        <label class="block text-gray-700 font-semibold">Address</label>
        <textarea name="address" class="w-full h-32 border border-gray-300 rounded-md p-4 text-lg" rows="4">{{ old('address', $employee->address) }}</textarea>
    </div>

    <div>
        <label class="block text-gray-700 font-semibold">Soft Skills</label>
        <textarea name="soft_skills" class="w-full h-32 border border-gray-300 rounded-md p-4 text-lg" rows="4">{{ old('soft_skills', $employee->soft_skills) }}</textarea>
    </div>

    <div>
        <label class="block text-gray-700 font-semibold">Hard Skills</label>
        <textarea name="hard_skills" class="w-full h-32 border border-gray-300 rounded-md p-4 text-lg" rows="4">{{ old('hard_skills', $employee->hard_skills) }}</textarea>
    </div>


                            <div class="mt-4">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Update Profile
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>

        <!-- Resume Modal -->
        <div id="resumeModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden">
        <div class="flex justify-center items-center min-h-screen">
            <div class="bg-white p-6 rounded-2xl shadow-lg w-3/4 max-w-2xl transform transition-all scale-95 opacity-0 animate-modal">
                <h3 class="text-xl font-bold text-center mb-4">Applicant Information</h3>
                
                <div id="applicantInfo" class="mb-4"></div>
                
                <div id="resumeContent" class="mb-4"></div>
                
                <button class="mt-4 w-full bg-red-500 text-white py-2 rounded-xl hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>


        <script>
            function showResumeModal(name, email, jobTitle, resumeUrl) {
        const modal = document.getElementById('resumeModal');
        const modalContent = modal.querySelector('div > div');

        // Remove `hidden` to make modal visible
        modal.classList.remove('hidden');

        // Add smooth open animation
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');

        // Populate modal content
        document.getElementById('applicantInfo').innerHTML = ` 
            <p><strong>Applicant Name:</strong> ${name}</p>
            <p><strong>Email:</strong> ${email}</p>
            <p><strong>Job Applied:</strong> ${jobTitle}</p>
        `;
        
        let fileExtension = resumeUrl.split('.').pop().toLowerCase();
        let resumeContent = '';

        if (fileExtension === 'pdf') {
            resumeContent = `<iframe src="${resumeUrl}" class="w-full h-96 rounded-xl" frameborder="0"></iframe>`;
        } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
            resumeContent = `<img src="${resumeUrl}" alt="Applicant Resume" class="w-full h-auto rounded-xl" />`;
        } else {
            resumeContent = `<p class="text-red-500">Unsupported file type</p>`;
        }

        document.getElementById('resumeContent').innerHTML = resumeContent;
    }

    function closeModal() {
        const modal = document.getElementById('resumeModal');
        const modalContent = modal.querySelector('div > div');

        // Add close animation
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        // Hide modal after animation
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

        </script>

    <style>
        @keyframes modal {
            0% { transform: scale(0.95); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .animate-modal {
            animation: modal 0.3s ease-out forwards;
        }
    </style>
    </body>
    </html>
