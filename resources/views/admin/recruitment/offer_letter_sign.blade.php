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
        
<div class="min-h-screen bg-gray-50 p-6">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold">Sign Offer Letter</h4>
            <p>For: {{ $application->name }} ({{ $application->job->title }})</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Offer Letter Preview</h5>
                </div>
                <div class="card-body">
                    <iframe src="{{ route('offer-letter.view', $application->id) }}" style="width:100%; height:500px; border:none;"></iframe>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Digital Signature</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('offer-letter.collectSignature', $application->id) }}" method="POST" id="signature-form">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="full_name" value="{{ $application->name }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="text" class="form-control" value="{{ now()->format('F j, Y') }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Signature</label>
                            <div class="signature-pad border rounded p-2">
                                <canvas id="signature-pad" width="300" height="150"></canvas>
                            </div>
                            <button type="button" id="clear-signature" class="btn btn-sm btn-outline-secondary mt-2">
                                Clear Signature
                            </button>
                            <input type="hidden" name="signature" id="signature-data">
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="acceptTerms" required>
                            <label class="form-check-label" for="acceptTerms">
                                I accept the terms and conditions of this offer
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100">Submit Signed Offer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas);
        const form = document.getElementById('signature-form');
        const clearButton = document.getElementById('clear-signature');
        const signatureData = document.getElementById('signature-data');
        
        // Handle window resize
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            signaturePad.clear();
        }
        
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();
        
        // Clear signature
        clearButton.addEventListener('click', function() {
            signaturePad.clear();
        });
        
        // Form submission
        form.addEventListener('submit', function(e) {
            if (signaturePad.isEmpty()) {
                e.preventDefault();
                alert('Please provide your signature');
            } else {
                signatureData.value = signaturePad.toDataURL();
            }
        });
    });

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
@endsection