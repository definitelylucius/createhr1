<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Tailwind & DaisyUI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@1.17.0/dist/full.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard - Review Applications</title>
</head>

<body class="bg-gray-100 font-[Poppins]">

    <!-- Navbar -->
    @include('admincomponent.nav-bar')

    <div class="flex">
        <!-- Sidebar -->
        @include('admincomponent.side-bar')

        <!-- Main Content -->
        <div class="flex-1 min-h-screen p-6">
            
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h1 class="text-3xl font-bold text-[#00446b]">Admin Review - Job Applications</h1>
                <p class="text-gray-600">Applications reviewed by staff that require admin approval.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Applicant List -->
                <div class="bg-white p-6 shadow-lg rounded-xl">
                    <h3 class="text-lg font-semibold text-[#00446b] mb-4">Applicants Pending Admin Review</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-300 rounded-lg shadow-md">
                            <thead>
                                <tr class="bg-[#00446b] text-white">
                                    <th class="p-4 text-left text-sm font-semibold">Name</th>
                                    <th class="p-4 text-left text-sm font-semibold">Job Title</th>
                                    <th class="p-4 text-left text-sm font-semibold">Reviewed By</th>
                                    <th class="p-4 text-left text-sm font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-300">
                                @foreach($applications as $application)
                                    <tr class="hover:bg-indigo-50 transition duration-200 cursor-pointer"
                                        onclick="fillForm(
                                            '{{ $application->id }}',
                                            '{{ $application->user->name }}',
                                            '{{ $application->job->title }}',
                                            '{{ $application->user->email }}',
                                            '{{ $application->application_status }}',
                                            '{{ optional($application->reviewer)->name ?? 'Not Reviewed Yet' }}'
                                        )">
                                        <td class="p-4 text-gray-800">{{ $application->user->name }}</td>
                                        <td class="p-4 text-gray-600">{{ $application->job->title }}</td>
                                        <td class="p-4 text-gray-600">
                                            {{ optional($application->reviewer)->name ?? 'Not Reviewed Yet' }}
                                        </td>
                                        <td class="p-4">
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold bg-yellow-200 text-yellow-800">
                                                {{ ucfirst(str_replace('_', ' ', $application->application_status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Update Form & Applicant Details -->
                <div class="bg-white p-6 shadow-lg rounded-xl">
                    <h3 class="text-lg font-semibold text-[#00446b] mb-4">Applicant Details</h3>
                    
                    <!-- Hidden input to store current applicant id -->
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
                        <label class="block text-gray-700 font-medium">Reviewed By:</label>
                        <input type="text" id="reviewed-by" class="w-full p-3 border rounded-md bg-gray-100" readonly>
                    </div>

                    <!-- Status Update Form -->
                    <form id="update-status-form" method="POST">
                        @csrf
                        @method('PUT') <!-- Ensure the request is sent as PUT -->

                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium">Update Status:</label>
                            <select class="w-full p-3 border rounded-md bg-gray-100" name="application_status" id="applicant-status" required>
                                <option value="interview_scheduled">Schedule Interview</option>
                                <option value="rejected">Reject</option>
                            </select>
                        </div>

                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function fillForm(applicationId, name, job, email, status, reviewedBy) {
        document.getElementById('applicant-name').value = name;
        document.getElementById('applicant-job').value = job;
        document.getElementById('applicant-email').value = email;
        document.getElementById('reviewed-by').value = reviewedBy;
        document.getElementById('application-id').value = applicationId;
        
        // Update form action dynamically
        document.getElementById('update-status-form').action = `/admin/applications/${applicationId}/update-status`;

        // Update status dropdown to reflect current status
        document.getElementById('applicant-status').value = status;
    }
    </script>

</body>
</html>
