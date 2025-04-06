<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Tailwind & DaisyUI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@1.17.0/dist/full.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <title>Staff Dashboard - Candidate Documents</title>
</head>

<body class="bg-gray-100 font-[Poppins]">

   <!-- Navbar -->
   @include('staffcomponent.nav-bar')

<div class="flex">
    <!-- Sidebar -->
    @include('staffcomponent.side-bar')

       
</body>
</html>