<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Admin Dinsos' }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="h-screen w-screen bg-[#f7f9fc] text-[#333] flex overflow-hidden">
  <!-- Sidebar -->
  <aside class="w-[260px] shrink-0 h-full bg-white border-r border-gray-200 flex flex-col">
    <div class="flex items-center gap-3 px-5 py-6">
      <img src="{{ asset('images/logo-dinsos.png') }}" class="h-10 object-contain" alt="Logo Dinsos">
      <h1 class="font-semibold text-lg">Admin Dinsos</h1>
    </div>
    <nav class="px-3 space-y-2">
      <a href="{{ route('admin.dashboard') }}"
         class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-100 {{ request()->routeIs('admin.dashboard')?'bg-blue-600 text-white hover:bg-blue-600':'' }}">
        <i class='bx bxs-dashboard text-2xl'></i> <span>Dasbor</span>
      </a>
      <a href="{{ route('admin.lansia.index') }}"
         class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-100 {{ request()->routeIs('admin.lansia.*')?'bg-blue-600 text-white hover:bg-blue-600':'' }}">
        <i class='bx bxs-data text-2xl'></i> <span>Kelola Data</span>
      </a>
      @if(auth()->user()->role==='admin')
      <a href="{{ route('admin.staff.index') }}"
         class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-100 {{ request()->routeIs('admin.staff.*')?'bg-blue-600 text-white hover:bg-blue-600':'' }}">
        <i class='bx bxs-user-account text-2xl'></i> <span>Manajemen Staf</span>
      </a>
      @endif
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="w-full text-left flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-100">
          <i class='bx bx-log-out text-2xl'></i> <span>Logout</span>
        </button>
      </form>
    </nav>
  </aside>

  <!-- Main -->
  <main class="flex-1 h-full overflow-y-auto">
    <header class="sticky top-0 z-10 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
      <h2 class="text-xl font-semibold">@yield('pageTitle','Admin Panel')</h2>
      <div class="flex items-center gap-3">
        <span class="text-sm text-gray-600">{{ auth()->user()->email }}</span>
        <img src="{{ asset('images/user-avatar.png') }}" class="w-10 h-10 rounded-full object-cover" alt="avatar">
      </div>
    </header>

    <section class="p-6">
      @if(session('ok'))
        <div class="mb-4 rounded-md bg-green-50 text-green-700 px-4 py-2">{{ session('ok') }}</div>
      @endif
      @if(session('err'))
        <div class="mb-4 rounded-md bg-red-50 text-red-700 px-4 py-2">{{ session('err') }}</div>
      @endif

      @yield('content')
    </section>
  </main>
</body>
</html>
