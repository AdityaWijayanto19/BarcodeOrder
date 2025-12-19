<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'RestoQR') }} - Admin</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Alpine.js untuk Sidebar & Dropdown Logic --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-black text-white font-['Poppins'] antialiased" x-data="{ sidebarOpen: true, cmsOpen: false }">

    <div class="flex min-h-screen relative">
        
        <!-- SIDEBAR -->
        <aside 
            :class="sidebarOpen ? 'w-64' : 'w-20'" 
            class="fixed left-0 top-0 h-screen bg-[#1A1A1A] border-r border-white/10 transition-all duration-300 z-50 flex flex-col shadow-2xl">
            
            <!-- Logo Area -->
            <div class="p-6 flex items-center gap-4 border-b border-white/5 h-20 overflow-hidden">
                <div class="bg-[#fa9a08] p-2 rounded-xl shrink-0">
                    <i class="ri-shield-flash-line text-black text-xl"></i>
                </div>
                <span x-show="sidebarOpen" x-transition.opacity class="font-bold text-lg tracking-widest italic whitespace-nowrap">
                    CLASS<span class="text-[#fa9a08]">BILLIARD</span>
                </span>
            </div>

            <!-- Navigation Area -->
            <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-2 scrollbar-hide">
                
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-4 p-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#fa9a08] text-black font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="ri-dashboard-line text-xl"></i>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Dashboard</span>
                </a>

                <!-- Kelola Meja (Tables) -->
                <a href="{{ route('tables.index') }}" 
                   class="flex items-center gap-4 p-3 rounded-xl transition-all {{ request()->routeIs('tables.*') ? 'bg-[#fa9a08] text-black font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="ri-qr-scan-2-line text-xl"></i>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Kelola Meja</span>
                </a>

                <!-- Kelola Menu -->
                <a href="#" 
                   class="flex items-center gap-4 p-3 rounded-xl text-slate-400 hover:bg-white/5 hover:text-white transition-all">
                    <i class="ri-restaurant-line text-xl"></i>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Kelola Menu</span>
                </a>

                <!-- Report Order -->
                <a href="#" 
                   class="flex items-center gap-4 p-3 rounded-xl text-slate-400 hover:bg-white/5 hover:text-white transition-all">
                    <i class="ri-file-list-3-line text-xl"></i>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Report Order</span>
                </a>

                <!-- CMS Dropdown -->
                <div class="space-y-1">
                    <button 
                        @click="cmsOpen = !cmsOpen; if(!sidebarOpen) sidebarOpen = true"
                        class="w-full flex items-center gap-4 p-3 rounded-xl text-slate-400 hover:bg-white/5 hover:text-white transition-all">
                        <i class="ri-layout-grid-line text-xl"></i>
                        <div x-show="sidebarOpen" class="flex justify-between items-center w-full">
                            <span class="font-medium text-sm">CMS Website</span>
                            <i class="ri-arrow-down-s-line transition-transform duration-300" :class="cmsOpen ? 'rotate-180' : ''"></i>
                        </div>
                    </button>
                    
                    <!-- Submenu CMS -->
                    <div x-show="cmsOpen && sidebarOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" class="pl-12 pr-4 py-2 space-y-3">
                        <a href="#" class="block text-xs text-slate-500 hover:text-[#fa9a08] transition-colors">Hero Section</a>
                        <a href="#" class="block text-xs text-slate-500 hover:text-[#fa9a08] transition-colors">About Section</a>
                        <a href="#" class="block text-xs text-slate-500 hover:text-[#fa9a08] transition-colors">Footer Settings</a>
                    </div>
                </div>
            </nav>

            <!-- Bottom Actions (Logout & Toggle) -->
            <div class="p-3 border-t border-white/5 space-y-2">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-4 p-3 rounded-xl text-red-500 hover:bg-red-500/10 transition-all">
                        <i class="ri-logout-circle-line text-xl"></i>
                        <span x-show="sidebarOpen" class="font-medium text-sm">Logout</span>
                    </button>
                </form>

                <button 
                    @click="sidebarOpen = !sidebarOpen"
                    class="w-full flex items-center gap-4 p-3 rounded-xl text-slate-500 hover:bg-white/5 transition-all">
                    <i :class="sidebarOpen ? 'ri-side-bar-fill' : 'ri-side-bar-line rotate-180'" class="text-xl"></i>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Collapse Menu</span>
                </button>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div 
            :class="sidebarOpen ? 'ml-64' : 'ml-20'" 
            class="flex-1 transition-all duration-300 flex flex-col min-h-screen">
            
            <!-- TOP BAR / HEADER -->
            <header class="h-20 bg-black/50 backdrop-blur-md border-b border-white/5 flex items-center justify-between px-8 sticky top-0 z-40">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-mono text-slate-500 uppercase tracking-widest">System Time:</span>
                    <span class="text-xs font-mono text-[#fa9a08]">{{ date('H:i') }}</span>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Notifications -->
                    <button class="relative text-slate-400 hover:text-white transition-colors">
                        <i class="ri-notification-3-line text-xl"></i>
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-[#fa9a08] text-black text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-black">2</span>
                    </button>

                    <!-- User Profile -->
                    <div class="flex items-center gap-3 pl-6 border-l border-white/10">
                        <div class="text-right">
                            <p class="text-sm font-bold">{{ Auth::user()->name ?? 'Admin Class' }}</p>
                            <p class="text-[10px] text-[#fa9a08] uppercase font-bold tracking-tighter">Super Admin</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#fa9a08] to-orange-600 flex items-center justify-center text-black font-bold shadow-lg">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- CONTENT -->
            <main class="flex-1 p-8">
                @yield('content')
            </main>

            <!-- FOOTER -->
            <footer class="p-8 border-t border-white/5 text-center">
                <p class="text-xs text-slate-600 uppercase tracking-widest">
                    &copy; {{ date('Y') }} <span class="text-[#fa9a08] font-bold">Class Billiard</span> - RestoQR Management System
                </p>
            </footer>
        </div>
    </div>

    @yield('scripts')
</body>

</html>