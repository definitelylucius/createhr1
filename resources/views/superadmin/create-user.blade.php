<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@1.17.0/dist/full.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite('resources/css/sidebar.css')
    <title>Create User</title>
</head>

<body class="bg-gray-100 font-[Poppins]">

    @include('superadcomponent.nav-bar')

    <div class="flex">
        @include('superadcomponent.side-bar')

        <!-- Main Content -->
        <div class="container mx-auto p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Create User</h1>

            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success mb-4">
                    <div class="flex">
                        <div class="alert-icon">
                            <svg class="w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12l5 5L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-3 text-sm text-green-700">
                            {{ session('success') }}
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Form -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <form action="{{ route('superadmin.storeUser') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="input input-bordered w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="input input-bordered w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <!-- Password -->
                        <div>
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="input input-bordered w-full" type="password" name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="input input-bordered w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="mt-4">
                        <x-input-label for="role" :value="__('Role')" />
                        <select id="role" name="role" class="select select-bordered w-full">
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                            <option value="applicant" {{ old('role') == 'applicant' ? 'selected' : '' }}>Applicant</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary w-full">
                            Create User
                        </button>
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <h2 class="text-2xl font-bold mt-8">Users</h2>
            <div class="overflow-x-auto mt-4">
                <div class="bg-white shadow-xl rounded-lg p-6">
                    <table class="table table-zebra w-full rounded-lg border border-gray-200 shadow-md">
                        <thead class="bg-gradient-to-r from-blue-500 to-blue-700 text-white text-md">
                            <tr>
                                <th class="py-3 px-6 text-left">Username</th>
                                <th class="py-3 px-6 text-left">Email</th>
                                <th class="py-3 px-6 text-left">Role</th>
                                <th class="py-3 px-6 text-left">Registered At</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-100 transition duration-300 ease-in-out">
                                    <td class="py-4 px-6 font-medium flex items-center space-x-3">
                                        <div class="avatar">
                                            <div class="w-10 rounded-full">
                                                <img src="https://i.pravatar.cc/100?img={{ $loop->index }}" alt="User Avatar">
                                            </div>
                                        </div>
                                        <span>{{ $user->name }}</span>
                                    </td>
                                    <td class="py-4 px-6">{{ $user->email }}</td>
                                    <td class="py-4 px-6">
                                        <span class="badge badge-outline badge-lg capitalize 
                                            {{ $user->role === 'admin' ? 'badge-primary' : '' }}
                                            {{ $user->role === 'staff' ? 'badge-secondary' : '' }}
                                            {{ $user->role === 'employee' ? 'badge-success' : '' }}
                                            {{ $user->role === 'applicant' ? 'badge-warning' : '' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">{{ $user->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</body>
</html>

