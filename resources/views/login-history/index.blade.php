@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-start justify-center bg-gray-50 dark:bg-gray-900">
        <div class="max-w-5xl w-full bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg mt-4">
            <h1 class="text-3xl font-bold text-center text-gray-900 dark:text-gray-100 mb-6">Login History</h1>

            @if ($loginHistory->isNotEmpty())
                @php
                    // Kelompokkan login history berdasarkan hari
                    $groupedHistory = $loginHistory->groupBy(function ($item) {
                        return \Carbon\Carbon::parse($item->logged_in_at)->format('l'); // Format hari (Senin, Selasa, dll)
                    });
                @endphp

                @foreach ($groupedHistory as $day => $logs)
                    <div class="my-6">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 capitalize">{{ $day }}</h2>
                        <div class="overflow-x-auto mt-3">
                            <table class="min-w-full table-auto text-sm text-left text-gray-700 dark:text-gray-300">
                                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                                    <tr>
                                        <th class="px-4 py-3">Name</th>
                                        <th class="px-4 py-3">Login Time</th>
                                        <th class="px-4 py-3">IP Address</th>
                                        <th class="px-4 py-3 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($logs as $history)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <td class="px-4 py-3">{{ $history->user->name }}</td>
                                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($history->logged_in_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
                                            <td class="px-4 py-3">{{ $history->ip_address }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <form action="{{ route('login-history.delete', $history->id) }}" method="POST" onsubmit="return confirm('Delete this entry?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-xs">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center text-gray-500 dark:text-gray-400 mt-4">No login history available.</p>
            @endif
        </div>
    </div>
@endsection
