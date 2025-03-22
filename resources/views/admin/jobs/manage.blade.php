<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">


    <!-- DaisyUI -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet">

    <title>Manage Job Listings</title>
</head>
<body class="bg-gray-100 font-[Poppins] text-[#1e293b]">

    <!-- Navbar -->
    @include('admincomponent.nav-bar')

    <div class="flex">
        <!-- Sidebar -->
        @include('admincomponent.side-bar')

        <!-- Main Content -->
        <div class="p-6 w-full flex gap-4">
            <!-- Left Panel: Job Listings -->
            <div class="w-1/3 bg-white shadow-md rounded-lg p-4">
                <h2 class="text-xl font-semibold mb-4">Jobs List</h2>

                <ul class="space-y-2">
                    @foreach($jobs as $job)
                    <li class="border p-3 rounded cursor-pointer hover:bg-gray-200"
                    onclick="showJobDetails(
    '{{ $job->id }}',
    '{{ addslashes($job->title) }}',
    '{{ addslashes($job->department) }}',
    '{{ addslashes($job->type) }}',
    '{{ addslashes($job->location) }}',
    '{{ addslashes($job->experience_level) }}',
    '{{ addslashes($job->application_deadline) }}',
    '{{ addslashes($job->min_salary) }}',
    '{{ addslashes($job->max_salary) }}',
    `{{ json_encode($job->description ?? '', JSON_HEX_APOS | JSON_HEX_QUOT) }}`,
    `{{ json_encode($job->responsibilities ?? '', JSON_HEX_APOS | JSON_HEX_QUOT) }}`,
    `{{ json_encode($job->qualifications?? '', JSON_HEX_APOS | JSON_HEX_QUOT) }}`,
    '{{ addslashes($job->status) }}'
)"

>
                        <strong>{{ $job->title }}</strong>
                        <p class="text-sm text-gray-500">{{ ucfirst($job->status) }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Right Panel: Job Details (Hidden by Default) -->
            <div id="jobEditContainer" class="w-2/3 bg-white shadow-md rounded-lg p-6 hidden">
                <h2 class="text-xl font-semibold mb-4">Job Details</h2>

                <form id="jobEditForm" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="editJobId" name="job_id">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label font-semibold">Job Title</label>
                            <input type="text" id="editJobTitle" name="title" class="input input-bordered bg-white w-full" required>
                        </div>

                        <div>
                            <label class="label font-semibold">Department</label>
                            <input type="text" id="editJobDepartment" name="department" class="input input-bordered bg-white w-full" required>
                        </div>

                        <div>
                            <label class="label font-semibold">Job Type</label>
                            <select id="editType" name="type" class="select select-bordered bg-white w-full" required>
                                <option>Full-time</option>
                                <option>Part-time</option>
                                <option>Contract</option>
                                <option>Internship</option>
                            </select>
                        </div>

                        <div>
                            <label class="label font-semibold">Location</label>
                            <input type="text" id="editJobLocation" name="location" class="input input-bordered bg-white w-full" required>
                        </div>

                        <div>
                            <label class="label font-semibold">Experience Level</label>
                            <input type="text" id="editJobExperience" name="experience_level" class="input input-bordered bg-white w-full" required>
                        </div>

                        <div>
                            <label class="label font-semibold">Application Deadline</label>
                            <input type="date" id="editJobDeadline" name="application_deadline" class="input input-bordered bg-white w-full" required>
                        </div>

                        <div>
                            <label class="label font-semibold">Status</label>
                            <select id="editJobStatus" name="status" class="select select-bordered bg-white w-full">
                                <option value="Active">Active</option>
                                <option value="Draft">Draft</option>
                            </select>
                        </div>

                        <div>
                            <label class="label font-semibold">Salary Range</label>
                            <div class="flex gap-2">
                                <input type="number" id="editJobMinSalary" name="min_salary" placeholder="Min Salary" class="input input-bordered bg-white w-1/2">
                                <input type="number" id="editJobMaxSalary" name="max_salary" placeholder="Max Salary" class="input input-bordered bg-white w-1/2">
                            </div>
                        </div>
                    </div>
                    <div>
    <label class="label font-semibold">Job Description</label>
    <textarea id="editJobDescription" name="description"
        class="textarea textarea-bordered bg-white w-full min-h-24" required>{!! old('description', preg_replace('/<br\s*\/?>/i', "\n", $job->description ?? '')) !!}</textarea>
</div>

<div>
    <label class="label font-semibold">Responsibilities</label>
    <textarea id="editJobResponsibilities" name="responsibilities"
        class="textarea textarea-bordered bg-white w-full min-h-24" required>{!! old('responsibilities', preg_replace('/<br\s*\/?>/i', "\n", $job->responsibilities ?? '')) !!}</textarea>
</div>

<div>
    <label class="label font-semibold">Qualifications</label>
    <textarea id="editJobQualifications" name="qualifications"
        class="textarea textarea-bordered bg-white w-full min-h-24" required>{!! old('qualifications', preg_replace('/<br\s*\/?>/i', "\n", $job->qualifications ?? '')) !!}</textarea>
</div>



<div class="flex justify-between mt-4">
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
        Save Changes
    </button>



                        <button type="button" onclick="deleteJob()"
                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">
                            Delete Job
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showJobDetails(id, title, department, type, location, experience_level, deadline, min_salary, max_salary, description, responsibilities, qualifications, status) {
            console.log("Job Data:", { id, title, department, type, location, experience_level, deadline, min_salary, max_salary, description, responsibilities, qualifications, status });

            document.getElementById('editJobId').value = id || '';
            document.getElementById('editJobTitle').value = title || '';
            document.getElementById('editJobDepartment').value = department || '';
            document.getElementById('editType').value = type || '';
            document.getElementById('editJobLocation').value = location || '';
            document.getElementById('editJobExperience').value = experience_level || '';
            document.getElementById('editJobDeadline').value = deadline || '';
            document.getElementById('editJobStatus').value = status || '';

            document.getElementById('editJobMinSalary').value = min_salary || '';
            document.getElementById('editJobMaxSalary').value = max_salary || '';

             document.getElementById('editJobDescription').value = description || '';
            document.getElementById('editJobResponsibilities').value = responsibilities || '';
            document.getElementById('editJobQualifications').value = qualifications || '';

            document.getElementById('jobEditContainer').classList.remove('hidden');
        }

        document.getElementById('jobEditForm').addEventListener('submit', function(event) {
    event.preventDefault();

    let jobId = document.getElementById('editJobId').value;

    if (!jobId) {
        alert("No job selected for updating.");
        return;
    }

    let formData = new FormData(this);
    
    fetch(`/admin/jobs/${jobId}`, {
        method: 'POST',  // Laravel doesn't support FormData with PUT, so use POST + _method
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log("Success:", data);
        alert("Job Updated Successfully!");
        location.reload();
    })
    .catch(error => console.error("Error:", error));
});

// DELETE JOB FUNCTION
function deleteJob() {
    let jobId = document.getElementById('editJobId').value;

    if (!jobId) {
        alert("No job selected for deletion.");
        return;
    }

    if (!confirm("Are you sure you want to delete this job?")) {
        return;
    }

    fetch(`/admin/jobs/${jobId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log("Job Deleted:", data);
        alert("Job Deleted Successfully!");
        location.reload();
    })
    .catch(error => console.error("Error:", error));
}
    </script>
</body>
</html>


