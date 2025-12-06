@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 text-center">
    <h2 class="text-2xl font-semibold mb-4">Checkout Pesanan</h2>

    <div class="bg-white shadow p-4 rounded">
        <p class="font-medium">Invoice: {{ $order->invoice }}</p>
        <p>Meja: {{ $order->table->name ?? '-' }}</p>
        <p>Total Pembayaran:</p>
        <h3 class="text-3xl font-bold mb-4">
            Rp {{ number_format($order->total,0,',','.') }}
        </h3>

        <button id="btnPay" 
                class="bg-green-600 text-white px-5 py-2 rounded text-lg">
            Bayar Sekarang
        </button>
    </div>
</div>
@endsection

@section('scripts')
    {{-- MIDTRANS SNAP SCRIPT --}}
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') ?? env('MIDTRANS_CLIENT_KEY') }}">
    </script>

    <script>
        document.getElementById('btnPay').addEventListener('click', function () {

            snap.pay("{{ $snapToken }}", {

                onSuccess: function(result){
                    console.log(result);
                    alert("Pembayaran berhasil!");
                    window.location.href = "/"; 
                },
                onPending: function(result){
                    console.log(result);
                    alert("Menunggu pembayaran!");
                },
                onError: function(result){
                    console.log(result);
                    alert("Pembayaran gagal!");
                },
                onClose: function(){
                    alert('Kamu menutup popup sebelum selesai pembayaran');
                }
            });

        });
    </script>
@endsection
