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


    <div class="container mt-4">
    <h2>Mark Applicant as Hired</h2>
    <form action="{{ route('staff.markHired', $application->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">Mark as Hired</button>
    </form>
</div>






    </body>

</html>