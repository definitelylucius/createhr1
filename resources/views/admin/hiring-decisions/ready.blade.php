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
          <h1 class="text-2xl font-bold text-gray-800">Candidates Ready for Hire</h1>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidate</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interview Date</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @forelse($candidates as $candidate)
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="text-sm font-medium text-gray-900">
                        {{ $candidate->full_name }}
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">{{ $candidate->job->title ?? 'N/A' }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">
                      {{ optional($candidate->finalInterview)->scheduled_at->format('M d, Y') ?? 'N/A' }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-4">
                      <a href="{{ route('admin.hiring-decisions.create', parameters: $candidate) }}" 
                         class="text-indigo-600 hover:text-indigo-900 hover:underline">
                         Create Hiring Decision
                      </a>
                      <a href="{{ route('admin.candidates.show', $candidate) }}" 
                         class="text-gray-600 hover:text-gray-900 hover:underline">
                         View Profile
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                    No candidates ready for hire at this time.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-6">
          {{ $candidates->links() }}
        </div>
      </div>
    </main>
  </div>

</body>
</html>