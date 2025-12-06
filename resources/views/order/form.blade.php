@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <h1 class="text-4xl font-bold text-amber-400">📦 Pesan Sekarang</h1>
        <p class="text-slate-400 mt-2">Meja: <span class="text-xl font-semibold text-amber-300">{{ $table->name ?? 'Meja tidak dikenal' }}</span></p>
    </div>

    <form id="orderForm" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        <input type="hidden" name="table_id" value="{{ $table->id ?? '' }}">
        
        {{-- Kolom Kiri: Customer Info --}}
        <div class="lg:col-span-1 bg-slate-800 rounded-lg p-6 shadow-lg border border-slate-700 h-fit">
            <h3 class="text-lg font-bold text-amber-400 mb-4">👤 Data Pemesan</h3>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-300 mb-2">Nama (opsional)</label>
                <input name="customer_name" class="w-full bg-slate-700 border border-slate-600 text-white p-3 rounded focus:outline-none focus:border-amber-400" placeholder="Masukkan nama Anda">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-300 mb-2">Email (untuk invoice)</label>
                <input name="customer_email" type="email" class="w-full bg-slate-700 border border-slate-600 text-white p-3 rounded focus:outline-none focus:border-amber-400" placeholder="email@contoh.com" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-300 mb-2">No. Telepon</label>
                <input name="customer_phone" class="w-full bg-slate-700 border border-slate-600 text-white p-3 rounded focus:outline-none focus:border-amber-400" placeholder="08213456789" required>
            </div>

            {{-- Ringkasan Pesanan --}}
            <div class="bg-slate-900 rounded p-4 border border-slate-600">
                <h4 class="font-bold text-amber-400 mb-3">💰 Ringkasan Pesanan</h4>
                <div id="cartSummary" class="space-y-2 text-sm mb-4">
                    <p class="text-slate-400">Belum ada menu</p>
                </div>
                <div class="border-t border-slate-600 pt-3">
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total:</span>
                        <span class="text-amber-400" id="totalPrice">Rp 0</span>
                    </div>
                </div>
            </div>

            <button id="btnCheckout" type="button" class="w-full mt-6 bg-amber-500 hover:bg-amber-600 text-black font-bold py-3 rounded-lg transition transform hover:scale-105">
                ✓ Checkout
            </button>
        </div>

        {{-- Kolom Kanan: Menu Selection --}}
        <div class="lg:col-span-2">
            <h3 class="text-lg font-bold text-amber-400 mb-4">🍽️ Pilih Menu</h3>
            <div id="menuList" class="grid grid-cols-2 gap-4">
                @foreach($menus as $menu)
                    <div class="bg-slate-800 border border-slate-700 rounded-lg p-4 hover:border-amber-400 hover:shadow-lg transition cursor-pointer group">
                        <div class="font-bold text-lg text-slate-100 group-hover:text-amber-400 transition">{{ $menu->name }}</div>
                        <div class="text-amber-400 font-semibold text-lg my-2">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                        <div class="flex items-center gap-2">
                            <button type="button" class="qty-btn-minus bg-slate-700 px-2 py-1 rounded text-sm hover:bg-amber-500" data-menu-id="{{ $menu->id }}">−</button>
                            <input type="number" min="0" value="0" data-menu-id="{{ $menu->id }}" class="qty-input w-16 text-center bg-slate-700 border border-slate-600 text-white p-2 rounded text-sm" readonly>
                            <button type="button" class="qty-btn-plus bg-slate-700 px-2 py-1 rounded text-sm hover:bg-amber-500" data-menu-id="{{ $menu->id }}">+</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const qtyInputs = {};

    // Initialize qty inputs
    document.querySelectorAll('.qty-input').forEach(input => {
        qtyInputs[input.dataset.menuId] = input;
    });

    // Plus button
    document.querySelectorAll('.qty-btn-plus').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const menuId = btn.dataset.menuId;
            const input = qtyInputs[menuId];
            input.value = parseInt(input.value || 0) + 1;
            updateCart();
        });
    });

    // Minus button
    document.querySelectorAll('.qty-btn-minus').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const menuId = btn.dataset.menuId;
            const input = qtyInputs[menuId];
            input.value = Math.max(0, parseInt(input.value || 0) - 1);
            updateCart();
        });
    });

    function updateCart() {
        let total = 0;
        let items = [];
        let summary = '';

        @php
            $menuPrices = $menus->keyBy('id')->map->price->toArray();
        @endphp
        const menuPrices = {!! json_encode($menuPrices) !!};
        const menuNames = {!! json_encode($menus->keyBy('id')->map->name->toArray()) !!};

        document.querySelectorAll('.qty-input').forEach(input => {
            const qty = parseInt(input.value) || 0;
            if (qty > 0) {
                const menuId = input.dataset.menuId;
                const price = menuPrices[menuId];
                const subtotal = price * qty;
                total += subtotal;
                summary += `<div class="flex justify-between"><span>${menuNames[menuId]} x${qty}</span><span class="text-amber-400">Rp ${subtotal.toLocaleString('id-ID')}</span></div>`;
                items.push({ menu_id: menuId, qty });
            }
        });

        document.getElementById('cartSummary').innerHTML = summary || '<p class="text-slate-400">Belum ada menu</p>';
        document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.getElementById('btnCheckout').addEventListener('click', async function () {
        const form = document.getElementById('orderForm');
        const tableId = form.querySelector('input[name="table_id"]').value;
        const name = form.customer_name.value;
        const email = form.customer_email.value;
        const phone = form.customer_phone.value;

        const items = [];
        document.querySelectorAll('.qty-input').forEach(input => {
            const qty = parseInt(input.value) || 0;
            if (qty > 0) {
                items.push({
                    menu_id: input.dataset.menuId,
                    qty: qty
                });
            }
        });

        if (items.length === 0) {
            Swal.fire({
                title: 'Pilih Menu Dulu!',
                text: 'Silakan pilih minimal satu menu sebelum checkout',
                icon: 'warning',
                confirmButtonColor: '#f59e0b'
            });
            return;
        }

        if (!email) {
            Swal.fire({
                title: 'Email Wajib!',
                text: 'Silakan isi email agar invoice dapat dikirim',
                icon: 'warning',
                confirmButtonColor: '#f59e0b'
            });
            return;
        }

        try {
            const response = await fetch('/order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    table_id: tableId,
                    customer_name: name,
                    customer_email: email,
                    customer_phone: phone,
                    items: items
                })
            });

            const text = await response.text();
            const data = JSON.parse(text);
            
            if (response.ok && data.order_id) {
                window.location.href = data.redirect;
            } else {
                Swal.fire('Error', data.error || 'Gagal membuat order', 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'Gagal membuat order: ' + error.message, 'error');
            console.error('Order error:', error);
        }
    });
</script>
@endsection