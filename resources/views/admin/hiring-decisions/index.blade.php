<!DOCTYPE html>
<html lang="en">
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
  </style>
</head>
<body class="bg-gray-100 font-[Poppins] text-[#1e293b]">

  <!-- Navbar -->
  <div class="fixed top-0 left-0 right-0 z-50">
    @include('admincomponent.nav-bar')
  </div>

  <!-- Main Layout -->
  <div class="flex pt-16 min-h-screen">
    <!-- Sidebar -->
    <div class="fixed left-0 top-16 h-[calc(100vh-4rem)] w-64 bg-white border-r border-gray-200 shadow-sm sidebar-transition z-40">
      @include('admincomponent.side-bar')
    </div>

    <!-- Main Content -->
    <main class="flex-1 ml-64 p-6 overflow-y-auto">
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-gray-800">Hiring Decisions</h1>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidate</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hire Date</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved By</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @forelse($decisions as $decision)
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $decision->candidate->full_name }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">{{ $decision->position }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">{{ $decision->department }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">
                      @if($decision->hire_date instanceof \Carbon\Carbon)
                        {{ $decision->hire_date->format('M d, Y') }}
                      @else
                        {{ \Carbon\Carbon::parse($decision->hire_date)->format('M d, Y') }}
                      @endif
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">{{ $decision->approver->name }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('admin.hiring-decisions.show', $decision) }}" 
                       class="text-indigo-600 hover:text-indigo-900 hover:underline">
                       View
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                    No hiring decisions recorded
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

</body>
</html>