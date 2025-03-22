<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Tailwind CSS & DaisyUI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ["Poppins", "sans-serif"]
                    }
                }
            }
        };
    </script>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Onboarding Management | Staff Dashboard</title>
</head>

<body class="bg-gray-100 font-poppins">

    <!-- Navbar -->
    @include('staffcomponent.nav-bar')

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md h-screen overflow-y-auto">
            @include('staffcomponent.side-bar')
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <!-- 🌟 Welcome Message -->
            <h1 class="text-2xl font-bold mb-4">Onboarding Management</h1>
            <p class="text-gray-600 mb-6">Track and manage the onboarding progress of new employees.</p>

            <!-- 🔹 Employee Onboarding Status -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">New Hires</h2>

                @if(isset($employees) && $employees->isNotEmpty())
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 border">Employee Name</th>
                            <th class="p-2 border">Department</th>
                            <th class="p-2 border">Onboarding Status</th>
                            <th class="p-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr class="border">
                                <td class="p-2 border">{{ $employee->name }}</td>
                                <td class="p-2 border">{{ $employee->department }}</td>
                                <td class="p-2 border">
                                    <span class="{{ $employee->onboarding_status === 'completed' ? 'text-green-500' : 'text-yellow-500' }}">
                                        {{ ucfirst($employee->onboarding_status) }}
                                    </span>
                                </td>
                                <td class="p-2 border">
                                    <button onclick="openTaskModal('{{ $employee->id }}')" 
                                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                        View Tasks
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p class="text-gray-600">No employees found for onboarding.</p>
                @endif
            </div>

            <!-- 🔹 Task Modal -->
            <div id="taskModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
                <div class="bg-white p-6 rounded shadow-lg w-1/2">
                    <h2 class="text-xl font-bold">Employee Tasks</h2>
                    <ul id="taskList" class="mt-4 space-y-2"></ul>

                    <button onclick="closeTaskModal()" class="bg-gray-500 text-white px-3 py-1 rounded mt-4 hover:bg-gray-600">
                        Close
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function openTaskModal(employeeId) {
            fetch(`/staff/onboarding/tasks/${employeeId}`)
                .then(response => response.json())
                .then(data => {
                    const taskList = document.getElementById('taskList');
                    taskList.innerHTML = '';
                    if (data.length === 0) {
                        taskList.innerHTML = '<p class="text-gray-600">No tasks assigned.</p>';
                    } else {
                        data.forEach(task => {
                            taskList.innerHTML += `
                                <li class="p-2 border-b flex justify-between">
                                    <span>${task.task_name} - <strong>${task.status}</strong></span>
                                    ${task.status !== 'completed' ? 
                                        `<button onclick="markTaskComplete(${task.id})" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">Mark as Done</button>` 
                                        : `<span class="text-green-500">✔ Completed</span>`}
                                </li>
                            `;
                        });
                    }
                    document.getElementById('taskModal').classList.remove('hidden');
                })
                .catch(error => console.error('Error fetching tasks:', error));
        }

        function closeTaskModal() {
            document.getElementById('taskModal').classList.add('hidden');
        }

        function markTaskComplete(taskId) {
            fetch(`/staff/onboarding/tasks/complete/${taskId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(() => location.reload())
            .catch(error => console.error('Error completing task:', error));
        }
    </script>

</body>
</html>
