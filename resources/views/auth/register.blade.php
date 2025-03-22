<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <title>Register</title>
</head>

<body>
    <div class="h-screen flex md:flex-row flex-col">
        
        <!-- Left Side - Bus Background -->
        <div class="lg:w-3/5 h-screen custom-py-1p lg:block hidden">
            <div class="bus-background bg-cover w-full h-full rounded-r-3xl"></div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="flex flex-col py-4 md:w-1/2 lg:w-2/5 w-full items-center bg-white">
            <p class="font-bold lg:text-4xl text-2xl w-full text-center text-[#00446b]">Bus Transportation Management System</p>
            <p class="font-semibold lg:text-3xl text-xl text-center mt-10 text-[#00446b]">&lt;Human Resource 1&gt;</p>

            <form method="POST" action="{{ route('register') }}" class="xl:w-4/6 lg:w-5/6 sm:w-2/3 py-4 rounded-3xl shadow-lg mt-10 flex flex-col items-center border">
                @csrf

                <p class="text-center mb-4 text-xl text-[#00446b]">Register</p>
                <hr class="border w-full border-[#00446b]">

                <!-- Full Name -->
                <div class="mt-6 w-4/5">
                    <label class="text-gray-700 font-medium">Full Name</label>
                    <input type="text" name="name" required class="mt-1 block w-full bg-transparent rounded-md border p-2" placeholder="Your Name">
                </div>

                <!-- Email -->
                <div class="mt-4 w-4/5">
                    <label class="text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" required class="mt-1 block w-full bg-transparent rounded-md border p-2" placeholder="Your Email">
                </div>

                <!-- Password -->
                <div class="mt-4 w-4/5">
                    <label class="text-gray-700 font-medium">Password</label>
                    <input type="password" name="password" required class="mt-1 block w-full bg-transparent rounded-md border p-2" placeholder="Your Password">
                </div>

                <!-- Confirm Password -->
                <div class="mt-4 w-4/5">
                    <label class="text-gray-700 font-medium">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="mt-1 block w-full bg-transparent rounded-md border p-2" placeholder="Confirm Password">
                </div>

                <!-- Register Button -->
                <div class="flex items-center mt-6 mb-8 w-4/5">
                    <button type="submit" class="w-full font-medium p-2 rounded-md border bg-[#00446b] text-white">
                        <p class="text-center">Register</p>
                    </button>
                </div>

                <!-- Already Registered? -->
                <div class="w-4/5 text-center">
                    <a href="{{ route('login') }}" class="text-sm hover:text-gray-300/50 rounded-md text-[#00446b]">Already have an account? Sign in.</a>
                </div>

            </form>
        </div>
    </div>

</body>

</html>


