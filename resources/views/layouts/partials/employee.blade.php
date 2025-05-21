@extends('layouts.app')

@section('body-class', 'employee-layout')

@push('styles')
<style>
    .employee-layout {
        --sidebar-width: 250px;
        --navbar-height: 60px;
        --primary-color: #10b981;
        --primary-light: #34d399;
        --secondary-color: #1e293b;
    }
    
    .employee-sidebar {
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
    
    .employee-main {
        margin-left: var(--sidebar-width);
        padding-top: var(--navbar-height);
        min-height: 100vh;
        background-color: #f8fafc;
    }
    
    .employee-navbar {
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
        .employee-sidebar {
            transform: translateX(-100%);
        }
        
        .employee-sidebar.active {
            transform: translateX(0);
        }
        
        .employee-main {
            margin-left: 0;
        }
        
        .employee-navbar {
            left: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="employee-sidebar">
    @include('layouts.partials.employee-sidebar')
</div>

<div class="employee-navbar">
    @include('layouts.partials.employee-navbar')
</div>

<div class="employee-main">
    <div class="container-fluid py-4 px-4">
        @yield('employee-content')
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle sidebar on mobile
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const sidebar = document.querySelector('.employee-sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        }
        
        // Highlight active menu item
        const currentUrl = window.location.href;
        const menuLinks = document.querySelectorAll('.sidebar-link');
        
        menuLinks.forEach(link => {
            if (link.href === currentUrl) {
                link.classList.add('active');
                
                // Expand parent menu if this is a submenu item
                let parentItem = link.closest('.sidebar-submenu');
                if (parentItem) {
                    parentItem.style.display = 'block';
                    parentItem.previousElementSibling.classList.add('active');
                }
            }
        });
    });
</script>
@endpush