<!DOCTYPE html>
<html lang="en" data-theme="light">
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
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <title>Manage Job Listings</title>
    <style>
        .auto-resize {
            min-height: 100px;
            resize: none;
            overflow-y: hidden;
        }
        .job-item:hover {
            background-color: #f3f4f6;
            transform: translateY(-1px);
        }
        .job-item {
            transition: all 0.2s ease;
        }
    </style>
</head>
<body class="bg-gray-50 font-[Poppins] text-gray-800">

@include('layouts.partials.admin-navbar')
@include('layouts.partials.admin-sidebar')
 <!-- Main Content Area -->
 <div class="flex-1 overflow-y-auto lg:ml-64 transition-all duration-200 bg-gray-50">
 
            

        <!-- Main Content -->
         
        <div class="flex-1 overflow-y-auto p-4">
            <div class="flex gap-6 h-full">
                <!-- Left Panel: Job Listings -->
                <div class="w-1/3 bg-white rounded-lg shadow-sm p-4 overflow-y-auto">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">Job Listings</h2>

                    <ul class="space-y-3">
                        @foreach($jobs as $job)
                        <li class="border border-gray-200 p-4 rounded-lg job-item cursor-pointer transition-all"
                            onclick="showJobDetails(
                                '{{ $job->id }}',
                                '{{ addslashes(string: $job->title) }}',
                                '{{ addslashes($job->department) }}',
                                '{{ addslashes($job->type) }}',
                                '{{ addslashes($job->location) }}',
                                '{{ addslashes($job->experience_level) }}',
                                '{{ $job->application_deadline }}',
                                '{{ $job->min_salary }}',
                                '{{ $job->max_salary }}',
                                `{!! addslashes(str_replace(["\r", "\n"], '', $job->description)) !!}`,
                                `{!! addslashes(str_replace(["\r", "\n"], '', $job->responsibilities)) !!}`,
                                `{!! addslashes(str_replace(["\r", "\n"], '', $job->qualifications)) !!}`,
                                '{{ $job->status }}'
                            )">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium text-gray-800">{{ $job->title }}</h3>
                                    <p class="text-sm text-gray-500">{{ $job->department }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $job->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </div>
                            <div class="mt-2 flex justify-between text-sm text-gray-500">
                                <span>{{ $job->type }}</span>
                                <span>{{ $job->location }}</span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Right Panel: Job Details (Hidden by Default) -->
                <div id="jobEditContainer" class="w-2/3 bg-white rounded-lg shadow-sm p-6 hidden">
                    <h2 class="text-xl font-semibold mb-6 text-gray-700">Edit Job</h2>

                    <form id="jobEditForm" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <input type="hidden" id="editJobId" name="job_id">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Job Title</span>
                                </label>
                                <input type="text" id="editJobTitle" name="title" class="input input-bordered w-full" required>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Department</span>
                                </label>
                                <input type="text" id="editJobDepartment" name="department" class="input input-bordered w-full" required>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Job Type</span>
                                </label>
                                <select id="editType" name="type" class="select select-bordered w-full" required>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Internship">Internship</option>
                                </select>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Location</span>
                                </label>
                                <input type="text" id="editJobLocation" name="location" class="input input-bordered w-full" required>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Experience Level</span>
                                </label>
                                <input type="text" id="editJobExperience" name="experience_level" class="input input-bordered w-full" required>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Application Deadline</span>
                                </label>
                                <input type="date" id="editJobDeadline" name="application_deadline" class="input input-bordered w-full" required>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Status</span>
                                </label>
                                <select id="editJobStatus" name="status" class="select select-bordered w-full">
                                    <option value="Active">Active</option>
                                    <option value="Draft">Draft</option>
                                </select>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Salary Range</span>
                                </label>
                                <div class="flex gap-4">
                                    <input type="number" id="editJobMinSalary" name="min_salary" placeholder="Min" class="input input-bordered w-full">
                                    <input type="number" id="editJobMaxSalary" name="max_salary" placeholder="Max" class="input input-bordered w-full">
                                </div>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Job Description</span>
                            </label>
                            <textarea id="editJobDescription" name="description" class="textarea textarea-bordered w-full auto-resize" required></textarea>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Responsibilities</span>
                            </label>
                            <textarea id="editJobResponsibilities" name="responsibilities" class="textarea textarea-bordered w-full auto-resize" required></textarea>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Qualifications</span>
                            </label>
                            <textarea id="editJobQualifications" name="qualifications" class="textarea textarea-bordered w-full auto-resize" required></textarea>
                        </div>

                        <div class="flex justify-end gap-4 pt-4">
                            <button type="button" onclick="deleteJob()" class="btn btn-error">
                                Delete Job
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-resize textareas
        document.querySelectorAll('.auto-resize').forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            // Trigger initial resize
            textarea.dispatchEvent(new Event('input'));
        });

        function showJobDetails(id, title, department, type, location, experience_level, deadline, min_salary, max_salary, description, responsibilities, qualifications, status) {
            // Fill form fields
            document.getElementById('editJobId').value = id;
            document.getElementById('editJobTitle').value = title;
            document.getElementById('editJobDepartment').value = department;
            document.getElementById('editType').value = type;
            document.getElementById('editJobLocation').value = location;
            document.getElementById('editJobExperience').value = experience_level;
            document.getElementById('editJobDeadline').value = deadline;
            document.getElementById('editJobStatus').value = status;
            document.getElementById('editJobMinSalary').value = min_salary;
            document.getElementById('editJobMaxSalary').value = max_salary;
            document.getElementById('editJobDescription').value = description.replace(/<br\s*\/?>/gi, '\n');
            document.getElementById('editJobResponsibilities').value = responsibilities.replace(/<br\s*\/?>/gi, '\n');
            document.getElementById('editJobQualifications').value = qualifications.replace(/<br\s*\/?>/gi, '\n');

            // Resize textareas
            document.querySelectorAll('.auto-resize').forEach(ta => {
                ta.dispatchEvent(new Event('input'));
            });

            // Show the edit container
            document.getElementById('jobEditContainer').classList.remove('hidden');
        }

        document.getElementById('jobEditForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const jobId = document.getElementById('editJobId').value;
            const formData = new FormData(this);
            
            try {
                const response = await fetch(`/admin/jobs/${jobId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-HTTP-Method-Override': 'PUT'
                    }
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    alert('Job updated successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to update job'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating the job');
            }
        });

        async function deleteJob() {
            const jobId = document.getElementById('editJobId').value;
            
            if (!confirm('Are you sure you want to delete this job?')) {
                return;
            }
            
            try {
                const response = await fetch(`/admin/jobs/${jobId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    alert('Job deleted successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to delete job'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while deleting the job');
            }
        }

          // Dropdown menu functionality
          document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.dropdown').forEach(dropdown => {
                const btn = dropdown.querySelector('button');
                const menu = dropdown.querySelector('.dropdown-menu');

                if (btn && menu) {
                    btn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        // Close other dropdowns first
                        document.querySelectorAll('.dropdown-menu').forEach(m => {
                            if (m !== menu) m.classList.add('hidden');
                        });
                        // Toggle this one
                        menu.classList.toggle('hidden');
                    });
                }
            });

            // Global click handler to close all dropdowns
            document.addEventListener('click', function () {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            });
        });
    </script>
</body>
</html>