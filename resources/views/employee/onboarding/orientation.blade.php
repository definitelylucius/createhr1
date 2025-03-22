<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Tailwind & DaisyUI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@2.51.2/dist/full.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>Employee Profile - Onboarding</title>

    <style>
    @keyframes modal {
        0% { transform: scale(0.95); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .animate-modal {
        animation: modal 0.3s ease-out forwards;
    }
</style>
</head>
<body class="bg-gray-100 font-[Poppins] min-h-screen flex flex-col">

    @include('employeecomponent.nav-bar')

    <div class="flex flex-grow">
        @include('employeecomponent.side-bar')

        
        <!-- Main Content -->
        <div class="w-full max-w-4xl mx-auto p-6 space-y-6">
    
    <!-- Employee Information Card -->
    <div class="card bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-800">Employee Information</h2>
        <div class="divider"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <label class="form-control w-full">
                <span class="label-text">Full Name</span>
                <input type="text" name="name" id="name" class="input input-bordered w-full" required>
            </label>

            <label class="form-control w-full">
                <span class="label-text">Address</span>
                <input type="text" name="address" id="address" class="input input-bordered w-full" required>
            </label>

            <label class="form-control w-full">
                <span class="label-text">Email</span>
                <input type="email" name="email" id="email" class="input input-bordered w-full" required>
            </label>

            <label class="form-control w-full">
                <span class="label-text">Department</span>
                <input type="text" name="department" id="department" class="input input-bordered w-full" required>
            </label>

            <label class="form-control w-full">
                <span class="label-text">Position</span>
                <input type="text" name="position" id="position" class="input input-bordered w-full" required>
            </label>

            <label class="form-control w-full">
                <span class="label-text">Hire Date</span>
                <input type="date" name="hire_date" id="hire_date" class="input input-bordered w-full" required>
            </label>
        </div>
    </div>

<!-- Document Generation Card -->
<div class="card bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-gray-800">Generate Documents</h2>
    <div class="divider"></div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <a href="{{ route('generate.nda', ['id' => $employee->id]) }}">Download NDA</a>

    <a href="{{ route('generate.onboarding', ['id' => $employee->id]) }}">Download Onboarding Letter</a>
    <a href="{{ route('generate.hiring', ['id' => $employee->id]) }}">Download Hiring Contract</a>
    </div>
</div>

    <!-- Document Upload Card -->
    <div class="card bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-800">Upload Documents</h2>
        <div class="divider"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <label class="form-control w-full">
                <span class="label-text">Upload NDA</span>
                <input type="file" name="nda" id="nda" class="file-input file-input-bordered w-full" required>
            </label>

            <label class="form-control w-full">
                <span class="label-text">Upload Welcome Letter</span>
                <input type="file" name="welcome_letter" id="welcome_letter" class="file-input file-input-bordered w-full" required>
            </label>

            <label class="form-control w-full">
                <span class="label-text">Upload Hiring Contract</span>
                <input type="file" name="contract" id="contract" class="file-input file-input-bordered w-full" required>
            </label>
        </div>
    </div>

    <!-- Submit Button Card -->
    <div class="card bg-white shadow-lg rounded-lg p-6 text-center">
        <button type="submit" class="btn btn-primary w-full text-lg">
            Submit
        </button>
    </div>

</div>


<script>

    //HANDLING DOCUMENT Generate
    function generateDocument(docType) {
        // Make an AJAX request to the backend to generate the PDF
        fetch(`/generate-${docType}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                // You can send employee data or other necessary information here
                employee_id: 123 // Example, replace with actual employee data
            })
        })
        .then(response => response.blob())
        .then(blob => {
            // Create a link element to trigger the download
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `${docType}_employee.pdf`; // Customize filename
            link.click();
        })
        .catch(error => {
            console.error('Error generating document:', error);
        });
    }
</script>





</body>
</html>
