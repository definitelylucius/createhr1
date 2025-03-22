<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/login.css">
    <title>Login</title>





</head>

<body >
<div class="h-screen flex md:flex-row flex-col ">
    
        <div class="lg:w-3/5 h-screen custom-py-1p lg:block hidden">
            <div class="bus-background bg-cover w-full h-full rounded-r-3xl"></div>
        </div>

        <div class="flex flex-col py-4 md:w-1/2 lg:w-2/5 w-full items-center bg-white">
            <p class="font-bold lg:text-4xl text-2xl w-full text-center text-[#00446b]">Bus Transportation Management System</p>
            <p class="font-semibold lg:text-3xl text-xl text-center mt-10 text-[#00446b]">&lt;Human Resource 1&gt;</p>

            <form method="POST" action="{{ route('login') }}" class="xl:w-4/6 lg:w-5/6 sm:w-2/3 py-4 rounded-3xl shadow-lg mt-10 flex flex-col items-center border">
            @csrf
           
                <p class="text-center mb-4 text-xl text-[#00446b]">Sign In</p>
                <hr class="border w-full border-[#00446b]">

                <!-- Email Address -->
                <div class="mt-8 w-4/5">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="mt-1 block w-full bg-transparent rounded-md border p-2" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4 w-4/5 text-color-black">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="mt-1 block w-full bg-transparent rounded-md border p-2" type="password" name="password" required autocomplete="current-password" placeholder="Password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="w-4/5 flex justify-between mt-4 lg:mb-12 mb-12">
                    <label class="flex items-center bg-white">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                        <span class="ml-2 text-sm hover:text-gray-300/50 rounded-md text-[#00446b]">{{ __('Remember me') }}</span>
                    </label>
                    <a class="text-sm hover:text-gray-300/50 rounded-md text-[#00446b]" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                </div>

                <div class="flex items-center mt-4 mb-8 w-4/5">
                    <button type="submit" class="w-full font-medium p-2 rounded-md border bg-[#00446b] text-white">
                        <p class="text-center">Log In</p>
                    </button>
                </div>
                <div class="w-4/5 flex justify-between mt-4 lg:mb-12 mb-12">
                <label class="flex items-center">
                @if (Route::has('register'))
                <a class="text-sm hover:text-gray-300/50 rounded-md text-[#00446b]" href="{{ route('register') }}">Don't have an account? create a new one.</a>
                @endif
                <div>
                </label>

            </form>
        </div>
    </div>

</body>

</html>