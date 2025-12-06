@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-gradient-to-br from-green-900 to-green-800 rounded-2xl shadow-2xl p-8 border border-green-600 text-center">
        <div class="mb-6">
            <div class="inline-block bg-green-500 rounded-full p-4 mb-4">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-green-300 mb-2">✓ Order Berhasil!</h1>
            <p class="text-green-100 text-lg">Pesanan Anda sedang diproses di dapur</p>
        </div>

        {{-- Invoice Card --}}
        <div class="bg-slate-900 rounded-xl p-6 mb-6 border border-slate-700 text-left">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-slate-400 text-sm">Invoice</p>
                    <p class="text-2xl font-mono font-bold text-amber-400">{{ $order->invoice }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-sm">Meja</p>
                    <p class="text-2xl font-bold text-amber-400">{{ $order->table->name ?? 'Meja Tidak Diketahui' }}</p>
                </div>
            </div>

            <div class="border-t border-slate-700 pt-4 mb-4">
                <p class="text-slate-400 text-sm mb-2">Detail Pesanan:</p>
                <ul class="space-y-2">
                    @foreach ($order->items as $item)
                        <li class="flex justify-between text-slate-200">
                            <span>{{ $item->menu->name }} x {{ $item->qty }}</span>
                            <span class="text-amber-400">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="border-t border-slate-700 pt-4">
                <div class="flex justify-between items-center">
                    <span class="text-slate-300 text-lg font-semibold">Total Pembayaran:</span>
                    <span class="text-3xl font-bold text-green-400">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Status Info --}}
        <div class="bg-blue-900 rounded-lg p-4 mb-6 border border-blue-600">
            <p class="text-blue-100">
                <strong>Estimasi waktu:</strong> 15-20 menit. Pesanan Anda akan dipanggil ketika sudah siap.
            </p>
        </div>

        <a href="{{ url('/') }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-black font-bold py-3 px-8 rounded-lg transition transform hover:scale-105">
            ← Kembali ke Menu
        </a>
    </div>
</div>
@endsection