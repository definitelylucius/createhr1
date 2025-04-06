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
  <title>Candidate Tags - Bus Transportation</title>
</head>
<body class="bg-gray-100 font-[Poppins] text-[#1e293b]">
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Candidate Tags</h2>
            <a href="{{ route('staff.tags.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Tag
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Color</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $tag)
                            <tr>
                                <td>{{ $tag->name }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $tag->color }}">
                                        {{ $tag->color }}
                                    </span>
                                </td>
                                <td>{{ $tag->created_at->format('m/d/Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('staff.tags.edit', $tag) }}" class="btn btn-info">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('staff.tags.destroy', $tag) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Delete this tag?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No tags created yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $tags->links() }}
            </div>
        </div>
    </div>
</div>
</body>
</html>