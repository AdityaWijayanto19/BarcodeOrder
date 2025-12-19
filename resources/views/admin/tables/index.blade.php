@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold italic tracking-tight uppercase">
                    Manajemen <span class="text-[#fa9a08]">Meja</span>
                </h1>
                <p class="text-slate-500 text-sm mt-1">Kelola barcode dan akses digital untuk setiap meja.</p>
            </div>

            <a href="{{ route('tables.create') }}"
                class="flex items-center gap-2 bg-[#fa9a08] hover:bg-[#e19e2b] text-black px-6 py-3 rounded-2xl font-bold transition-all transform hover:scale-105 shadow-[0_10px_20px_rgba(250,154,8,0.2)]">
                <i class="ri-add-circle-fill text-xl"></i>
                Tambah Meja Baru
            </a>
        </div>

        <!-- Alert Success -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="p-4 bg-green-500/10 border border-green-500/50 rounded-2xl flex items-center justify-between text-green-500 animate-fade-in">
                <div class="flex items-center gap-3">
                    <i class="ri-checkbox-circle-fill text-xl"></i>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false"><i class="ri-close-line"></i></button>
            </div>
        @endif

        <!-- Tables Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tables as $table)
                <div
                    class="bg-[#1A1A1A] rounded-[32px] border border-white/5 overflow-hidden group hover:border-[#fa9a08]/40 transition-all duration-500 shadow-xl flex flex-col">

                    <!-- QR Code Preview Area -->
                    <div
                        class="aspect-square bg-white m-4 rounded-[24px] flex items-center justify-center relative overflow-hidden group-hover:bg-slate-50 transition-colors border-4 border-transparent group-hover:border-[#fa9a08]/20">

                        <!-- Gambar Barcode dengan Fallback Check -->
                        @if($table->qrcode && file_exists(public_path($table->qrcode)))
                            <img src="{{ asset($table->qrcode) }}" alt="QR {{ $table->name }}"
                                class="w-48 h-48 relative z-10 p-2 object-contain">
                        @else
                            <!-- Jika File Tidak Ditemukan, Generate Langsung (Inline) sebagai Cadangan -->
                            <div class="p-4 bg-white rounded-lg">
                                {!! QrCode::size(180)->generate(url('/order/' . $table->slug)) !!}
                            </div>
                        @endif

                        <!-- Quick Hover Actions -->
                        <div
                            class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center gap-3 z-20 backdrop-blur-sm">
                            <!-- LIHAT DETAIL POSTER (REVISI) -->
                            <a href="{{ route('table.barcode', $table->id) }}"
                                class="p-3 bg-[#fa9a08] text-black rounded-2xl hover:scale-110 transition-transform shadow-xl flex flex-col items-center gap-1 w-20">
                                <i class="ri-printer-line text-xl"></i>
                                <span class="text-[9px] font-bold uppercase tracking-tighter">Poster</span>
                            </a>

                            <!-- UNDUH FILE -->
                            <a href="{{ asset($table->qrcode) }}" download="QR_{{ $table->slug }}.svg"
                                class="p-3 bg-white text-black rounded-2xl hover:scale-110 transition-transform shadow-xl flex flex-col items-center gap-1 w-20">
                                <i class="ri-download-2-line text-xl"></i>
                                <span class="text-[9px] font-bold uppercase tracking-tighter">Unduh</span>
                            </a>

                            <!-- BUKA LINK ORDER -->
                            <a href="{{ url('/order/' . $table->slug) }}" target="_blank"
                                class="p-3 bg-blue-500 text-white rounded-2xl hover:scale-110 transition-transform shadow-xl flex flex-col items-center gap-1 w-20">
                                <i class="ri-external-link-line text-xl"></i>
                                <span class="text-[9px] font-bold uppercase tracking-tighter">Cek Link</span>
                            </a>
                        </div>
                    </div>

                    <!-- Table Info -->
                    <div class="p-6 pt-2 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3
                                    class="text-xl font-bold text-white group-hover:text-[#fa9a08] transition-colors leading-none mb-2">
                                    {{ $table->name }}
                                </h3>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-[10px] bg-black text-slate-500 px-2 py-1 rounded border border-white/5 font-mono">
                                        /{{ $table->slug }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end">
                                <span
                                    class="flex items-center gap-1 text-green-500 text-[10px] font-bold uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    Active
                                </span>
                                <span class="text-slate-600 text-[10px] mt-1 font-medium">
                                    {{ $table->capacity ?? '0' }} Seats
                                </span>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="mt-auto flex gap-2 pt-4 border-t border-white/5">
                            <!-- KE HALAMAN DETAIL BARCODE -->
                            <a href="{{ route('table.barcode', $table->id) }}"
                                class="flex-1 flex items-center justify-center gap-2 py-3 bg-white/5 hover:bg-[#fa9a08] hover:text-black rounded-xl text-xs font-bold transition-all border border-white/10 group/btn">
                                <i class="ri-fullscreen-line text-sm"></i> Detail QR
                            </a>

                            <!-- HAPUS -->
                            <form action="#" method="POST" class="shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus meja ini?')"
                                    class="w-12 flex items-center justify-center py-3 bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition-all border border-red-500/20">
                                    <i class="ri-delete-bin-line text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div
                    class="col-span-full py-20 flex flex-col items-center justify-center bg-[#1A1A1A] rounded-[40px] border border-dashed border-white/10 text-center">
                    <i class="ri-qr-code-line text-6xl text-slate-700 mb-4"></i>
                    <h3 class="text-xl font-bold text-white">Belum Ada Meja</h3>
                    <p class="text-slate-500 text-sm mt-1">Generate barcode meja pertama Anda sekarang.</p>
                    <a href="{{ route('tables.create') }}"
                        class="mt-6 bg-[#fa9a08] text-black px-8 py-3 rounded-xl font-bold hover:scale-105 transition-transform">Tambah
                        Meja</a>
                </div>
            @endforelse
        </div>
    </div>
@endsection