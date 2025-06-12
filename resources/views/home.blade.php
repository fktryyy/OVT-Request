@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-start justify-center bg-gray-100 dark:bg-gray-900">
    <div class="max-w-md w-full bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mt-1">
        <h1 class="text-2xl font-semibold text-center text-gray-900 dark:text-gray-100">
            Welcome, {{ Auth::user()->name }}
        </h1>
        <p class="mt-4 text-center text-gray-700 dark:text-gray-300">
            You are logged in as <strong>{{ Auth::user()->username }}</strong>
        </p>

        @if(Auth::user()->role == 'admin')
            <div class="mt-6 text-center">
                <a href="{{ route('login-history.index') }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">
                    View Login History
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
