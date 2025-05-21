<!DOCTYPE html>
<html lang="en" data-theme="corporate">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- DaisyUI -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet">

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Toastify -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- FlatIcon UIcons -->
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Laravel Mix Compiled Assets -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Custom Styles -->
    <style>
        .auto-resize {
            min-height: 100px;
            resize: none;
            overflow-y: hidden;
        }
        .job-item {
            transition: all 0.2s ease;
        }
        .job-item:hover {
            background-color: #f3f4f6;
            transform: translateY(-1px);
        }

        .admin-layout {
            --sidebar-width: 250px;
            --navbar-height: 60px;
            --primary-color: #4f46e5;
            --primary-light: #6366f1;
            --secondary-color: #1e293b;
        }

        .admin-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--secondary-color);
            color: white;
            transition: all 0.3s;
            z-index: 100;
        }

        .admin-main {
            margin-left: var(--sidebar-width);
            padding-top: var(--navbar-height);
            min-height: 100vh;
            background-color: #f8fafc;
        }

        .admin-navbar {
            height: var(--navbar-height);
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            z-index: 90;
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.active {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
            }
            .admin-navbar {
                left: 0;
            }
        }
    </style>
</head>

<body class="bg-gray-50 font-[Poppins] text-gray-800">
    <main class="min-h-screen">
        @yield('content')
    </main>

    @stack('bottom-scripts')
</body>
</html>
