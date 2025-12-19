<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Billiard Class')</title>

    {{-- Google Font Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Remix Icon --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-['Poppins'] bg-black text-white antialiased overflow-x-hidden">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 w-full z-50 py-6 bg-[#1A1A1A] border-b border-white/5">
        <div class="max-w-[1024px] mx-auto px-4">
            <div class="flex items-center justify-between">
                <div class="logo">
                    <h1 class="text-[1.75rem] font-bold text-[#fa9a08]">Class Billiard</h1>
                </div>

                <ul class="hidden md:flex list-none gap-12 items-center" id="navbar-menu">
                    <li><a href="/" class="text-[0.95rem] font-medium transition-colors hover:text-[#fa9a08]">Beranda</a></li>
                    <li><a href="{{ route('menu') }}" class="text-[0.95rem] font-medium text-[#fa9a08]">Orders</a></li>
                </ul>

                <div class="md:hidden cursor-pointer" id="navbar-toggle">
                    <i class="ri-menu-3-line text-2xl"></i>
                </div>
            </div>
        </div>
    </header>

    <main class="pt-24 pb-32 bg-black min-h-screen">
        <section class="max-w-[1024px] mx-auto px-4">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold">Menu Kami</h2>
                <button class="bg-[#fa9a08] hover:bg-[#e19e2b] px-6 py-2 rounded-xl font-semibold text-sm transition-all" id="viewOrderBtn">
                    Lihat Pesanan
                </button>
            </div>

            <!-- Categories Filter -->
            <div class="flex gap-3 mb-8 overflow-x-auto pb-2 scrollbar-hide">
                <button class="category-btn whitespace-nowrap px-6 py-2.5 rounded-xl text-sm font-medium transition-all bg-[#fa9a08] text-white" data-category="makanan">Makanan</button>
                <button class="category-btn whitespace-nowrap px-6 py-2.5 rounded-xl text-sm font-medium transition-all bg-white/10 text-white hover:bg-white/20" data-category="minuman">Minuman</button>
                <button class="category-btn whitespace-nowrap px-6 py-2.5 rounded-xl text-sm font-medium transition-all bg-white/10 text-white hover:bg-white/20" data-category="cemilan">Cemilan</button>
                <button class="category-btn whitespace-nowrap px-6 py-2.5 rounded-xl text-sm font-medium transition-all bg-white/10 text-white hover:bg-white/20" data-category="all">Semua</button>
            </div>

            <!-- Menu Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6" id="menuItemsGrid">
                {{-- Item Card --}}
                <div class="menu-item-card bg-[#1A1A1A] rounded-2xl overflow-hidden border border-white/5 hover:border-[#fa9a08]/50 transition-all group" data-category="makanan">
                    <div class="aspect-square overflow-hidden p-3">
                        <img src="{{ asset('assets/img/MIE GORENG CLASS - 25K 2.png') }}" alt="Mie Goreng" class="w-full h-full object-cover rounded-xl group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-4 pt-0">
                        <h3 class="font-semibold text-sm md:text-base mb-2 line-clamp-1">Mie Goreng Class</h3>
                        <div class="flex justify-between items-center gap-2">
                            <p class="text-[#fa9a08] font-bold text-sm">Rp25.000</p>
                            <button class="add-to-cart-btn bg-[#fa9a08] p-2 rounded-lg hover:scale-110 transition-transform" data-name="Mie Goreng Class" data-price="25000">
                                <i class="ri-add-line font-bold"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Tambahkan item lainnya di sini dengan struktur yang sama -->
            </div>
        </section>
    </main>

    <!-- Sidebar Order Panel -->
    <div class="fixed top-0 -right-full md:-right-[400px] w-full md:w-[400px] h-screen bg-[#141414] z-[1001] flex flex-col transition-all duration-300 shadow-2xl border-l border-white/10" id="orderPanel">
        <div class="p-6 bg-[#fa9a08] flex justify-between items-center">
            <h3 class="text-xl font-bold">Keranjang Saya</h3>
            <button class="w-10 h-10 flex items-center justify-center rounded-full bg-black/20 hover:bg-black/40 transition-all" id="closeOrderBtn">
                <i class="ri-close-line text-2xl text-white"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-4 scrollbar-hide" id="orderItemsList">
            <!-- Items injected by JS -->
        </div>

        <div class="p-6 bg-[#1A1A1A] border-t border-white/10 space-y-4">
            <div id="orderSummaryItems" class="space-y-2 max-h-32 overflow-y-auto text-sm opacity-70"></div>
            <div class="flex justify-between items-center pt-4 border-t border-white/10">
                <span class="font-medium text-lg">Total</span>
                <span class="text-xl font-bold text-[#10b981]" id="orderTotalPrice">Rp0</span>
            </div>
            
            <div class="flex gap-2 py-2">
                <button class="payment-btn flex-1 py-2 rounded-lg border border-white/10 text-xs font-medium [&.active]:bg-[#fa9a08] [&.active]:border-[#fa9a08]" data-payment="cash">CASH</button>
                <button class="payment-btn flex-1 py-2 rounded-lg border border-white/10 text-xs font-medium [&.active]:bg-[#fa9a08] [&.active]:border-[#fa9a08]" data-payment="qris">QRIS</button>
            </div>

            <button class="w-full py-4 bg-[#fa9a08] hover:bg-[#e19e2b] disabled:bg-gray-700 rounded-xl font-bold transition-all" id="checkoutBtn">
                Checkout Pesanan
            </button>
        </div>
    </div>

    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/80 z-[1000] hidden opacity-0 transition-all duration-300" id="orderPanelOverlay"></div>

    <!-- Bottom Bar Notification -->
    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[90%] max-w-[500px] bg-[#fa9a08] p-4 rounded-2xl flex justify-between items-center shadow-2xl z-[900] cursor-pointer transition-all hover:scale-[1.02] hidden" id="bottomOrderBar">
        <div class="flex items-center gap-4">
            <div class="bg-black/20 w-12 h-12 rounded-xl flex items-center justify-center relative">
                <i class="ri-shopping-basket-2-line text-2xl"></i>
                <span class="absolute -top-2 -right-2 bg-white text-[#fa9a08] text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center" id="orderCount">0</span>
            </div>
            <div>
                <p class="font-bold text-sm">Lihat Pesanan</p>
                <p class="text-xs opacity-90" id="orderTotal">Rp0</p>
            </div>
        </div>
        <button id="closeBottomBarBtn" class="p-2 hover:bg-black/10 rounded-lg"><i class="ri-delete-bin-line"></i></button>
    </div>

    <!-- Checkout Modal -->
    <div class="fixed inset-0 bg-black/90 z-[2000] hidden items-center justify-center p-4 opacity-0 transition-all duration-300" id="checkoutModal">
        <div class="bg-[#1A1A1A] w-full max-w-md rounded-3xl overflow-hidden shadow-2xl border border-white/10 transform translate-y-10 transition-all duration-300" id="checkoutModalContent">
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-[#fa9a08]">
                <h3 class="font-bold text-lg text-black text-white">Detail Pengiriman</h3>
                <button id="closeModalBtn" class="text-white text-2xl">&times;</button>
            </div>
            <form id="checkoutForm" class="p-6 space-y-4">
                <div class="space-y-1">
                    <label class="text-xs font-medium opacity-60">Nama Pemesan</label>
                    <input type="text" id="customerName" required class="w-full bg-white/5 border border-white/10 p-3 rounded-xl focus:outline-none focus:border-[#fa9a08] transition-all" placeholder="John Doe">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-medium opacity-60">No. Meja</label>
                        <input type="text" id="tableNumber" required class="w-full bg-white/5 border border-white/10 p-3 rounded-xl focus:outline-none focus:border-[#fa9a08]" placeholder="08">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium opacity-60">Ruangan</label>
                        <input type="text" id="room" required class="w-full bg-white/5 border border-white/10 p-3 rounded-xl focus:outline-none focus:border-[#fa9a08]" placeholder="VIP / Reg">
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" id="cancelCheckoutBtn" class="flex-1 py-3 rounded-xl bg-white/5 font-medium">Batal</button>
                    <button type="submit" class="flex-1 py-3 rounded-xl bg-[#fa9a08] font-bold text-white">Kirim Pesanan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="fixed top-10 left-1/2 -translate-x-1/2 -translate-y-20 bg-green-500 text-white px-6 py-3 rounded-full shadow-2xl z-[3000] transition-all duration-500 opacity-0 flex items-center gap-2">
        <i class="ri-checkbox-circle-fill text-xl"></i>
        <span id="toastMessage">Pesanan Berhasil!</span>
    </div>

    <script src="{{ asset('js/menu.js') }}"></script>
</body>
</html>