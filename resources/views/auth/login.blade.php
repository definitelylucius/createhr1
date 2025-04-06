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
            <p class="font-semibold text-lg text-center mt-2 text-gray-600">Welcome Back</p>

            <form method="POST" action="{{ route('login') }}" class="w-4/5 py-6 rounded-lg shadow-lg mt-6 flex flex-col items-center bg-white border border-gray-200">
                @csrf
                
                <p class="text-center mb-4 text-lg text-[#00446b] font-semibold">Sign In to Your Account</p>
                <hr class="border w-full border-gray-300">

                <!-- Email Field -->
                <div class="mt-6 w-4/5">
                    <label class="text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" required class="mt-1 block w-full bg-gray-50 text-gray-900 rounded-md border p-2 focus:ring-[#00446b] focus:border-[#00446b]" placeholder="Your Email">
                </div>

                <!-- Password Field -->
                <div class="mt-4 w-4/5">
                    <label class="text-gray-700 font-medium">Password</label>
                    <input type="password" name="password" required class="mt-1 block w-full bg-gray-50 text-gray-900 rounded-md border p-2 focus:ring-[#00446b] focus:border-[#00446b]" placeholder="Your Password">
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="mt-4 w-4/5 flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" class="h-4 w-4 text-[#00446b] border-gray-300 rounded">
                        <label class="ml-2 text-gray-700 text-sm">Remember Me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-sm text-[#00446b] hover:text-gray-400">Forgot Password?</a>
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
</body>

</html>
