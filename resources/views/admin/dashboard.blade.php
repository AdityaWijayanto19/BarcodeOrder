@extends('layouts.app')

@section('content')
    <!-- Page Header (Hanya Judul Halaman) -->
    <div class="mb-10">
        <h1 class="text-3xl font-bold italic tracking-tight uppercase">
            Dashboard <span class="text-[#fa9a08]">Overview</span>
        </h1>
        <p class="text-slate-500 text-sm mt-1">Pantau performa Class Billiard hari ini.</p>
    </div>

    <!-- STATS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Stat 1: Order Baru -->
        <div class="bg-[#1A1A1A] p-6 rounded-2xl border border-white/5 hover:border-[#fa9a08]/30 transition-all group shadow-lg">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-500/10 rounded-xl text-blue-500">
                    <i class="ri-notification-badge-line text-2xl"></i>
                </div>
                <span class="text-xs text-slate-500 font-medium">Order Baru</span>
            </div>
            <h3 class="text-3xl font-bold">{{ $stats['new_orders'] }}</h3>
            <p class="text-blue-500 text-xs mt-2 font-medium">Perlu segera diproses</p>
        </div>

        <!-- Stat 2: Cooking -->
        <div class="bg-[#1A1A1A] p-6 rounded-2xl border border-white/5 hover:border-[#fa9a08]/30 transition-all group shadow-lg">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-yellow-500/10 rounded-xl text-yellow-500">
                    <i class="ri-fire-line text-2xl"></i>
                </div>
                <span class="text-xs text-slate-500 font-medium">Cooking</span>
            </div>
            <h3 class="text-3xl font-bold">{{ $stats['cooking_orders'] }}</h3>
            <p class="text-yellow-500 text-xs mt-2 font-medium">Sedang di dapur</p>
        </div>

        <!-- Stat 3: Selesai -->
        <div class="bg-[#1A1A1A] p-6 rounded-2xl border border-white/5 hover:border-[#fa9a08]/30 transition-all group shadow-lg">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-green-500/10 rounded-xl text-green-500">
                    <i class="ri-checkbox-circle-line text-2xl"></i>
                </div>
                <span class="text-xs text-slate-500 font-medium">Selesai</span>
            </div>
            <h3 class="text-3xl font-bold">{{ $stats['done_orders'] }}</h3>
            <p class="text-green-500 text-xs mt-2 font-medium">Pesanan hari ini</p>
        </div>

        <!-- Stat 4: Revenue -->
        <div class="bg-[#1A1A1A] p-6 rounded-2xl border border-[#fa9a08]/20 bg-gradient-to-br from-[#1A1A1A] to-[#fa9a08]/5 transition-all shadow-lg">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-[#fa9a08]/10 rounded-xl text-[#fa9a08]">
                    <i class="ri-money-dollar-circle-line text-2xl"></i>
                </div>
                <span class="text-xs text-slate-400 font-medium">Revenue</span>
            </div>
            <h3 class="text-2xl font-bold text-[#fa9a08]">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
            <p class="text-slate-400 text-xs mt-2 font-medium">Total Pendapatan</p>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <a href="{{ route('admin.barcodes') }}"
            class="group relative overflow-hidden bg-[#1A1A1A] p-8 rounded-3xl border border-white/5 hover:border-[#fa9a08]/50 transition-all shadow-xl">
            <div class="relative z-10">
                <i class="ri-qr-code-line text-4xl text-[#fa9a08] mb-4 block"></i>
                <h4 class="text-xl font-bold mb-2">QR Code Meja</h4>
                <p class="text-slate-500 text-sm">Lihat & Kelola barcode untuk setiap meja billiard.</p>
            </div>
            <div class="absolute -right-8 -bottom-8 text-white/5 group-hover:text-[#fa9a08]/10 transition-colors">
                <i class="ri-qr-code-line text-[160px]"></i>
            </div>
        </a>

        <a href="{{ route('admin.print-barcodes') }}"
            class="group relative overflow-hidden bg-[#1A1A1A] p-8 rounded-3xl border border-white/5 hover:border-[#fa9a08]/50 transition-all shadow-xl">
            <div class="relative z-10">
                <i class="ri-printer-line text-4xl text-[#fa9a08] mb-4 block"></i>
                <h4 class="text-xl font-bold mb-2">Print Barcode</h4>
                <p class="text-slate-500 text-sm">Cetak semua QR Code untuk ditempel secara fisik.</p>
            </div>
            <div class="absolute -right-8 -bottom-8 text-white/5 group-hover:text-[#fa9a08]/10 transition-colors">
                <i class="ri-printer-line text-[160px]"></i>
            </div>
        </a>
    </div>

    <!-- TABLE SECTION -->
    <div class="bg-[#1A1A1A] rounded-3xl border border-white/10 overflow-hidden shadow-2xl">
        <div class="p-6 border-b border-white/5 flex justify-between items-center bg-black/20">
            <h3 class="text-lg font-bold flex items-center gap-2">
                <i class="ri-list-settings-line text-[#fa9a08]"></i>
                Status Meja Billiard
            </h3>
            <span class="px-4 py-1.5 bg-white/5 rounded-full text-xs font-medium text-slate-400">Total:
                {{ $stats['total_tables'] }} Meja</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="text-xs uppercase tracking-wider text-slate-500 bg-black/40">
                    <tr>
                        <th class="px-8 py-5">Nama Meja</th>
                        <th class="px-8 py-5">Kapasitas</th>
                        <th class="px-8 py-5 text-center">Status</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($tables as $table)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-8 py-5 font-bold text-slate-200 group-hover:text-[#fa9a08] transition-colors">
                                {{ $table->name }}
                            </td>
                            <td class="px-8 py-5 text-slate-400">
                                <span class="flex items-center gap-2">
                                    <i class="ri-group-line text-xs"></i> {{ $table->capacity }} Orang
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                    <span class="text-xs font-medium text-green-500 uppercase tracking-widest">Ready</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <a href="{{ route('table.barcode', $table->id) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-sm font-semibold hover:bg-[#fa9a08] hover:text-black hover:border-[#fa9a08] transition-all group-hover:scale-105">
                                    Lihat QR <i class="ri-arrow-right-up-line"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection