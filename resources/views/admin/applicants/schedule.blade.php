<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- DaisyUI CDN -->
  <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet">

  <title>Admin Dashboard - Bus Transportation</title>
</head>
<body class="bg-gray-100 font-[Poppins] text-[#1e293b]">

  @include('admincomponent.nav-bar')

  <!-- Main Layout -->
  <div class="flex min-h-screen">
    
    @include('admincomponent.side-bar')

    <div class="container mt-4">
    <h2>Schedule Interview</h2>
    <form action="{{ route('admin.scheduleInterview', $application->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Interview Date:</label>
            <input type="datetime-local" class="form-control" name="interview_date" required>
        </div>

        <button type="submit" class="btn btn-primary">Schedule</button>
    </form>
</div>




    </body>

</html>