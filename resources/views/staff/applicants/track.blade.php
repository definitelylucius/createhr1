<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@1.17.0/dist/full.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>Staff Dashboard - Track Job Applications</title>
</head>

<body class="bg-gray-100 font-[Poppins]">

    <!-- Navbar -->
    @include('staffcomponent.nav-bar')

    <div class="flex">
        <!-- Sidebar -->
        @include('staffcomponent.side-bar')

        <!-- Main Content -->
        <div class="container mx-auto px-6 py-12 flex flex-col">
            <h2 class="text-3xl font-bold text-[#00446b] mb-6">Track Job Applications</h2>

            <div class="mt-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-6">
                    <!-- New Application -->
                    <div class="card shadow-lg p-6 bg-blue-200 rounded-xl flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-xl text-[#00446b]">New Applications</h3>
                    <p class="text-3xl font-bold">
    {{ isset($applicationsCount['new_application']) ? $applicationsCount['new_application'] : 'N/A' }}
</p>
                        </div>
                        <div class="bg-blue-500 rounded-full p-4 text-white">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>

                    <!-- Qualified -->
                    <div class="card shadow-lg p-6 bg-green-200 rounded-xl flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-xl text-[#00446b]">Qualified</h3>
                            <p class="text-3xl font-bold">{{ $applicationsCount['qualified'] }}</p>
                        </div>
                        <div class="bg-green-500 rounded-full p-4 text-white">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>

                    <!-- Scheduled -->
                    <div class="card shadow-lg p-6 bg-yellow-200 rounded-xl flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-xl text-[#00446b]">Scheduled</h3>
                            <p class="text-3xl font-bold">{{ $applicationsCount['scheduled'] }}</p>
                        </div>
                        <div class="bg-yellow-500 rounded-full p-4 text-white">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>

                    <!-- Interviewed -->
                    <div class="card shadow-lg p-6 bg-orange-200 rounded-xl flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-xl text-[#00446b]">Interviewed</h3>
                            <p class="text-3xl font-bold">{{ $applicationsCount['interviewed'] }}</p>
                        </div>
                        <div class="bg-orange-500 rounded-full p-4 text-white">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>

                    <!-- Hired -->
                    <div class="card shadow-lg p-6 bg-gray-200 rounded-xl flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-xl text-[#00446b]">Hired</h3>
                            <p class="text-3xl font-bold">{{ $applicationsCount['hired'] }}</p>
                        </div>
                        <div class="bg-gray-500 rounded-full p-4 text-white">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Overview Table -->
            <div class="overflow-hidden bg-white shadow-lg rounded-xl mt-8 min-h-[300px]">
                <table class="min-w-full table-auto">
                    <thead class="bg-indigo-500 text-white">
                        <tr>
                            <th class="py-3 px-6 text-left">Job Title</th>
                            <th class="py-3 px-6 text-left">Deadline</th>
                            <th class="py-3 px-6 text-left">Status</th>
                            <th class="py-3 px-6 text-left">Time to Hire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Check if there is no data for the table -->
                        @forelse ($jobs as $job)
                            <tr class="bg-gray-50">
                                <td class="py-3 px-6 text-gray-700">{{ $job->title }}</td>
                                <td class="py-3 px-6 text-gray-700">{{ $job->application_deadline->format('F j, Y') }}</td>
                                <td class="py-3 px-6 text-gray-700">{{ $job->status }}</td>
                                <td class="py-3 px-6 text-gray-700">{{ $job->time_to_hire }} days</td>
                            </tr>
                        @empty
                            <tr class="bg-gray-50">
                                <td colspan="4" class="py-3 px-6 text-center text-gray-700">No job applications available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>


