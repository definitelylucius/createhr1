<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Candidate Approvals | Bus Transportation</title>
  <style>
    .status-badge {
      @apply badge badge-sm font-medium gap-1;
    }
    .avatar-initials {
      @apply w-8 h-8 flex items-center justify-center rounded-full text-white font-medium text-sm;
    }
  </style>
</head>
<body class="bg-gray-100 font-[Poppins]">

    <!-- Navbar -->
    @include('admincomponent.nav-bar')

    <div class="flex min-h-[calc(100vh-4rem)]"> <!-- Subtract navbar height -->
        <!-- Sidebar - Fixed width -->
        <div class="w-64 flex-shrink-0 bg-white border-r border-gray-200 shadow-sm">
            @include('admincomponent.side-bar')
        </div>

        <!-- Main Content - Flexible width -->
        <div class="flex-1 overflow-y-auto p-4">


<div class="container mx-auto p-4 lg:p-6">
  <div class="card bg-base-100 shadow-lg">
    <div class="card-body p-0">
      <div class="p-6 pb-2">
        <h2 class="card-title text-2xl font-bold">Candidate Approvals</h2>
        <p class="text-sm text-gray-500 mt-1">{{ $candidates->total() }} candidates pending review</p>
      </div>
      
      <div class="overflow-x-auto">
        <table class="table">
          <!-- head -->
          <thead>
            <tr class="bg-base-200">
              <th class="font-semibold">Candidate</th>
              <th class="font-semibold">Status</th>
              <th class="font-semibold">Tags</th>
              <th class="font-semibold">License</th>
              <th class="font-semibold">Tests</th>
              <th class="font-semibold">Notes</th>
              <th class="font-semibold text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($candidates as $candidate)
            <tr class="hover:bg-base-100/70 border-b border-base-200">
              <!-- Candidate Column -->
              <td>
                <div class="flex items-center gap-3">
                  <div class="avatar-initials bg-primary">
                    {{ strtoupper(substr($candidate->first_name, 0, 1)) }}{{ strtoupper(substr($candidate->last_name, 0, 1)) }}
                  </div>
                  <div>
                    <div class="font-medium">{{ $candidate->full_name }}</div>
                    <div class="text-sm text-gray-400">{{ $candidate->email }}</div>
                  </div>
                </div>
              </td>
              
              <!-- Status Column -->
              <td>
                @php
                  $statusBadge = [
                    'pending_approval' => 'badge-warning',
                    'approved' => 'badge-success',
                    'rejected' => 'badge-error'
                  ][$candidate->status] ?? 'badge-info';
                @endphp
                <span class="status-badge {{ $statusBadge }}">
                  {{ ucfirst(str_replace('_', ' ', $candidate->status)) }}
                </span>
              </td>
              
              <!-- Tags Column -->
              <td>
                <div class="flex flex-wrap gap-1">
                  @forelse($candidate->tags as $tag)
                    <span class="badge badge-outline badge-sm" style="border-color: {{ $tag->color }}; color: {{ $tag->color }};">
                      {{ $tag->name }}
                    </span>
                  @empty
                    <span class="text-xs text-gray-400">None</span>
                  @endforelse
                </div>
              </td>
              
              <!-- License Column -->
              <td>
                @if($candidate->licenseVerification?->is_verified)
                  <div class="flex items-center gap-1 text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Verified</span>
                  </div>
                @else
                  <div class="flex items-center gap-1 text-error">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span>Not Verified</span>
                  </div>
                @endif
              </td>
              
              <!-- Tests Column -->
              <td>
                @if($candidate->tests->where('is_passed', false)->count() > 0)
                  <div class="tooltip" data-tip="{{ $candidate->tests->where('is_passed', false)->count() }} failed test(s)">
                    <div class="flex items-center gap-1 text-error">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                      </svg>
                      <span>Issues</span>
                    </div>
                  </div>
                @else
                  <div class="flex items-center gap-1 text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>All Passed</span>
                  </div>
                @endif
              </td>
              
              <!-- Notes Column -->
              <td>
                <div class="tooltip" data-tip="{{ $candidate->staff_notes }}">
                  <span class="text-sm line-clamp-1">{{ $candidate->staff_notes }}</span>
                </div>
              </td>
              
              <!-- Actions Column -->
              <td>
                <div class="flex justify-end gap-2">
                  <a href="{{ route('admin.candidates.review', $candidate) }}" class="btn btn-primary btn-sm">
                    Review
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                  </a>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center py-8">
                <div class="flex flex-col items-center justify-center gap-2 text-gray-400">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <p class="text-lg">No candidates pending approval</p>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      @if($candidates->hasPages())
      <div class="p-4 border-t border-base-200">
        <div class="flex justify-center">
          {{ $candidates->onEachSide(1)->links() }}
        </div>
      </div>
      @endif
    </div>
  </div>
</div>

</body>
</html>