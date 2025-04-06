<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind & DaisyUI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@1.17.0/dist/full.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Load Flat Icons -->
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>Staff Dashboard</title>
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
<body class="bg-gray-50 font-[Poppins]">
    <!-- Navbar -->
    @include('staffcomponent.nav-bar')

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-56 bg-white shadow-sm flex flex-col h-screen">
            @include('staffcomponent.side-bar')
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-4 overflow-y-auto">
            <!-- Header -->
            <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
                <h1 class="text-2xl font-bold text-[#00446b]">Staff Dashboard</h1>
                <p class="text-sm text-gray-500">Welcome back, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
            </div>

            <!-- Dashboard Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <!-- Total Candidates Card -->
                <div class="stat-card bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Candidates</p>
                            <h3 class="text-xl font-bold mt-1">{{ $totalCandidates }}</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="text-green-500">+{{ $newCandidatesThisWeek }}</span> this week
                            </p>
                        </div>
                        <div class="bg-blue-50 p-2 rounded-lg">
                            <i class="fi fi-rr-users text-blue-400 text-lg"></i>
                        </div>
                    </div>
                </div>

                <!-- Interviews Scheduled Card -->
                <div class="stat-card bg-white p-4 rounded-lg shadow-sm border-l-4 border-orange-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Interviews</p>
                            <h3 class="text-xl font-bold mt-1">{{ $scheduledInterviews }}</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="text-green-500">{{ $interviewsToday }}</span> today
                            </p>
                        </div>
                        <div class="bg-orange-50 p-2 rounded-lg">
                            <i class="fi fi-rr-calendar text-orange-400 text-lg"></i>
                        </div>
                    </div>
                </div>

                <!-- Pending Approval Card -->
                <div class="stat-card bg-white p-4 rounded-lg shadow-sm border-l-4 border-purple-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pending Approval</p>
                            <h3 class="text-xl font-bold mt-1">{{ $pendingApproval }}</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="text-red-500">{{ $overdueApprovals }}</span> overdue
                            </p>
                        </div>
                        <div class="bg-purple-50 p-2 rounded-lg">
                            <i class="fi fi-rr-document-signed text-purple-400 text-lg"></i>
                        </div>
                    </div>
                </div>

                <!-- Hired Candidates Card -->
                <div class="stat-card bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Hired</p>
                            <h3 class="text-xl font-bold mt-1">{{ $hiredCandidates }}</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="text-green-500">+{{ $hiredThisMonth }}</span> this month
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
                <!-- Candidates Status Chart -->
                <div class="bg-white p-4 rounded-lg shadow-sm lg:col-span-2">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Candidates by Status</h2>
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

              

            <!-- Upcoming Interviews and Recent Candidates -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Upcoming Interviews -->
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Upcoming Interviews</h2>
                        <a href="{{ route('staff.final-interviews.index') }}" class="text-xs text-blue-500 hover:underline">View All</a>
                    </div>
                    <div class="space-y-2 max-h-[220px] overflow-y-auto">
                        @forelse($upcomingInterviews as $interview)
                        <div class="flex items-center justify-between p-2 border rounded hover:bg-gray-50">
                            <div class="flex items-center">
                                <div class="bg-blue-50 p-1.5 rounded-full mr-2">
                                    <i class="fi fi-rr-calendar text-blue-400 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium">{{ $interview->candidate->full_name }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $interview->scheduled_at->format('M d, h:i A') }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('staff.final-interviews.show', $interview) }}" class="text-blue-400 hover:text-blue-500">
                                <i class="fi fi-rr-eye text-xs"></i>
                            </a>
                        </div>
                        @empty
                        <p class="text-xs text-gray-500 text-center py-2">No upcoming interviews</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Candidates -->
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Recent Candidates</h2>
                        <a href="{{ route('staff.candidates.index') }}" class="text-xs text-blue-500 hover:underline">View All</a>
                    </div>
                    <div class="space-y-2 max-h-[220px] overflow-y-auto">
                        @forelse($recentCandidates as $candidate)
                        <div class="flex items-center justify-between p-2 border rounded hover:bg-gray-50">
                            <div class="flex items-center">
                                <div class="bg-purple-50 p-1.5 rounded-full mr-2">
                                    <i class="fi fi-rr-user text-purple-400 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium">{{ $candidate->full_name }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $candidate->job->title ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <span class="px-1.5 py-0.5 text-[0.6rem] rounded-full bg-{{ $candidate->status_badge }}-100 text-{{ $candidate->status_badge }}-800">
                                {{ str_replace('_', ' ', $candidate->status) }}
                            </span>
                        </div>
                        @empty
                        <p class="text-xs text-gray-500 text-center py-2">No recent candidates</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($statusLabels) !!},
                datasets: [{
                    data: {!! json_encode($statusData) !!},
                    backgroundColor: [
                        '#3B82F6', // blue
                        '#F59E0B', // amber
                        '#10B981', // emerald
                        '#EF4444', // red
                        '#8B5CF6', // violet
                        '#64748B', // slate
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 10,
                            font: {
                                size: 10
                            },
                            padding: 10
                        }
                    },
                    tooltip: {
                        bodyFont: {
                            size: 10
                        },
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%',
            }
        });
    </script>
</body>
</html>