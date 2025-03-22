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

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column: Employee Info -->
                <div class="bg-white p-6 shadow-lg rounded-xl">
                    <h3 class="text-lg font-semibold text-[#00446b] mb-4">Employee Information</h3>
                    <table class="w-full border border-gray-300 rounded-lg shadow-md">
                        <thead>
                            <tr class="bg-[#00446b] text-white">
                                <th class="p-4 text-left text-sm font-semibold">Employee Name</th>
                                <th class="p-4 text-left text-sm font-semibold">Department</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($employees as $employee)
    <tr>
        <td class="p-4">
            {{ $employee->name }}
        </td>
        <td class="p-4">
            {{ $employee->job->department ?? 'No department assigned' }}  <!-- Access department via job -->
        </td>
    </tr>
@endforeach

                    </table>
                </div>

                <!-- Right Column: Document Types for the Selected Employee -->
                <div class="bg-white p-6 shadow-lg rounded-xl">
                    <h3 class="text-lg font-semibold text-[#00446b] mb-4">Documents for Verification</h3>
                    <div id="documentList" class="space-y-4">
                        <!-- Initially empty or a message like "Select an employee to view documents" -->
                        <p id="noEmployeeSelected" class="text-gray-500">Select an employee to view documents.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Function to load documents based on employee ID
        function loadDocuments(employeeId, employeeName) {
            // Simulate fetching employee-specific documents from the server
            const documents = [
                { id: 'government_id', name: 'Government ID' },
                { id: 'emergency_contact', name: 'Emergency Contact' },
                // Add more document types here as needed
            ];

            // Get the document list element
            const documentList = document.getElementById('documentList');
            const noEmployeeSelected = document.getElementById('noEmployeeSelected');
            noEmployeeSelected.style.display = 'none';  // Hide the "Select an employee" message

            // Clear previous document list
            documentList.innerHTML = '';

            // Display employee's name at the top of the document list
            const employeeTitle = document.createElement('h4');
            employeeTitle.classList.add('font-semibold', 'text-xl', 'text-[#00446b]');
            employeeTitle.textContent = `Documents for ${employeeName}`;
            documentList.appendChild(employeeTitle);

            // Loop through documents and display them as clickable buttons
            documents.forEach(document => {
                const button = document.createElement('button');
                button.classList.add('btn', 'btn-secondary', 'w-full', 'mt-2');
                button.textContent = document.name;
                button.onclick = () => showDocumentContent(document.name);
                documentList.appendChild(button);
            });
        }

        // Function to show the document content (for now, just a placeholder)
        function showDocumentContent(documentName) {
            const documentContent = `
                <p><strong>Document:</strong> ${documentName}</p>
                <p>This is where the document content will appear.</p>
            `;

            // Replace document list with content for that specific document
            document.getElementById('documentList').innerHTML = documentContent;
        }
    </script>

</body>
</html>
