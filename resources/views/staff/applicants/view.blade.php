<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@1.17.0/dist/full.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>Staff Dashboard</title>
</head>

<body class="bg-gray-100 font-[Poppins]">

@include('staffcomponent.nav-bar')

<div class="flex flex-1">
    @include('staffcomponent.side-bar')

    <div class="container mx-auto px-6 py-12">
        <h2 class="text-2xl font-bold text-[#00446b] mb-6">All Job Applications</h2>

        <div class="overflow-hidden shadow-lg rounded-2xl">
            <table class="w-full border border-gray-300 rounded-2xl overflow-hidden">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="px-4 py-3 text-left text-sm font-semibold">Applicant Name</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Email</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Job Title</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Resume</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($applications as $application)
                        <tr class="hover:bg-blue-50 transition duration-200">
                            <td class="px-4 py-3 border-b">{{ $application->user->name }}</td>
                            <td class="px-4 py-3 border-b">{{ $application->user->email }}</td>
                            <td class="px-4 py-3 border-b">{{ $application->job->title }}</td>
                            <td class="px-4 py-3 border-b text-blue-600 font-semibold">{{ ucfirst($application->status) }}</td>
                            <td class="px-4 py-3 border-b">
                                <button class="text-blue-500 hover:text-blue-700 font-medium" 
                                    onclick="showResumeModal('{{ $application->user->name }}', '{{ $application->user->email }}', '{{ $application->job->title }}', '{{ asset('storage/' . $application->resume) }}')">
                                    View Resume
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Resume Modal -->
<div id="resumeModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-6 rounded-2xl shadow-lg w-3/4 max-w-2xl transform transition-all scale-95 opacity-0 animate-modal">
            <h3 class="text-xl font-bold text-center mb-4">Applicant Information</h3>
            
            <div id="applicantInfo" class="mb-4"></div>
            
            <div id="resumeContent" class="mb-4"></div>
            
            <button class="mt-4 w-full bg-red-500 text-white py-2 rounded-xl hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50" onclick="closeModal()">Close</button>
        </div>
    </div>
</div>


<script>
    function showResumeModal(name, email, jobTitle, resumeUrl) {
        const modal = document.getElementById('resumeModal');
        modal.classList.remove('hidden');

        const modalContent = modal.querySelector('div');
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');

        document.getElementById('applicantInfo').innerHTML = ` 
            <p><strong>Applicant Name:</strong> ${name}</p>
            <p><strong>Email:</strong> ${email}</p>
            <p><strong>Job Applied:</strong> ${jobTitle}</p>
        `;
        
        let fileExtension = resumeUrl.split('.').pop().toLowerCase();
        let resumeContent = '';

        if (fileExtension === 'pdf') {
            resumeContent = `<iframe src="${resumeUrl}" class="w-full h-96 rounded-xl" frameborder="0"></iframe>`;
        } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
            resumeContent = `<img src="${resumeUrl}" alt="Applicant Resume" class="w-full h-auto rounded-xl" />`;
        } else {
            resumeContent = `<p class="text-red-500">Unsupported file type</p>`;
        }

        document.getElementById('resumeContent').innerHTML = resumeContent;
    }

    function closeModal() {
        const modal = document.getElementById('resumeModal');
        modal.classList.add('hidden');
    }
</script>

<style>
    @keyframes modal {
        0% { transform: scale(0.95); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .animate-modal {
        animation: modal 0.3s ease-out forwards;
    }
</style>

</body>
</html>
