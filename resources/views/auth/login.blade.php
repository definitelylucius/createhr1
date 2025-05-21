<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

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
        <div 
            class="bg-cover bg-center w-full h-full rounded-r-3xl" 
            style="background-image: url('/imagess/bus-background.jpg');">
        </div>
    </div>

      <!-- Right Side - Login Form -->
<div class="flex flex-col justify-center py-8 md:w-1/2 lg:w-2/5 w-full items-center bg-white shadow-lg">
    <p class="font-bold text-3xl w-full text-center text-[#00446b]">NexfleetDynamics</p>
    <p class="font-semibold text-lg text-center mt-2 text-gray-600">Welcome Back</p>

    <form method="POST" action="{{ route('login') }}" class="w-4/5 py-6 rounded-lg shadow-lg mt-6 flex flex-col items-center bg-white border border-gray-200">
        @csrf
        
   

        <p class="text-center mb-4 text-lg text-[#00446b] font-semibold">Sign In to Your Account</p>
        <hr class="border w-full border-gray-300">

        <!-- Email Field -->
        <div class="mt-6 w-4/5">
            <label class="text-gray-700 font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required 
                   class="mt-1 block w-full bg-gray-50 text-gray-900 rounded-md border p-2 focus:ring-[#00446b] focus:border-[#00446b] @error('email') border-red-500 @enderror" 
                   placeholder="Your Email">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Field with Show Password Toggle -->
        <div class="mt-4 w-4/5">
            <label class="text-gray-700 font-medium">Password</label>
            <div class="relative">
                <input id="password" type="password" name="password" required 
                       class="mt-1 block w-full bg-gray-50 text-gray-900 rounded-md border p-2 pr-10 focus:ring-[#00446b] focus:border-[#00446b] @error('password') border-red-500 @enderror" 
                       placeholder="Your Password">
                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-3 pt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

    

                <!-- Login Button -->
                <div class="flex items-center mt-6 w-4/5">
                    <button type="submit" class="w-full font-medium p-3 rounded-md border bg-[#00446b] text-white">
                        Sign In
                    </button>
                </div>

                <!-- Don't Have an Account -->
                <div class="w-4/5 text-center mt-6">
                    <p class="text-sm text-gray-600">Don't have an account? 
                        <a href="{{ route('register') }}" class="font-medium text-[#00446b] hover:text-gray-400">Sign Up.</a>
                    </p>
                </div>
            </form>

            <!-- Back to Welcome Page -->
            <div class="w-4/5 text-center mt-4">
            <a href="{{ url('/') }}" class="text-sm text-[#00446b] hover:text-gray-400">Back to NexfleetDynamics</a>

            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        }
    </script>
</body>

</html>