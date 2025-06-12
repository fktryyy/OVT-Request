@extends('layouts.guest')

@section('content')
<style>
    body, h1, h2, p, label, input, button {
        font-family: 'Times New Roman', serif;
    }
</style>

<div class="flex items-center justify-center min-h-screen bg-gray-50">
    <div class="w-full max-w-4xl bg-white p-8 rounded-lg shadow-lg flex">
        <!-- Illustration image with green background -->
        <div class="hidden md:block w-1/2 bg-green-500 p-8 rounded-l-lg">
            <img src="{{ asset('img/20944201.jpg') }}" alt="Illustration" class="rounded-lg w-full h-auto shadow-md">
        </div>

        <!-- Login form with white background -->
        <div class="w-full md:w-1/2 px-6 py-8 bg-white rounded-r-lg">
            <h2 class="text-3xl font-semibold text-center text-blue-800 mb-6">Sign In to Your Account</h2>

            @if ($errors->any())
                <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-800">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}"
                           class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           required autofocus>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-800">Password</label>
                    <input type="password" name="password"
                           class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           required>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg focus:outline-none transition">
                    Sign In
                </button>

                @auth
                    @if(auth()->user()->role === 'admin')
                        <p class="mt-4 text-center text-sm text-gray-600">
                            As an admin, you can register new users.
                        </p>
                    @endif
                @endauth
            </form>
        </div>
    </div>
</div>
@endsection
