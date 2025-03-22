<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@1.17.0/dist/full.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hiring Applicants | Admin Dashboard</title>
</head>
<body class="bg-gray-100 font-[Poppins]">

@include('admincomponent.nav-bar')

<div class="flex min-h-screen">
    @include('admincomponent.side-bar')

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
                                @if($application->status === 'recommended_for_hiring') 
                                    <tr class="hover:bg-indigo-50 transition duration-200 cursor-pointer"
                                        onclick="toggleApplicantSelection('{{ $application->id }}', this)">
                                        <td class="p-4 text-gray-800">{{ $application->name }}</td>
                                        <td class="p-4 text-gray-600">{{ $application->job->title }}</td>
                                        <td class="p-4 text-gray-600">
                                            {{ optional($application->reviewer)->name ?? 'Not Reviewed Yet' }}
                                        </td>
                                        <td class="p-4">
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-200 text-green-800">
                                                {{ $application->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endif
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
                 <!-- Hire Applicants -->
        <div class="bg-white p-6 shadow-lg rounded-xl">
            <h3 class="text-lg font-semibold text-[#00446b] mb-4">Hire Selected Applicants</h3>

            <!-- Hidden Field to Store Selected Applicant IDs -->
            <form method="POST" id="hireForm">
                @csrf
                @method('POST')

                <!-- Hidden Field to Store Selected Applicant IDs -->
                <input type="hidden" name="selected_applicants" id="selectedApplicants">

                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Hired
                </button>
            </form>
        </div>
    </div>
            </div>
        </div>
        
        

    <script>
    let selectedApplicants = [];

    // Toggle applicant selection when clicked
    function toggleApplicantSelection(applicantId, row) {
        // If applicant is already selected, remove it from the array
        if (selectedApplicants.includes(applicantId)) {
            selectedApplicants = selectedApplicants.filter(id => id !== applicantId);
            row.classList.remove('bg-indigo-100');  // Remove highlight
        } else {
            // If not selected, add it to the array
            selectedApplicants.push(applicantId);
            row.classList.add('bg-indigo-100');  // Highlight row
        }

        // Update hidden field with selected applicant IDs
        document.getElementById('selectedApplicants').value = selectedApplicants.join(',');
    }

    // Set form action dynamically when the form is submitted
    document.getElementById('hireForm').onsubmit = function() {
        let applicantIds = document.getElementById('selectedApplicants').value;
        if (!applicantIds) {
            alert("Please select at least one applicant.");
            return false; // Prevent form submission if no applicants are selected
        }
        this.action = `/admin/applicants/hire/${applicantIds}`;
    };
</script>


</div>
</body>
</html>
