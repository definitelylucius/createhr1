<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@1.17.0/dist/full.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>Super Admin Dashboard</title>
</head>
<body class="bg-base-100 font-[Poppins]">

@include('superadcomponent.nav-bar')

<div class="flex flex-1">
    @include('superadcomponent.side-bar')

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Super Admin Dashboard</h1>

        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="card w-full bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-700">Total Users</h2>
                <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
            </div>
            <div class="card w-full bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-700">Admins</h2>
                <p class="text-3xl font-bold text-gray-900">{{ $adminCount }}</p>
            </div>
            <div class="card w-full bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-700">Staff</h2>
                <p class="text-3xl font-bold text-gray-900">{{ $staffCount }}</p>
            </div>
            <div class="card w-full bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-700">Employees</h2>
                <p class="text-3xl font-bold text-gray-900">{{ $employeeCount }}</p>
            </div>
            <div class="card w-full bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-700">Applicants</h2>
                <p class="text-3xl font-bold text-gray-900">{{ $applicantCount }}</p>
            </div>
        </div>

        <!-- User Management Table -->
        <h2 class="text-xl font-bold mt-6">User & Role Management</h2>

        <div class="overflow-x-auto mt-4">
            <table class="table w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Username</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-left">Role</th>
                        <th class="py-3 px-6 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-100">
                            <td class="py-3 px-6">{{ $user->name }}</td>
                            <td class="py-3 px-6">{{ $user->email }}</td>
                            <td class="py-3 px-6">{{ ucfirst($user->role) }}</td>
                            <td class="py-3 px-6">
                                <a href="{{ route('superadmin.editUser', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('superadmin.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-error">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>