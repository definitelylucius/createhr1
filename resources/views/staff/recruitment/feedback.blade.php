<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Tailwind & DaisyUI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@1.17.0/dist/full.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <title>Staff Dashboard - Track Job Applications</title>
</head>

<body class="bg-gray-100 font-[Poppins]">

    <!-- Navbar -->
    @include('staffcomponent.nav-bar')

    <div class="flex">
        <!-- Sidebar -->
        @include('staffcomponent.side-bar')

        <!-- Main Content -->
        <div class="flex-1 min-h-screen p-6">
            
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h1 class="text-3xl font-bold text-[#00446b]">Track Job Applications</h1>
                <p class="text-gray-600">Manage and update applicant statuses</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Applicant List -->
                <div class="bg-white p-6 shadow-lg rounded-xl">
                    <h3 class="text-lg font-semibold text-[#00446b] mb-4">Applicant List</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-300 rounded-lg shadow-md">
                            <thead>
                                <tr class="bg-[#00446b] text-white">
                                    <th class="p-4 text-left text-sm font-semibold rounded-tl-lg">Name</th>
                                    <th class="p-4 text-left text-sm font-semibold">Job Title</th>
                                    <th class="p-4 text-left text-sm font-semibold rounded-tr-lg">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-300">
                                @if($applications->isEmpty())
                                    <tr>
                                        <td colspan="3" class="p-4 text-center text-gray-500">No applicants yet.</td>
                                    </tr>
                                @else
                                    @foreach($applications as $application)
                                    <tr class="hover:bg-indigo-50 transition duration-200 cursor-pointer"
                                        onclick="fillForm(
                                            '{{ $application->user->name }}', 
                                            '{{ $application->user->email }}', 
                                            '{{ $application->job->title }}', 
                                            '{{ asset('storage/' . $application->resume) }}',
                                            '{{ $application->id }}'
                                        )">
                                        <td class="p-4 text-gray-800">{{ $application->user->name }}</td>
                                        <td class="p-4 text-gray-600">{{ $application->job->title }}</td>
                                        <td class="p-4">
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold 
                                                {{ $application->application_status == 'for_admin_review' ? 'bg-yellow-200 text-yellow-800' : 
                                                ($application->application_status == 'rejected' ? 'bg-red-200 text-red-800' : 
                                                'bg-green-200 text-green-800') }}">
                                                {{ ucfirst(str_replace('_', ' ', $application->application_status)) }}
                                            </span>
                                        </td>
                                    </tr> 
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Update Form & Applicant Details -->
                <div class="bg-white p-6 shadow-lg rounded-xl">
                    <h3 class="text-lg font-semibold text-[#00446b] mb-4">Applicant Details</h3>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">Name:</label>
                        <input type="text" id="applicant-name" class="w-full p-3 border rounded-md bg-gray-100" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">Job Applied For:</label>
                        <input type="text" id="applicant-job" class="w-full p-3 border rounded-md bg-gray-100" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">Email:</label>
                        <input type="email" id="applicant-email" class="w-full p-3 border rounded-md bg-gray-100" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">Resume:</label>
                        <button id="resume-button" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition hidden" onclick="showResumeModal()">
                            View Resume
                        </button>
                    </div>
                    <h3 class="text-lg font-semibold text-[#00446b] mb-2">Update Status</h3>
                    <form id="update-status-form" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="application_id" id="hidden-application-id">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium">Status:</label>
                            <select class="w-full p-3 border rounded-md bg-gray-100" name="application_status" id="applicant-status" required>
                                <option value="for_admin_review">For Admin Review</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-[#00446b] text-white py-3 rounded-md">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
function fillForm(name, email, job, resumeUrl, applicationId) {
    // Set the values of the fields dynamically
    document.getElementById('applicant-name').value = name;
    document.getElementById('applicant-job').value = job;
    document.getElementById('applicant-email').value = email;
    document.getElementById('hidden-application-id').value = applicationId;

    // Update the form action to target the correct application
    document.getElementById('update-status-form').action = `/staff/applications/${applicationId}/update-status`;

    // Display the resume button
    let resumeButton = document.getElementById('resume-button');
    resumeButton.classList.remove('hidden');
    resumeButton.onclick = function() {
        window.open(resumeUrl, '_blank');
    };
}
        
    </script>
</body>
</html>





