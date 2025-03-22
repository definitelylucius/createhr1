<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.10/dayjs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.10/plugin/relativeTime.min.js"></script>
<script>
    dayjs.extend(dayjs_plugin_relativeTime);
</script>
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/login.css">
    <title>NexFleetDynamics</title>
</head>

<body>
<header class="bg-white py-4 px-6 flex justify-between items-center border-b border-gray-300 shadow-lg relative">
    <div class="font-bold text-2xl text-center text-[#00446b]">Nexfleet Dynamics</div>
    <div class="flex items-center space-x-4 relative">
        <!-- Profile Icon -->
        <button type="button" class="p-2 rounded-full hover:bg-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>

        <!-- Settings Icon with Dropdown -->
        <div class="relative">
            <button type="button" onclick="toggleSettingsDropdown()" class="p-2 rounded-full hover:bg-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </button>

            <!-- Settings Dropdown -->
            <div id="settingsDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-md shadow-lg z-20">
                <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Settings</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<div class="container mx-auto px-6 py-12">
    <h2 class="text-2xl font-bold text-blue-700">Apply for {{ $job->title }}</h2>

    <form action="{{ route('submit.application', $job->id) }}" method="POST" enctype="multipart/form-data">

        @csrf
        <div class="mt-4">
            <label class="block text-gray-700">Full Name</label>
            <input type="text" name="name" class="input input-bordered w-full mt-2" required>
        </div>

        <div class="mt-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" name="email" class="input input-bordered w-full mt-2" required>
        </div>

        <div class="mt-4">
            <label class="block text-gray-700">Upload Resume</label>
            <input type="file" name="resume" class="file-input file-input-bordered w-full mt-2" required>
        </div>

        <button type="submit" class="mt-6 bg-[#00446b] text-white px-6 py-2 rounded-md hover:bg-[#1F2936]">
            Submit Application
        </button>
    </form>
</div>


<script>
    function toggleSettingsDropdown() {
        const dropdown = document.getElementById('settingsDropdown');
        dropdown.classList.toggle('hidden');
    }
</script>
</body>
</html>
