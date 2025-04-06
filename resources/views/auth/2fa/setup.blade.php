<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>NexfleetDynamics - Login</title>
</head>

<body>
    <div class="h-screen flex flex-col md:flex-row bg-gray-100">
        <!-- Left Side - Background Image -->
        <div class="hidden lg:block lg:w-3/5 h-screen">
        <div class="bus-background bg-cover w-full h-full rounded-r-3xl"></div>
        </div>

             <!-- Right Side - Login Form -->
             <div class="flex flex-col justify-center py-8 md:w-1/2 lg:w-2/5 w-full items-center bg-white shadow-lg">
            <p class="font-bold text-3xl w-full text-center text-[#00446b]">NexfleetDynamics</p>
            <p class="font-semibold text-lg text-center mt-2 text-gray-600">Two Factor Authentication</p>
            <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Set Up Two-Factor Authentication</div>

                <div class="card-body">
                    <p>Scan this QR code with your authenticator app:</p>
                    <div class="text-center mb-4">
                        {!! $qrCode !!}
                    </div>
                    <p>Or enter this code manually: <code>{{ $secret }}</code></p>
                    
                    <form method="POST" action="{{ route('2fa.enable') }}">
                        @csrf
                        <input type="hidden" name="secret" value="{{ $secret }}">
                        
                        <div class="form-group">
                            <label for="one_time_password">Enter OTP to verify</label>
                            <input type="text" class="form-control" id="one_time_password" name="one_time_password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary mt-3">Enable 2FA</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

