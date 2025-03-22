<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Tailwind & DaisyUI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@2.51.2/dist/full.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <title>Employee Dashboard - Onboarding</title>
</head>
<body class="bg-gray-100 font-[Poppins]">

    <!-- Navbar -->
    @include('employeecomponent.nav-bar')

    <div class="flex">
        <!-- Sidebar -->
        @include('employeecomponent.side-bar')

        <div class="container mx-auto p-6">
    <!-- Top Breadcrumb -->
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Welcome back, {{ Auth::user()->name }}!</h1>
        <p class="text-gray-500">{{ now()->format('l, F jS') }}. Have a wonderful day!</p>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="grid grid-cols-12 gap-6">
        
    <div class="col-span-3 bg-white p-4 rounded-lg shadow-md">
    <div class="text-center">
        <div class="avatar">
            <div class="w-20 h-20 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                <img src="{{ Auth::user()->profile_photo_url ?? asset('default-avatar.png') }}" alt="Profile">
            </div>
        </div>
        <h2 class="text-lg font-semibold mt-2">{{ Auth::user()->name ?? 'N/A' }}</h2>
        <p class="text-gray-500">{{ Auth::user()->position ?? 'N/A' }}</p>
    </div>
    <hr class="my-3">
    <p><strong>Employee ID:</strong> {{ Auth::user()->employee_id ?? 'N/A' }}</p>
    <p><strong>Department:</strong> {{ Auth::user()->department ?? 'N/A' }}</p>
    <p><strong>Date Joined:</strong> {{ Auth::user()->date_joined ? Auth::user()->date_joined->format('d M Y') : 'N/A' }}</p>
    <p><strong>Email:</strong> {{ Auth::user()->email ?? 'N/A' }}</p>
    <p><strong>Manager:</strong> {{ Auth::user()->manager ?? 'N/A' }}</p>
</div>

        <!-- Middle Panel (Tasks & Onboarding) -->
        <div class="col-span-6 bg-white p-6 rounded-lg shadow-md">
            <div class="alert alert-info flex items-center">
                <span class="text-lg font-semibold">Onboarding</span>
                <p class="ml-2">Check and complete your onboarding tasks</p>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div class="card bg-gray-100 p-4 rounded-lg">
                    <h3 class="font-semibold">My Profile</h3>
                    <p class="text-sm text-gray-600">Update and review your personal profile</p>
                </div>
                <div class="card bg-gray-100 p-4 rounded-lg">
                    <h3 class="font-semibold">Directory</h3>
                    <p class="text-sm text-gray-600">List of employees in the company</p>
                </div>
                <div class="card bg-gray-100 p-4 rounded-lg">
                    <h3 class="font-semibold">Onboarding</h3>
                    <p class="text-sm text-gray-600">Onboarding Task</p>
                </div>
                
                
               
            </div>
        </div>

        <!-- Right Panel (Calendar & Who's Away) -->
        <div class="col-span-3 bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold">Who's Away?</h2>
            <div class="calendar mt-4">
                <div class="grid grid-cols-7 text-center font-semibold">
                    <div>Su</div> <div>Mo</div> <div>Tu</div> <div>We</div> <div>Th</div> <div>Fr</div> <div>Sa</div>
                </div>
                <div class="grid grid-cols-7 text-center mt-2">
                    <!-- Example Calendar -->
                    @for ($i = 1; $i <= 30; $i++)
                        <div class="p-1 text-gray-700 {{ $i == now()->day ? 'bg-primary text-white rounded' : '' }}">
                            {{ $i }}
                        </div>
                    @endfor
                </div>
            </div>
            <div class="mt-4 p-2 bg-gray-100 rounded">
                <h3 class="text-sm font-semibold">30/3 - Jack</h3>
                <p class="text-xs text-gray-600">Marketing Executive</p>
            </div>
        </div>

    </div>
</div>
    
</body>
</html>



