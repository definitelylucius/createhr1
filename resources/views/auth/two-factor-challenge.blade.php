@extends('layouts.app') {{-- Or use your layout file --}}

@section('content')
<div class="flex justify-center items-center h-screen bg-gray-100">
    <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4 text-center">Two-Factor Authentication</h2>
        <p class="mb-4 text-sm text-gray-600 text-center">
            Please enter the 6-digit verification code sent to your email.
        </p>

        @if ($errors->any())
            <div class="mb-4 text-red-500 text-sm">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route('two-factor.verify') }}">

            @csrf
            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700">Verification Code</label>
                <input type="text" id="code" name="code" maxlength="6" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                Verify
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
            @csrf
            <button type="submit" class="text-sm text-gray-500 hover:underline">
                Cancel and go back to login
            </button>
        </form>
    </div>
</div>
@endsection
