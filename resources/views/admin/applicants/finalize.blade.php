<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
  
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
  <!-- DaisyUI CDN -->
  <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Dashboard - Bus Transportation</title>
</head>
<body class="bg-gray-100 font-[Poppins] text-[#1e293b]">

  @include('admincomponent.nav-bar')

  <!-- Main Layout -->
  <div class="flex min-h-screen">
    
    @include('admincomponent.side-bar')


    <div class="container mt-4">
    <h2>Finalize Hiring</h2>
    <form action="{{ route('admin.finalizeHiring', $application->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Finalize Hiring</button>
    </form>
</div>






    </body>

</html>