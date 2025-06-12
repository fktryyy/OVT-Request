@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50">
    <div class="w-full max-w-4xl bg-white p-8 rounded-lg shadow-lg flex">
        <!-- Illustration Image -->
        <div class="hidden md:block w-1/2 bg-blue-500 p-10 rounded-l-lg">
            <img src="{{ asset('img/10973590.jpg') }}" alt="Illustration" class="rounded-lg w-full h-auto shadow-md">
        </div>

        <!-- Registration Form -->
        <div class="w-full md:w-1/2 px-6 py-8 bg-white rounded-r-lg">
            <h2 class="text-3xl font-semibold text-center text-blue-800 mb-6">Create New Account</h2>

            @if ($errors->any())
                <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required autofocus>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">NIP</label>
                    <input type="text" name="nip" value="{{ old('nip') }}"
                           class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password"
                           class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                           class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>

                    <label class="block mt-4 text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="role" class="w-full mt-1 p-2 border border-gray-300 rounded" required>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <div class="flex justify-center mt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                      Register
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
