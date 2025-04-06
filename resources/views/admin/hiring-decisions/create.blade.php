<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
  <!-- DaisyUI CDN -->
  <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Dashboard - Bus Transportation</title>
  <style>
    .sidebar-transition {
      transition: all 0.3s ease;
    }
    .main-content {
      margin-left: 16rem; /* matches sidebar width */
      padding-top: 4rem; /* matches navbar height */
    }
    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body class="bg-gray-100 font-[Poppins] text-[#1e293b]">

  @include('admincomponent.nav-bar')

  <!-- Main Layout -->
  <div class="flex min-h-screen">
    @include('admincomponent.side-bar')

    <!-- Main Content Area -->
    <main class="main-content flex-1 p-6">
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-gray-800">Create Hiring Decision for {{ $candidate->full_name }}</h1>
        </div>

        <form action="{{ route('admin.hiring-decisions.store', $candidate) }}" method="POST">
          @csrf

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
              <input type="text" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                     id="position" name="position" required>
            </div>
            <div>
              <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
              <input type="text" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                     id="department" name="department" required>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-1">Hire Date</label>
              <input type="date" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                     id="hire_date" name="hire_date" required>
            </div>
            <div>
              <label for="salary" class="block text-sm font-medium text-gray-700 mb-1">Salary</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
                <input type="number" step="0.01" class="input input-bordered w-full pl-8 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                       id="salary" name="salary" required>
              </div>
            </div>
          </div>

          <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea class="textarea textarea-bordered w-full h-32 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                      id="notes" name="notes" rows="3"></textarea>
          </div>

          <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.candidates.index') }}" class="btn btn-ghost hover:bg-gray-100">
              Cancel
            </a>
            <button type="submit" class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white">
              Create Hiring Decision
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>

</body>
</html>