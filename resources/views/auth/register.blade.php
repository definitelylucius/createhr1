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
    
    <title>Register</title>
</head>

<body>
    <div class="h-screen flex flex-col md:flex-row bg-gray-100">
        
        <!-- Left Side - Bus Background -->
        <div class="hidden lg:block lg:w-3/5 h-screen">
            <div class="bus-background bg-cover w-full h-full rounded-r-3xl"></div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="flex flex-col justify-center py-8 w-full md:w-1/2 lg:w-2/5 items-center bg-white shadow-lg">
        <p class="font-bold text-3xl text-center text-[#00446b] md:w-full">NexfleetDynamics</p>
<p class="font-semibold text-lg text-center mt-2 text-gray-600 md:w-full">Your Future Starts Here</p>



            <!-- Larger Form with more spacing and width -->
            <form method="POST" action="{{ route('register') }}" class="w-full max-w-lg py-8 px-8 rounded-lg shadow-lg mt-6 flex flex-col items-center bg-white border border-gray-200">
                @csrf
                
                <p class="text-center mb-6 text-xl text-[#00446b] font-semibold">Register</p>
                <hr class="border w-full border-[#00446b] mb-6">

                <!-- First Name & Last Name in One Row -->
                <div class="mt-6 w-full grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label class="text-gray-700 font-medium">First Name</label>
                        <input type="text" name="first_name" required class="mt-1 block w-full bg-gray-50 text-gray-900 rounded-md border p-3 focus:ring-[#00446b] focus:border-[#00446b]" placeholder="First Name">
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="text-gray-700 font-medium">Last Name</label>
                        <input type="text" name="last_name" required class="mt-1 block w-full bg-gray-50 text-gray-900 rounded-md border p-3 focus:ring-[#00446b] focus:border-[#00446b]" placeholder="Last Name">
                    </div>
                </div>

                <!-- Email -->
                <div class="mt-6 w-full">
                    <label class="text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" required class="mt-1 block w-full bg-gray-50 text-gray-900 rounded-md border p-3 focus:ring-[#00446b] focus:border-[#00446b]" placeholder="Your Email">
                </div>

                <!-- Password -->
                <div class="mt-6 w-full">
                    <label class="text-gray-700 font-medium">Password</label>
                    <input type="password" name="password" required class="mt-1 block w-full bg-gray-50 text-gray-900 rounded-md border p-3 focus:ring-[#00446b] focus:border-[#00446b]" placeholder="Your Password">
                </div>

                <!-- Confirm Password -->
                <div class="mt-6 w-full">
                    <label class="text-gray-700 font-medium">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="mt-1 block w-full bg-gray-50 text-gray-900 rounded-md border p-3 focus:ring-[#00446b] focus:border-[#00446b]" placeholder="Confirm Password">
                </div>

                <!-- Register Button -->
                <div class="flex items-center mt-6 w-full">
                    <button type="submit" class="w-full font-medium p-4 rounded-md border bg-[#00446b] text-white">
                        Register
                    </button>
                </div>

                <!-- Already Registered? -->
                <div class="w-full text-center mt-6">
                    <p class="text-sm text-gray-600">Already have an account? 
                        <a href="{{ route('login') }}" class="font-medium text-[#00446b] hover:text-gray-400">Sign In.</a>
                    </p>
                </div>
            </form>

            <!-- Back to Welcome Page -->
            <div class="w-full text-center mt-4">
                <a href="{{ url('/') }}" class="text-sm text-[#00446b] hover:text-gray-400">Back to NexfleetDynamics</a>
            </div>
        </div>
    </div>
</body>

</html>
