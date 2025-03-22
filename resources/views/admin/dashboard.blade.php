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
</head>
<body class="bg-gray-100 font-[Poppins]">

    <!-- Navbar -->
    @include('admincomponent.nav-bar')

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg flex flex-col h-screen">
            @include('admincomponent.side-bar')
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6 overflow-y-auto">

            <!-- Header -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h1 class="text-3xl font-bold text-[#00446b]">Admin Dashboard</h1>
                <p class="text-gray-600">Welcome back, {{ Auth::user()->name }}</p>
            </div>

            <!-- Dashboard Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                
                <!-- Admin Review -->
                <div class="p-6 bg-blue-200 rounded-xl flex items-center justify-between shadow-lg">
                    <div>
                        <h3 class="font-semibold text-xl text-[#00446b]">Admin Review</h3>
                        <p class="text-3xl font-bold">
                            {{ $applicationStatusCounts['for_admin_review'] ?? '0' }}
                        </p>
                    </div>
                    <div class="bg-blue-500 rounded-full p-4 text-white">
                        <i class="fi fi-sr-user-clock"></i>
                    </div>
                </div>

                <!-- Interview Schedule -->
                <div class="p-6 bg-yellow-200 rounded-xl flex items-center justify-between shadow-lg">
                    <div>
                        <h3 class="text-xl font-semibold text-[#00446b]">Interview Schedule</h3>
                        <p class="text-3xl font-bold">
                            {{ $statusCounts['interview_scheduled'] ?? '0' }}
                        </p>
                    </div>
                    <div class="bg-yellow-500 rounded-full p-4 text-white">
                        <i class="fi fi-sr-calendar"></i>
                    </div>
                </div>

                <!-- Hired -->
                <div class="p-6 bg-green-200 rounded-xl flex items-center justify-between shadow-lg">
                    <div>
                        <h3 class="text-xl font-semibold text-[#00446b]">Hired</h3>
                        <p class="text-3xl font-bold">
                            {{ $statusCounts['hired'] ?? '0' }}
                        </p>
                    </div>
                    <div class="bg-green-500 rounded-full p-4 text-white">
                        <i class="fi fi-sr-briefcase"></i>
                    </div>
                </div>

                <!-- Rejected -->
                <div class="p-6 bg-red-200 rounded-xl flex items-center justify-between shadow-lg">
                    <div>
                        <h3 class="text-xl font-semibold text-[#00446b]">Rejected</h3>
                        <p class="text-3xl font-bold">
                            {{ $statusCounts['rejected'] ?? '0' }}
                        </p>
                    </div>
                    <div class="bg-red-500 rounded-full p-4 text-white">
                        <i class="fi fi-sr-times-circle"></i>
                    </div>
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

</body>
</html>