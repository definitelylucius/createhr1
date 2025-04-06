<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Tailwind & DaisyUI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@1.17.0/dist/full.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>Admin Dashboard</title>
    <style>
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .compact-chart {
            height: 220px !important;
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
            <!-- Header -->
            <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
                <h1 class="text-2xl font-bold text-[#00446b]">Admin Dashboard</h1>
                <p class="text-sm text-gray-500">Welcome back, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
            </div>

            <!-- Dashboard Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <!-- Total Candidates Card -->
                <div class="stat-card bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Candidates</p>
                            <h3 class="text-xl font-bold mt-1">{{ $totalCandidates ?? 0 }}</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="text-green-500">+{{ $newCandidatesThisWeek ?? 0 }}</span> this week
                            </p>
                        </div>
                        <div class="bg-blue-50 p-2 rounded-lg">
                            <i class="fi fi-rr-users text-blue-400 text-lg"></i>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals Card -->
                <div class="stat-card bg-white p-4 rounded-lg shadow-sm border-l-4 border-purple-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pending Approval</p>
                            <h3 class="text-xl font-bold mt-1">{{ $pendingApproval ?? 0 }}</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="text-red-500">{{ $overdueApprovals ?? 0 }}</span> overdue
                            </p>
                        </div>
                        <div class="bg-purple-50 p-2 rounded-lg">
                            <i class="fi fi-rr-document-signed text-purple-400 text-lg"></i>
                        </div>
                    </div>
                </div>

                <!-- For Review Card -->
                <div class="stat-card bg-white p-4 rounded-lg shadow-sm border-l-4 border-orange-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">For Review</p>
                            <h3 class="text-xl font-bold mt-1">{{ $forReviewCount ?? 0 }}</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="text-blue-500">{{ $reviewToday ?? 0 }}</span> today
                            </p>
                        </div>
                        <div class="bg-orange-50 p-2 rounded-lg">
                            <i class="fi fi-rr-time-past text-orange-400 text-lg"></i>
                        </div>
                    </div>
                </div>

                <!-- Hired Candidates Card -->
                <div class="stat-card bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Hired</p>
                            <h3 class="text-xl font-bold mt-1">{{ $hiredCandidates ?? 0 }}</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="text-green-500">+{{ $hiredThisMonth ?? 0 }}</span> this month
                            </p>
                        </div>
                        <div class="bg-green-50 p-2 rounded-lg">
                            <i class="fi fi-rr-badge-check text-green-400 text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Recent Activity Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
                <!-- Application Status Chart -->
                <div class="bg-white p-4 rounded-lg shadow-sm lg:col-span-2">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Application Status</h2>
                        <select class="text-xs border rounded px-2 py-1 bg-gray-50">
                            <option>This Month</option>
                            <option>Last Month</option>
                            <option>This Year</option>
                        </select>
                    </div>
                    <div class="compact-chart">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <!-- Notifications Panel -->
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Notifications</h2>
                        <span class="text-xs text-blue-500 hover:underline cursor-pointer">Mark all as read</span>
                    </div>
                    <div class="space-y-2 max-h-[220px] overflow-y-auto">
                        @forelse($notifications ?? [] as $notification)
                        <div class="flex items-start p-2 border rounded hover:bg-gray-50">
                            <div class="bg-blue-50 p-1.5 rounded-full mr-2">
                                <i class="fi fi-rr-bell text-blue-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium">{{ $notification->data['message'] ?? '' }}</p>
                                <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-gray-500 text-center py-2">No new notifications</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Approval Queue -->
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Approval Queue</h2>
                    <a href="{{ route('admin.candidates.index') }}" class="text-xs text-blue-500 hover:underline">View All</a>
                </div>
                <div class="space-y-2 max-h-[220px] overflow-y-auto">
                    @forelse($approvalQueue ?? [] as $candidate)
                    <div class="flex items-center justify-between p-2 border rounded hover:bg-gray-50">
                        <div class="flex items-center">
                            <div class="bg-yellow-50 p-1.5 rounded-full mr-2">
                                <i class="fi fi-rr-time-past text-yellow-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium">{{ $candidate->full_name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $candidate->job->title ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.candidates.review', ['candidate' => $candidate->id]) }}" class="text-blue-400 hover:text-blue-500">
                            <i class="fi fi-rr-eye text-xs"></i>
                        </a>
                    </div>
                    @empty
                    <p class="text-xs text-gray-500 text-center py-2">No candidates pending approval</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Status Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusChart = new Chart(statusCtx, {
                type: 'bar',
                data: {
                    labels: ['New', 'Under Review', 'Approved', 'Rejected', 'Hired'],
                    datasets: [{
                        label: 'Applications',
                        data: {!! json_encode($statusData ?? [0, 0, 0, 0, 0]) !!},
                        backgroundColor: [
                            '#3B82F6', // blue
                            '#F59E0B', // amber
                            '#10B981', // emerald
                            '#EF4444', // red
                            '#8B5CF6', // violet
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            bodyFont: {
                                size: 10
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>