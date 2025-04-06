<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
  <!-- DaisyUI CDN -->
  <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet">
  <title>Admin Dashboard - Post a Job</title>
  <style>
    .sidebar-transition {
      transition: all 0.3s ease;
    }
    .main-content {
      margin-left: 16rem;
      padding-top: 4rem;
    }
    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
      }
    }
    .auto-resize {
      min-height: 100px;
      resize: none;
      overflow-y: hidden;
    }
  </style>
</head>
<body class="bg-gray-100 font-[Poppins] text-[#1e293b]">
<body class="bg-gray-50 font-[Poppins] text-gray-800">

 <!-- Navbar -->
 @include('admincomponent.nav-bar')

<div class="flex min-h-[calc(100vh-4rem)]"> <!-- Subtract navbar height -->
    <!-- Sidebar - Fixed width -->
    <div class="w-64 flex-shrink-0 bg-white border-r border-gray-200 shadow-sm">
        @include('admincomponent.side-bar')
    </div>


        <!-- Main Content -->
         
        <div class="flex-1 overflow-y-auto p-4">
      <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-8">

        <h2 class="text-3xl font-bold text-gray-800 mb-8">Post a New Job</h2>

        @if(session('success'))
          <div class="alert alert-success mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
          </div>
        @endif

        <form action="{{ route('admin.jobs.store') }}" method="POST" class="space-y-8">
          @csrf

          <!-- Grid Layout for Two Columns -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- Left Column -->
            <div class="space-y-6">
              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold">Job Title</span>
                </label>
                <input type="text" name="title" class="input input-bordered w-full" required>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold">Department</span>
                </label>
                <input type="text" name="department" class="input input-bordered w-full" required>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold">Job Type</span>
                </label>
                <select name="type" class="select select-bordered w-full" required>
                  <option disabled selected>Select job type</option>
                  <option>Full-time</option>
                  <option>Part-time</option>
                  <option>Contract</option>
                  <option>Internship</option>
                </select>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold">Location</span>
                </label>
                <input type="text" name="location" class="input input-bordered w-full" required>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold">Experience Level</span>
                </label>
                <input type="text" name="experience_level" class="input input-bordered w-full" required>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold">Application Deadline</span>
                </label>
                <input type="date" name="application_deadline" class="input input-bordered w-full" required>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold">Status</span>
                </label>
                <select name="status" class="select select-bordered w-full">
                  <option value="Active">Active</option>
                  <option value="Draft">Draft</option>
                </select>
              </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
              <div class="form-control">
                <label class="label">
                  <span class="label-text font-semibold">Salary Range</span>
                </label>
                <div class="flex gap-4">
                  <div class="flex-1">
                    <input type="number" name="min_salary" placeholder="Min Salary" class="input input-bordered w-full">
                  </div>
                  <div class="flex-1">
                    <input type="number" name="max_salary" placeholder="Max Salary" class="input input-bordered w-full">
                  </div>
                </div>
              </div>

              <div x-data="{ 
                description: '',
                responsibilities: '',
                qualifications: ''
              }">
                <div class="form-control">
                  <label class="label">
                    <span class="label-text font-semibold">Job Description</span>
                  </label>
                  <textarea name="description" x-model="description"
                    class="textarea textarea-bordered w-full auto-resize"
                    required></textarea>
                  <div class="mt-2 p-3 bg-gray-50 rounded-lg text-gray-700" x-html="description.replace(/\n/g, '<br>')"></div>
                </div>

                <div class="form-control mt-4">
                  <label class="label">
                    <span class="label-text font-semibold">Responsibilities</span>
                  </label>
                  <textarea name="responsibilities" x-model="responsibilities"
                    class="textarea textarea-bordered w-full auto-resize"
                    required></textarea>
                  <div class="mt-2 p-3 bg-gray-50 rounded-lg text-gray-700" x-html="responsibilities.replace(/\n/g, '<br>')"></div>
                </div>

                <div class="form-control mt-4">
                  <label class="label">
                    <span class="label-text font-semibold">Qualifications</span>
                  </label>
                  <textarea name="qualifications" x-model="qualifications"
                    class="textarea textarea-bordered w-full auto-resize"
                    required></textarea>
                  <div class="mt-2 p-3 bg-gray-50 rounded-lg text-gray-700" x-html="qualifications.replace(/\n/g, '<br>')"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end pt-6">
            <button type="submit" class="btn btn-primary px-8 py-3">Post Job</button>
          </div>
        </form>
      </div>
    </main>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Auto-resize textareas
      const textareas = document.querySelectorAll('.auto-resize');
      textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
          this.style.height = 'auto';
          this.style.height = (this.scrollHeight) + 'px';
        });
        // Trigger initial resize
        textarea.dispatchEvent(new Event('input'));
      });
    });
  </script>
</body>
</html>