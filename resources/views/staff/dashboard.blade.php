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

    
</head>
<body class="bg-gray-100 font-[Poppins]">

    <!-- Navbar -->
    @include('staffcomponent.nav-bar')

    <div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg flex flex-col h-screen">
        @include('staffcomponent.side-bar')
    </div>

        <!-- Main Content -->
        <div class="flex-1 p-6 overflow-y-auto">

            <!-- Header -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h1 class="text-3xl font-bold text-[#00446b]">Staff Dashboard</h1>
                <p class="text-gray-600">Welcome back, {{ Auth::user()->name }}</p>
            </div>
<!-- Dashboard Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-6">
    <!-- Pending Review (application_status) -->
    <div class="p-6 bg-blue-200 rounded-xl flex items-center justify-between shadow-lg">
        <div>
            <h3 class="font-semibold text-xl text-[#00446b]">Pending Review</h3>
            <p class="text-3xl font-bold">
                {{ $applicationStatusCounts['new_application'] ?? '0' }}
            </p>
        </div>
        <div class="bg-blue-500 rounded-full p-4 text-white">
            <i class="fas fa-user-clock"></i>
        </div>
    </div>

    <!-- Qualified (status) -->
    <div class="p-6 bg-green-200 rounded-xl flex items-center justify-between shadow-lg">
        <div>
            <h3 class="text-xl font-semibold text-[#00446b]">Qualified</h3>
            <p class="text-3xl font-bold">
                {{ $statusCounts['interview_scheduled'] ?? '0' }}
            </p>
        </div>
        <div class="bg-green-500 rounded-full p-4 text-white">
            <i class="fas fa-check-circle"></i>
        </div>
    </div>

    <!-- Interview Scheduled (status) -->
    <div class="p-6 bg-yellow-200 rounded-xl flex items-center justify-between shadow-lg">
        <div>
            <h3 class="text-xl font-semibold text-[#00446b]">Interviewed</h3>
            <p class="text-3xl font-bold">
    {{ ($statusCounts['interviewed'] ?? 0) + ($statusCounts['recommended_for_hiring'] ?? 0) }}
</p>

        </div>
        <div class="bg-yellow-500 rounded-full p-4 text-white">
            <i class="fas fa-calendar-alt"></i>
        </div>
    </div>

    <!-- Hired (status) -->
    <div class="p-6 bg-orange-200 rounded-xl flex items-center justify-between shadow-lg">
        <div>
            <h3 class="text-xl font-semibold text-[#00446b]">Hired</h3>
            <p class="text-3xl font-bold">
                {{ $statusCounts['hired'] ?? 'N/A' }}
            </p>
        </div>
        <div class="bg-gray-500 rounded-full p-4 text-white">
            <i class="fas fa-briefcase"></i>
        </div>
    </div>

    <!-- Rejected (application_status) -->
    <div class="p-6 bg-red-200 rounded-xl flex items-center justify-between shadow-lg">
        <div>
            <h3 class="text-xl font-semibold text-[#00446b]">Rejected</h3>
            <p class="text-3xl font-bold">
                {{ $applicationStatusCounts['rejected'] ?? 'N/A' }}
            </p>
        </div>
        <div class="bg-red-500 rounded-full p-4 text-white">
            <i class="fas fa-times-circle"></i>
        </div>
    </div>
</div>



            <!-- Analytics Section -->
<div class="bg-white shadow-lg p-6 rounded-xl mt-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-[#00446b]">Application Trends</h2>
        <!-- Dropdown for Timeframe Selection -->
        <select id="timeframe" class="border rounded-md p-1 text-sm text-gray-700">
            <option value="7">Last 7 Days</option>
            <option value="30">Last 30 Days</option>
        </select>
    </div>

   

    <!-- Fixed Height Wrapper -->
    <div class="h-72">
        <canvas id="applicantChart" class="max-h-72"></canvas>
    </div>
</div>

            <!-- Applicant List Table -->
            <div class="bg-white shadow-lg p-6 rounded-xl mt-8">
                <h2 class="text-2xl font-bold text-[#00446b] mb-4">Applicant List</h2>
                <div class="overflow-x-auto">
                @if($applications->isEmpty())
    <p>No applicants found.</p>
@else
    <table class="w-full border border-gray-300 rounded-lg shadow-md">
        <thead>
            <tr class="bg-indigo-700 text-white">
                <th class="px-4 py-3 text-left text-sm font-semibold">Name</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Email</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Job</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($applications as $applicant)
                <tr class="hover:bg-indigo-50 transition duration-200">
                    <td class="px-4 py-3">{{ $applicant->user?->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3">{{ $applicant->user?->email ?? 'N/A' }}</td>
                    <td class="px-4 py-3">{{ $applicant->job?->title ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-indigo-600 font-semibold">{{ ucfirst($applicant->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
                </div>
            </div>

        </div>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    var ctx = document.getElementById('applicantChart').getContext('2d');

    var gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.7)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.1)');

    var applicantChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Applicants per Day',
                data: {!! json_encode($data) !!},
                backgroundColor: gradient,
                borderColor: '#3B82F6',
                borderWidth: 3,
                pointBackgroundColor: '#2563EB',
                pointRadius: 5,
                pointHoverRadius: 8,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: { size: 14, weight: 'bold' }
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: '#3B82F6',
                    titleFont: { weight: 'bold' },
                    bodyFont: { size: 14 },
                    padding: 10,
                    cornerRadius: 6
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(200, 200, 200, 0.3)' },
                    ticks: { color: '#333', font: { size: 12 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#333', font: { size: 12 } }
                }
            }
        }
    });

    // Change data dynamically based on timeframe selection
    document.getElementById('timeframe').addEventListener('change', function() {
        var days = this.value;
        fetch(`/analytics-data?days=${days}`)
            .then(response => response.json())
            .then(data => {
                applicantChart.data.labels = data.labels;
                applicantChart.data.datasets[0].data = data.data;
                applicantChart.update();
            });
    });
});
</script>
</body> 
</html>
