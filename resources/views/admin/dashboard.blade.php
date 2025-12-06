@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-amber-400 mb-2">⚙️ Admin Dashboard</h1>
        <p class="text-slate-400">Kelola meja, lihat statistik, dan kelola barcode</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-slate-800 rounded-lg p-4 border border-slate-700">
            <div class="text-slate-400 text-sm">Total Meja</div>
            <div class="text-3xl font-bold text-amber-400">{{ $stats['total_tables'] }}</div>
        </div>
        <div class="bg-slate-800 rounded-lg p-4 border border-slate-700">
            <div class="text-slate-400 text-sm">Order Baru</div>
            <div class="text-3xl font-bold text-blue-400">{{ $stats['new_orders'] }}</div>
        </div>
        <div class="bg-slate-800 rounded-lg p-4 border border-slate-700">
            <div class="text-slate-400 text-sm">Sedang Memasak</div>
            <div class="text-3xl font-bold text-yellow-400">{{ $stats['cooking_orders'] }}</div>
        </div>
        <div class="bg-slate-800 rounded-lg p-4 border border-slate-700">
            <div class="text-slate-400 text-sm">Selesai</div>
            <div class="text-3xl font-bold text-green-400">{{ $stats['done_orders'] }}</div>
        </div>
        <div class="bg-slate-800 rounded-lg p-4 border border-slate-700">
            <div class="text-slate-400 text-sm">Total Pendapatan</div>
            <div class="text-2xl font-bold text-emerald-400">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('admin.barcodes') }}" class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 rounded-lg p-6 text-center transition transform hover:scale-105 border border-purple-500">
            <div class="text-3xl mb-2">🔲 QR Code Barcodes</div>
            <p class="text-slate-200">Lihat & kelola barcode QR untuk setiap meja</p>
        </a>
        
        <a href="{{ route('admin.print-barcodes') }}" class="bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 rounded-lg p-6 text-center transition transform hover:scale-105 border border-indigo-500">
            <div class="text-3xl mb-2">🖨️ Print Barcode</div>
            <p class="text-slate-200">Print barcode untuk ditempel di setiap meja</p>
        </a>
    </div>

    {{-- Tabel Daftar Meja --}}
    <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
        <div class="bg-slate-900 px-6 py-4 border-b border-slate-700">
            <h3 class="text-xl font-bold text-amber-400">📋 Daftar Meja</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-slate-200">Meja</th>
                        <th class="px-6 py-3 text-left text-slate-200">Kapasitas</th>
                        <th class="px-6 py-3 text-left text-slate-200">Status</th>
                        <th class="px-6 py-3 text-left text-slate-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($tables as $table)
                        <tr class="hover:bg-slate-750 transition">
                            <td class="px-6 py-3 font-semibold text-amber-400">{{ $table->name }}</td>
                            <td class="px-6 py-3 text-slate-300">{{ $table->capacity }} orang</td>
                            <td class="px-6 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-900 text-green-300">Aktif</span>
                            </td>
                            <td class="px-6 py-3">
                                <a href="{{ route('table.barcode', $table->id) }}" class="text-blue-400 hover:text-blue-300 text-sm font-semibold">
                                    Lihat Barcode →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
