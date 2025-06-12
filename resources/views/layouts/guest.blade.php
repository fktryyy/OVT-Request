<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Overtime Request</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>
<body class="bg-white text-gray-800">

  <!-- Navbar -->
  <nav class="bg-white shadow-md px-4 py-3 fixed top-0 left-0 right-0 z-10">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <div class="text-xl font-semibold text-blue-600">
        Overtime Request
      </div>
      <div>
        <a href="{{ route('login') }}"
           class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
          Login
        </a>
      </div>
    </div>
  </nav>

  <!-- Content Section -->
  <main class="pt-20">
    @yield('content')
  </main>

</body>
</html>
