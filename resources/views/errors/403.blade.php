<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Error 403 - Access Denied</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes pulseGlow {
      0%, 100% {
        text-shadow: 0 0 0 rgba(220, 38, 38, 0);
      }
      50% {
        text-shadow: 0 0 15px rgba(220, 38, 38, 0.7);
      }
    }
    .pulse-glow {
      animation: pulseGlow 3s ease-in-out infinite;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-red-50 via-white to-red-50 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white max-w-md w-full p-10 rounded-3xl shadow-2xl border border-red-200 text-center">
    <h1 class="text-9xl font-extrabold text-red-700 mb-4 select-none pulse-glow">403</h1>
    <h2 class="text-4xl font-semibold text-blue-600 mb-3">Access Denied</h2>
    <p class="text-gray-600 mb-8 leading-relaxed">
      You do not have permission to access this page,<br />
      only administrators are allowed to access it.
    </p>
    <a href="{{ route('home') }}"
      class="inline-flex items-center justify-center gap-3 bg-red-700 text-white font-bold py-4 px-10 rounded-full shadow-lg hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 transition duration-300 ease-in-out select-none">
      <!-- Left arrow icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
      </svg>
      Back to Home
    </a>
  </div>
</body>
</html>
