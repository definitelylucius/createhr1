<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Letter - NexFleet Dynamics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-12 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Letter Header -->
            <div class="bg-blue-800 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold">NexFleet Dynamics</h1>
                        <p class="mt-1">Bus Staff Recruitment</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm">{{ now()->format('F j, Y') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Letter Content -->
            <div class="p-6">
                <div class="prose max-w-none">
                    {!! nl2br(e($offerLetter->content)) !!}
                </div>
                
                <!-- Signature Area -->
                @if($offerLetter->status === 'sent')
                <div class="mt-12 border-t pt-8">
                    <h3 class="text-lg font-medium mb-4">Please sign to accept this offer</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Signature</label>
                        <div class="border rounded-md bg-white p-2">
                            <canvas id="signature-pad" class="w-full h-32 border border-gray-300"></canvas>
                        </div>
                        <button id="clear-signature" type="button" class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                            Clear Signature
                        </button>
                    </div>
                    
                    <div class="mb-4">
                        <label for="full-name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="full-name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    
                    <button id="accept-offer" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Accept Offer
                    </button>
                </div>
                @elseif($offerLetter->status === 'accepted')
                <div class="mt-12 border-t pt-8">
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-sm text-gray-500">Accepted on: {{ $offerLetter->signed_at->format('F j, Y') }}</p>
                            <p class="text-sm text-gray-500">IP: {{ $offerLetter->ip_address }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">Signature:</p>
                            <img src="{{ Storage::url($offerLetter->signature_path) }}" alt="Signature" class="h-20 mt-2">
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($offerLetter->status === 'sent')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('signature-pad');
            const signaturePad = new SignaturePad(canvas);
            const clearButton = document.getElementById('clear-signature');
            const acceptButton = document.getElementById('accept-offer');
            const fullNameInput = document.getElementById('full-name');
            
            // Adjust canvas coordinate space taking into account pixel ratio,
            // to make it look crisp on mobile devices.
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                signaturePad.clear(); // otherwise isEmpty() might return incorrect value
            }
            
            window.addEventListener('resize', resizeCanvas);
            resizeCanvas();
            
            clearButton.addEventListener('click', function() {
                signaturePad.clear();
            });
            
            acceptButton.addEventListener('click', function() {
                if (signaturePad.isEmpty()) {
                    alert('Please provide a signature first.');
                    return;
                }
                
                if (!fullNameInput.value.trim()) {
                    alert('Please enter your full name.');
                    return;
                }
                
                const signatureData = signaturePad.toDataURL();
                
                fetch("{{ route('offer-letters.sign', $offerLetter) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        signature: signatureData,
                        full_name: fullNameInput.value.trim()
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Offer accepted successfully!');
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to accept offer'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while accepting the offer.');
                });
            });
        });
    </script>
    @endif
</body>
</html>