<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Tailwind CSS & DaisyUI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Onboarding Panel | Staff Dashboard</title>
</head>

<body class="bg-gray-100 font-[Poppins]">

    <!-- Navbar -->
    @include('staffcomponent.nav-bar')

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md h-screen">
            @include('staffcomponent.side-bar')
        </aside>

        <div class="container mx-auto p-6">
            <!-- 🌟 Welcome Message -->
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold">Welcome, {{ Auth::user()->name }}! 🎉</h1>
                <p class="text-gray-600">Let's complete your onboarding process.</p>
            </div>

            <!-- 🛠 Onboarding Timeline -->
            <div class="mb-6 p-6 bg-white shadow-lg rounded-lg">
                <h2 class="text-xl font-bold mb-3">Onboarding Progress</h2>
                <div class="flex items-center">
                    @foreach ($tasks as $task)
                        <div class="relative flex-1">
                            <div class="w-8 h-8 rounded-full 
                                {{ $task->status === 'completed' ? 'bg-green-500' : 'bg-gray-300' }} 
                                mx-auto"></div>
                            <p class="text-center text-sm mt-2">{{ $task->task_name }}</p>
                            @if (!$loop->last)
                                <div class="h-1 w-full bg-gray-300 absolute top-4 left-1/2"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ✅ Tasks to be Done -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Tasks to Complete</h2>
                <ul>
                    @foreach ($tasks as $task)
                        <li class="flex justify-between items-center p-3 border-b">
                            <span>{{ $task->task_name }}</span>
                            @if($task->status == 'completed')
                                <span class="text-green-500 font-bold">✔ Completed</span>
                            @else
                                <button class="bg-blue-500 text-white px-4 py-2 rounded" onclick="openModal('{{ $task->task_slug }}')">
                                    Complete
                                </button>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- ✅ Modals for Onboarding Tasks -->
    @foreach ($tasks as $task)
    <div id="{{ $task->task_slug }}" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white p-6 rounded shadow w-1/2">
            <h2 class="text-xl font-bold">{{ $task->task_name }}</h2>
            <form action="{{ route('onboarding.complete', $task->id) }}" method="POST">
                @csrf
                @if($task->task_slug == 'employee-details')
                    <label class="block">Profile Picture</label>
                    <input type="file" name="profile_picture" class="file-input w-full mb-2">
                    
                    <label class="block">Bio</label>
                    <textarea name="bio" class="textarea w-full mb-2"></textarea>
                    
                    <label class="block">Department</label>
                    <input type="text" name="department" class="input w-full mb-2">
                    
                    <label class="block">Position</label>
                    <input type="text" name="position" class="input w-full mb-2">
                @endif

                @if($task->task_slug == 'personal-details')
                    <label class="block">Full Name</label>
                    <input type="text" name="full_name" class="input w-full mb-2">
                    
                    <label class="block">Phone</label>
                    <input type="text" name="phone" class="input w-full mb-2">
                    
                    <label class="block">Email</label>
                    <input type="email" name="email" class="input w-full mb-2">
                    
                    <label class="block">Age</label>
                    <input type="number" name="age" class="input w-full mb-2">
                    
                    <label class="block">Birthday</label>
                    <input type="date" name="birthday" class="input w-full mb-2">
                @endif

                @if($task->task_slug == 'personal-files')
                    <label class="block">Select Document</label>
                    <select name="document_name" class="select w-full mb-2">
                        <option value="Government ID">Government ID</option>
                        <option value="TIN ID">TIN ID</option>
                        <option value="NDA">NDA</option>
                        <option value="Contract">Contract</option>
                        <option value="Onboarding Form">Onboarding Form</option>
                    </select>
                    
                    <label class="block">Upload File</label>
                    <input type="file" name="document" class="file-input w-full mb-2">
                @endif

                @if($task->task_slug == 'bank-details')
                    <label class="block">Bank Name</label>
                    <input type="text" name="bank_name" class="input w-full mb-2">
                    
                    <label class="block">Account Number</label>
                    <input type="text" name="account_number" class="input w-full mb-2">
                    
                    <label class="block">Account Holder</label>
                    <input type="text" name="account_holder" class="input w-full mb-2">
                @endif

                <button type="submit" class="btn btn-primary w-full">Save & Next</button>
            </form>
            <button onclick="closeModal('{{ $task->task_slug }}')" class="btn btn-secondary mt-2">Close</button>
        </div>
    </div>
    @endforeach

    <!-- JavaScript -->
    <script>
        function openModal(id) {
            let modal = document.getElementById(id);
            if (modal) modal.classList.remove('hidden');
        }

        function closeModal(id) {
            let modal = document.getElementById(id);
            if (modal) modal.classList.add('hidden');
        }
    </script>

</body>
</html>
