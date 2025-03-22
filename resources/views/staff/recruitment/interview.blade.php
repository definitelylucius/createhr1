<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Tailwind & DaisyUI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <title>Interview Panel | Staff Dashboard</title>
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
                <h1 class="text-3xl font-bold text-[#00446b]">Interview Panel</h1>
                <p class="text-gray-600">Manage interview invitations and recommendations.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Applicant List -->
                <div class="bg-white p-6 shadow-lg rounded-xl">
                    <h3 class="text-lg font-semibold text-[#00446b] mb-4">Applicants for Interview</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-300 rounded-lg shadow-md">
                            <thead>
                                <tr class="bg-[#00446b] text-white">
                                    <th class="p-4 text-left text-sm font-semibold">Name</th>
                                    <th class="p-4 text-left text-sm font-semibold">Job Title</th>
                                    <th class="p-4 text-left text-sm font-semibold">Status</th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-300">
                                @foreach($applications as $application)
                                    <tr class="hover:bg-indigo-50 transition duration-200 cursor-pointer"
                                        onclick="fillForm(
                                            '{{ $application->id }}',
                                            '{{ $application->user->name }}', 
                                            '{{ $application->job->title ?? 'N/A' }}', 
                                            '{{ $application->user->email }}', 
                                            '{{ $application->status }}', 
                                            '{{ asset('storage/' . ($application->resume ?? '')) }}')">
                                        <td class="p-4 text-gray-800">{{ $application->user->name }}</td>
                                        <td class="p-4 text-gray-600">{{ $application->job->title ?? 'N/A' }}</td>
                                        <td class="p-4">
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold 
                                                {{ $application->status == 'new_application' ? 'bg-yellow-200 text-yellow-800' :
                                                ($application->status == 'approved_for_interview' ? 'bg-blue-200 text-blue-800' : 
                                                ($application->status == 'interviewed' ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-600')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                            </span>
                                        </td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Applicant Details -->
                <div class="bg-white p-6 shadow-lg rounded-xl">
                    <h3 class="text-lg font-semibold text-[#00446b] mb-4">Applicant Details</h3>
                    
                    <input type="hidden" id="application-id">

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
                        <a href="#" id="resume-link" class="text-blue-500 hover:text-blue-700 font-medium" target="_blank">
                            View Resume
                        </a>
                    </div>

                    <!-- Interview Invitation Form -->
                    <div class="mt-6 bg-white p-4 shadow-md rounded-md">
                        <h3 class="text-lg font-semibold mb-2">Send Interview Invitation</h3>
                        <form action="{{ route('staff.recruitment.sendInterviewEmail', $application->id) }}" method="POST">
    @csrf
                            <label class="block text-sm font-medium">Subject:</label>
                            <input type="text" name="subject" class="w-full p-2 border rounded-md" required>

                            <label class="block text-sm font-medium mt-2">Message:</label>
                            <textarea name="message" class="w-full p-2 border rounded-md" required></textarea>

                            <button type="submit" class="mt-2 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                                📩 Send Invitation
                            </button>
                        </form>
                    </div>

                    <!-- Complete Interview Form -->
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-2">Complete Interview</h3>
                        <form id="complete-interview-form" action="" method="POST">
                            @csrf

                            <select name="interview_status" class="w-full p-2 border rounded-md">
                                <option value="passed">Passed</option>
                                <option value="failed">Failed</option>
                            </select>

                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-md mt-2">
                                Submit Interview Result
                            </button>
                        </form>
                    </div>

                    <!-- Recommendation Form -->
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-2">Recommend for Hiring</h3>
                        <form id="recommend-form" action="" method="POST">
                            @csrf
                            <select name="outcome_status" class="w-full p-2 border rounded-md">
                                <option value="recommended_for_hiring">Recommended for Hiring</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-6 rounded-md mt-2">
                                Submit Recommendation
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
    function fillForm(id, name, job, email, status, resume) {
        // Set the form action dynamically for interview form
        document.getElementById("complete-interview-form").action = `/staff/recruitment/interview/${id}/store`;

        // Set the applicant's details
        document.getElementById("application-id").value = id;
        document.getElementById("applicant-name").value = name;
        document.getElementById("applicant-job").value = job;
        document.getElementById("applicant-email").value = email;
        document.getElementById("resume-link").href = resume || '#';

        // Set the recommendation form action dynamically
        document.getElementById("recommend-form").action = `/staff/applicant/${id}/recommend`;
    }
    </script>
</body>
</html>
