@extends('layouts.staff')

@section('content')

<!-- Main Layout Structure -->
<div class="flex h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-20 w-64 bg-white shadow transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out">
        @include('layouts.partials.admin-sidebar')
    </aside>
    
    <div class="flex-1 flex flex-col overflow-hidden lg:ml-64">
        <!-- Navbar -->
        @include('layouts.partials.admin-navbar')
        
        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto pt-16">
            <div class="container mx-auto px-4 py-6">


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Prepare Offer Letter - {{ $application->full_name }}</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('offer-letter.generate', $application->id) }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="position">Position</label>
                                    <input type="text" class="form-control" id="position" name="position" value="{{ $application->job->title }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="salary">Salary</label>
                                    <input type="number" class="form-control" id="salary" name="salary" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="terms">Terms</label>
                            <textarea class="form-control" id="terms" name="terms" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="benefits">Benefits</label>
                            <textarea class="form-control" id="benefits" name="benefits" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Generate Offer Letter</button>
                    </form>

                    @if(session('offer_path'))
                        <div class="mt-4">
                            <iframe src="{{ session('offer_path') }}" style="width:100%; height:500px;"></iframe>
                            <div class="mt-2">
                                <a href="{{ route('offer-letter.send', $application->id) }}" class="btn btn-success">Send to Candidate</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
     document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.dropdown').forEach(dropdown => {
            const btn = dropdown.querySelector('button');
            const menu = dropdown.querySelector('.dropdown-menu');

            if (btn && menu) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    // Close other dropdowns first
                    document.querySelectorAll('.dropdown-menu').forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });
                    // Toggle this one
                    menu.classList.toggle('hidden');
                });
            }
        });

        // Global click handler to close all dropdowns
        document.addEventListener('click', function () {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        });
    });
</script>
@endsection