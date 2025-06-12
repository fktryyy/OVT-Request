<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SSM Overtime Request</title>
  
  {{-- Tailwind CSS --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Alpine.js --}}
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  {{-- Chart.js --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  {{-- Heroicons --}}
  <script src="https://unpkg.com/@heroicons/vue@2.0.18/dist/heroicons.min.js"></script>

  <style>
    body { margin: 0; padding: 0; }
  </style>
</head>


<body class="flex h-full bg-gray-100 dark:bg-gray-900 font-sans text-gray-800 dark:text-gray-100">

  {{-- SIDEBAR --}}
  <aside x-data="{ open: true }" 
         :class="open ? 'w-64' : 'w-20'" 
         class="bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col transition-all duration-300 shadow-lg z-50">

    <!-- Header Sidebar -->
    <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200 dark:border-gray-700">
      <div class="flex items-center space-x-3" :class="open ? 'block' : 'hidden'">
        <img src="{{ asset('img/1212.png') }}" alt="Logo" class="h-15 w-20 object-contain rounded-md shadow" />
        <span class="font-bold text-lg text-blue-600">Overtime </span>
      </div>
      <button @click="open = !open" class="text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded p-2">
        <!-- Ikon menu (open = true) -->
<svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
</svg>

<!-- Ikon panah keluar (open = false) -->
<svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
</svg>

      </button>
    </div>

    <!-- Navigasi -->
    <nav class="flex-1 overflow-auto p-4">
      <h2 class="text-xs font-semibold text-indigo-500 mb-4 uppercase tracking-wide">Menu</h2>

      <a href="{{ route('home') }}" class="flex items-center space-x-3 px-3 py-2 rounded-md hover:bg-indigo-500 hover:text-white transition">
        <span class="text-lg">🏠</span>
        <span x-show="open" class="text-sm font-medium">Home</span>
      </a>

      <div x-data="{ submenuOpen: false }" class="mt-2">
        <button @click="submenuOpen = !submenuOpen"
                class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-indigo-500 hover:text-white text-sm font-medium transition"
                :class="{ 'bg-indigo-100 dark:bg-gray-700 text-indigo-600': submenuOpen }">
          <div class="flex items-center space-x-3">
            <span class="text-lg">⏰</span>
            <span x-show="open">Overtime</span>
          </div>
          <span x-show="open" x-text="submenuOpen ? '🔽' : '▶️'"></span>
        </button>
        <div x-show="submenuOpen" x-collapse class="ml-7 mt-2 space-y-1 text-sm">
          <a href="{{ route('overtime.form') }}" class="block px-3 py-1 rounded hover:bg-indigo-100 dark:hover:bg-gray-600">
            📝 Request Form
          </a>
          <a href="{{ route('request.by.department') }}" class="block px-3 py-1 rounded hover:bg-indigo-100 dark:hover:bg-gray-600">
            📝 Request Form By Departement
          </a>
          <a href="{{ route('overtime.data') }}" class="block px-3 py-1 rounded hover:bg-indigo-100 dark:hover:bg-gray-600">
            📊 Data Overtime
          </a>
          
        </div>
      </div>

      @auth
        @if (Auth::user()->role === 'admin')
        <a href="{{ route('register') }}" class="flex items-center space-x-3 px-3 py-2 rounded-md hover:bg-indigo-100 dark:hover:bg-gray-700 mt-2">
          <span>➕</span>
          <span x-show="open">Add User</span>
        </a>
        @endif
      @endauth
    </nav>

    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 text-xs text-center text-gray-500">
      <span x-show="open">&copy; 2025 Overtime Request<br/>All rights reserved.</span>
    </div>
  </aside>
  {{-- MAIN --}}
  <div class="flex-1 flex flex-col overflow-hidden">
    {{-- HEADER --}}
    <header class="flex items-center justify-between px-6 py-3 bg-white dark:bg-gray-800 border-b dark:border-gray-700">
      <h1 class="text-lg font-semibold"></h1>
      <div class="flex items-center space-x-4">
        {{-- Dark Mode Toggle --}}
        <button id="darkModeToggle" class="w-5 h-5 rounded-full bg-gray-200 dark:bg-gray-700 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 shadow transition flex items-center justify-center">
          <span class="block dark:hidden">🌙</span>
          <span class="hidden dark:block">🌞</span>
        </button>        
        {{-- User Auth Info --}}
        @auth
  <div class="flex items-center space-x-2 text-sm">
    @if(Auth::user()->role === 'admin')
      <span class="w-4 h-4 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-[9px]">A</span>
    @else
      <span class="w-4 h-4 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-[9px]">U</span>
    @endif
    <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
    
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" title="Logout" class="ml-2 transition hover:opacity-80">
        <img src="{{ asset('img/download.jpeg') }}" alt="Logout" class="w-4 h-4 rounded-full shadow-sm" />
      </button>
    </form>
  </div>
@endauth
      </div>
    </header>

    {{-- CONTENT --}}
    <main class="flex-1 p-6 overflow-auto">
      @yield('content')
    </main>
  </div>
  <script>
    tailwind.config = {
      darkMode: 'class',
    }
  </script>
  <script>
    const html = document.documentElement;
    const toggleBtn = document.getElementById('darkModeToggle');

    // Load theme from localStorage
    if (localStorage.getItem('theme') === 'dark') {
      html.classList.add('dark');
    }

    toggleBtn.addEventListener('click', () => {
      html.classList.toggle('dark');
      localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    });
  </script>
</body>
</html>
