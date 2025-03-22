<!DOCTYPE html>
<html lang="en">
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
</head>
<body class="bg-gray-100 font-[Poppins] text-[#1e293b]">

  <!-- Navbar -->
  @include('admincomponent.nav-bar')

  <div class="flex">
    <!-- Sidebar -->
    @include('admincomponent.side-bar')

    <!-- Main Content -->
    <div class="flex-1 px-8 py-6">
      <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-6">

        <h2 class="text-3xl font-bold text-gray-800 mb-6">Post a New Job</h2>

        @if(session('success'))
          <div class="alert alert-success text-green-700 bg-green-100 p-3 rounded-lg mb-4">
            {{ session('success') }}
          </div>
        @endif

        <form action="{{ route('admin.jobs.store') }}" method="POST" class="space-y-6">
          @csrf

          <!-- Grid Layout for Two Columns -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Left Column -->
            <div class="space-y-4">
              <div>
                <label class="label font-semibold">Job Title</label>
                <input type="text" name="title" class="input input-bordered bg-white w-full" required>
              </div>

              <div>
                <label class="label font-semibold">Department</label>
                <input type="text" name="department" class="input input-bordered bg-white w-full" required>
              </div>

              <div>
                <label class="label font-semibold">Job Type</label>
                <select name="type" class="select select-bordered bg-white w-full" required>
                  <option>Full-time</option>
                  <option>Part-time</option>
                  <option>Contract</option>
                  <option>Internship</option>
                </select>
              </div>

              <div>
                <label class="label font-semibold">Location</label>
                <input type="text" name="location" class="input input-bordered bg-white w-full" required>
              </div>

              <div>
                <label class="label font-semibold">Experience Level</label>
                <input type="text" name="experience_level" class="input input-bordered bg-white w-full" required>
              </div>

              <div>
                <label class="label font-semibold">Application Deadline</label>
                <input type="date" name="application_deadline" class="input input-bordered bg-white w-full" required>
              </div>

              <div>
                <label class="label font-semibold">Status</label>
                <select name="status" class="select select-bordered bg-white w-full">
                  <option value="Active">Active</option>
                  <option value="Draft">Draft</option>
                </select>
              </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
              <div>
                <label class="label font-semibold">Salary Range</label>
                <div class="flex gap-2">
                  <input type="number" name="min_salary" placeholder="Min Salary" class="input input-bordered bg-white w-1/2">
                  <input type="number" name="max_salary" placeholder="Max Salary" class="input input-bordered bg-white w-1/2">
                </div>
              </div>

              <div x-data="{ 
    selectedJob: { 
        description: '{{ addslashes($job->description ?? '') }}'.replace(/<br\s*\/?>/g, '\n'),
        responsibilities: '{{ addslashes($job->responsibilities ?? '') }}'.replace(/<br\s*\/?>/g, '\n'),
        qualifications: '{{ addslashes($job->qualifications ?? '') }}'.replace(/<br\s*\/?>/g, '\n')
    }
}">
    <label class="label font-semibold">Job Description</label>
    <textarea name="description"
        class="textarea textarea-bordered bg-white w-full min-h-24 overflow-auto resize-y whitespace-pre-wrap"
        required x-model="selectedJob.description"></textarea>

    <div class="p-3 bg-gray-100 rounded-lg text-gray-800">
        <div x-html="selectedJob.description.replace(/\n/g, '<br>')"></div>
    </div>

    <label class="label font-semibold">Responsibilities</label>
    <textarea name="responsibilities"
        class="textarea textarea-bordered bg-white w-full min-h-24 overflow-auto resize-y whitespace-pre-wrap"
        required x-model="selectedJob.responsibilities"></textarea>

    <div class="p-3 bg-gray-100 rounded-lg text-gray-800">
        <div x-html="selectedJob.responsibilities.replace(/\n/g, '<br>')"></div>
    </div>

    <label class="label font-semibold">Qualifications</label>
    <textarea name="qualifications"
        class="textarea textarea-bordered bg-white w-full min-h-24 overflow-auto resize-y whitespace-pre-wrap"
        required x-model="selectedJob.qualifications"></textarea>

    <div class="p-3 bg-gray-100 rounded-lg text-gray-800">
        <div x-html="selectedJob.qualifications.replace(/\n/g, '<br>')"></div>
    </div>
</div>



          <!-- Submit Button -->
          <div class="flex justify-end">
            <button type="submit" class="btn btn-primary px-6">Post Job</button>
          </div>

        </form>
      </div>
    </div>

  </div>
  <script>
  document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll("textarea").forEach(textarea => {
          textarea.addEventListener("input", function() {
              this.style.height = "auto";
              this.style.height = this.scrollHeight + "px";
          });
      });
  });
</script>

</body>
</html>


