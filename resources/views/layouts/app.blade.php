<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'RestoQR') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) 
</head>

<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-slate-100 min-h-screen flex flex-col">
    {{-- Navbar --}}
    <nav class="bg-black shadow-lg border-b border-slate-700">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="text-2xl font-bold text-amber-400">🍽️ RestoQR</div>
            <div class="flex gap-4">
                <a href="{{ url('/') }}" class="px-4 py-2 rounded hover:bg-slate-800 transition text-sm">
                    📋 Order
                </a>
                <a href="{{ route('kitchen.index') }}" class="px-4 py-2 rounded hover:bg-slate-800 transition text-sm">
                    👨‍🍳 Kitchen
                </a>
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded hover:bg-slate-800 transition text-sm">
                    ⚙️ Admin
                </a>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded hover:bg-slate-800 transition text-sm">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="flex-1 container mx-auto p-4">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-black border-t border-slate-700 py-4 mt-8">
        <div class="container mx-auto text-center text-sm text-slate-500">
            &copy; 2025 RestoQR - Restaurant Order Management System
        </div>
    </footer>

    @yield('scripts') 
</body>

</html>