<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-2xl font-semibold text-center">Register New User</h1>
            
            <form method="POST" action="{{ route('register.submit') }}">
                @csrf
                <div class="mt-4">
                    <label for="name" class="block text-sm">Name</label>
                    <input type="text" name="name" id="name" class="w-full mt-1 p-2 border border-gray-300 rounded" required>
                </div>

                <div class="mt-4">
                    <label for="email" class="block text-sm">Email</label>
                    <input type="email" name="email" id="email" class="w-full mt-1 p-2 border border-gray-300 rounded" required>
                </div>

                <div class="mt-4">
                    <label for="password" class="block text-sm">Password</label>
                    <input type="password" name="password" id="password" class="w-full mt-1 p-2 border border-gray-300 rounded" required>
                </div>

                <div class="mt-4">
                    <label for="password_confirmation" class="block text-sm">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full mt-1 p-2 border border-gray-300 rounded" required>
                </div>

                <div class="mt-4">
                    <label for="role" class="block text-sm">Role</label>
                    <select name="role" id="role" class="w-full mt-1 p-2 border border-gray-300 rounded" required>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full p-2 bg-blue-500 text-white rounded">Register User</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
