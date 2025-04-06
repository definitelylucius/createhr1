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
  <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Hiring Decision - Bus Transportation</title>
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
        <!-- Back button -->
        <div class="mb-6">
          <a href="{{ route('admin.hiring-decisions.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fi fi-sr-arrow-left mr-2"></i> Back to Hiring Decisions
          </a>
        </div>

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-gray-800">Hiring Decision Details</h1>
          <span class="badge bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
            Active
          </span>
        </div>

        <!-- Decision Details Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
          <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
              <i class="fi fi-sr-user-check"></i>
              Candidate Information
            </h2>
          </div>
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Candidate</h3>
                <p class="text-lg font-medium">{{ $decision->candidate->full_name }}</p>
              </div>
              <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Approved By</h3>
                <p class="text-lg font-medium">{{ $decision->approver->name }}</p>
              </div>
              <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Position</h3>
                <p class="text-lg font-medium">{{ $decision->position }}</p>
              </div>
              <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Department</h3>
                <p class="text-lg font-medium">{{ $decision->department }}</p>
              </div>
              <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Hire Date</h3>
                <p class="text-lg font-medium">
                  @if($decision->hire_date instanceof \Carbon\Carbon)
                    {{ $decision->hire_date->format('M d, Y') }}
                  @else
                    {{ \Carbon\Carbon::parse($decision->hire_date)->format('M d, Y') }}
                  @endif
                </p>
              </div>
              <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Salary</h3>
                <p class="text-lg font-medium">${{ number_format($decision->salary, 2) }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Notes Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
          <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
              <i class="fi fi-sr-notes"></i>
              Decision Notes
            </h2>
          </div>
          <div class="p-6">
            @if($decision->notes)
              <div class="prose max-w-none">
                {!! nl2br(e($decision->notes)) !!}
              </div>
            @else
              <p class="text-gray-500 italic">No notes provided for this decision.</p>
            @endif
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-end space-x-4">
          <a href="{{ route('admin.hiring-decisions.edit', $decision) }}" 
             class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white">
            <i class="fi fi-sr-pencil mr-2"></i> Edit Decision
          </a>
          <form action="{{ route('admin.hiring-decisions.destroy', $decision) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-ghost text-red-600 hover:bg-red-50" 
                    onclick="return confirm('Are you sure you want to delete this hiring decision?')">
              <i class="fi fi-sr-trash mr-2"></i> Delete
            </button>
          </form>
        </div>
      </div>
    </main>
  </div>

</body>
</html>