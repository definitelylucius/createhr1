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
  
    <form action="{{ route('admin.applications.updateStatus', $application->id) }}" method="POST">
    @csrf
    @method('PUT') 

    <label class="block text-gray-700 font-medium">Status:</label>
    <select name="status" required class="w-full p-3 border rounded-md bg-gray-100">
        <option value="pending_review">Pending Review</option>
        <option value="for_admin_review">For Admin Review</option>
        <option value="rejected">Rejected</option>
    </select>

    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-800 transition">
        Update Status
    </button>
</form>


  </div>
    

<script>
  .ajax({
    url: '/admin/applications/' + applicationId + '/status/' + applicationStatus, // Add status to the URL if needed
    type: 'PUT',
    data: {
        _token: '{{ csrf_token() }}',
        status: 'hired', // Replace with dynamic status if needed
        id: applicationId // Pass the id as part of the data
    },
    success: function(response) {
        alert('Status updated successfully!');
    }
});
</script>
</body>

</html>