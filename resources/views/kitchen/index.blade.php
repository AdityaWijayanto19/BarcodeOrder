<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAT PANDA | KITCHEN DASHBOARD</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link
    href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Courier+Prime:wght@400;700&family=Inter:wght@400;700;900&display=swap"
    rel="stylesheet">
  <style>
    .receipt-font {
      font-family: 'Courier Prime', monospace;
    }

    .logo-font {
      font-family: 'Bebas Neue', cursive;
    }

    body {
      font-family: 'Inter', sans-serif;
    }

    /* Background Kayu Dapur */
    .kitchen-bg {
      background-color: #1a1a1a;
      background-image: url("https://www.transparenttextures.com/patterns/dark-wood.png");
    }

    /* Tekstur Kertas Nota */
    .thermal-paper {
      background: #fdfcf0;
      box-shadow: 10px 10px 25px rgba(0, 0, 0, 0.7);
    }

    /* Efek Gerigi Potongan Kertas Thermal */
    .jagged-bottom {
      clip-path: polygon(0% 0%, 100% 0%, 100% 96%, 98% 100%, 96% 96%, 94% 100%, 92% 96%, 90% 100%, 88% 96%, 86% 100%, 84% 96%, 82% 100%, 80% 96%, 78% 100%, 76% 96%, 74% 100%, 72% 96%, 70% 100%, 68% 96%, 66% 100%, 64% 96%, 62% 100%, 60% 96%, 58% 100%, 56% 96%, 54% 100%, 52% 96%, 50% 100%, 48% 96%, 46% 100%, 44% 96%, 42% 100%, 40% 96%, 38% 100%, 36% 96%, 34% 100%, 32% 96%, 30% 100%, 28% 96%, 26% 100%, 24% 96%, 22% 100%, 20% 96%, 18% 100%, 16% 96%, 14% 100%, 12% 96%, 10% 100%, 8% 96%, 6% 100%, 4% 96%, 2% 100%, 0% 96%);
    }

    /* Animasi Kertas Masuk (Slam) */
    @keyframes order-slam {
      0% {
        transform: scale(2) rotate(15deg);
        opacity: 0;
      }

      100% {
        transform: scale(1) rotate(0deg);
        opacity: 1;
      }
    }

    .animate-slam {
      animation: order-slam 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
  </style>
</head>

<body class="kitchen-bg min-h-screen text-white overflow-x-hidden">

  <nav
    class="bg-zinc-900/95 backdrop-blur-md border-b-4 border-zinc-800 p-4 sticky top-0 z-50 flex justify-between items-center shadow-2xl">
    <div class="flex items-center gap-6">
      <h1 class="logo-font text-4xl tracking-widest italic text-white">CLASS<span class="text-red-600">BILLIARD</span>
      </h1>
      <button id="enableSound"
        class="bg-amber-500 hover:bg-amber-600 text-black font-black px-4 py-2 rounded text-xs transition transform hover:scale-105 active:scale-95">AKTIFKAN NOTIFIKASI</button>
    </div>

    <div class="hidden md:flex gap-6">
      <div class="text-center px-6 border-r border-zinc-700">
        <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest">Order Baru</p>
        <p id="statsNew" class="text-3xl font-black text-blue-500">0</p>
      </div>
      <div class="text-center px-6 border-r border-zinc-700">
        <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest">Memasak</p>
        <p id="statsCooking" class="text-3xl font-black text-yellow-500">0</p>
      </div>
      <div class="text-center px-6">
        <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest">Selesai</p>
        <p id="statsDone" class="text-3xl font-black text-green-500">0</p>
      </div>
    </div>

    <div class="flex items-center gap-4">
      <button class="text-zinc-500 hover:text-white transition p-2 bg-zinc-800 rounded-full">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
      </button>
      <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit"
          class="bg-red-600/20 text-red-500 border border-red-500/50 px-5 py-2 rounded-lg font-bold text-sm hover:bg-red-600 hover:text-white transition">
          Logout
        </button>
      </form>
    </div>
  </nav>

  <div class="w-full h-4 bg-zinc-800 shadow-inner border-b border-black/30 flex justify-around items-center px-10">
    <div class="w-2 h-2 bg-black rounded-full opacity-40"></div>
    <div class="w-2 h-2 bg-black rounded-full opacity-40"></div>
    <div class="w-2 h-2 bg-black rounded-full opacity-40"></div>
    <div class="w-2 h-2 bg-black rounded-full opacity-40"></div>
  </div>

  <main id="ordersList" class="p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-12 auto-rows-max">
  </main>

  <script>
    // === LOGIC BACKEND (Disesuaikan dari kodemu) ===
    let audioEnabled = false;
    let notifAudio = new Audio('/sounds/new_order.mp3');
    let lastOrderIds = new Set();
    let audioInterval = null;

    async function loadOrders() {
      try {
        const res = await fetch('/api/kitchen/orders');
        if (!res.ok) return;
        const orders = await res.json();

        const currentOrderIds = new Set(orders.map(o => o.id));
        let newOrders = [];

        orders.forEach(o => {
          if (!lastOrderIds.has(o.id)) {
            newOrders.push(o);
          }
        });

        if (newOrders.length > 0 && audioEnabled) {
          newOrders.forEach(order => showOrderAlert(order));
        }

        lastOrderIds = currentOrderIds;
        renderOrders(orders);
      } catch (e) { console.error("Fetch error:", e); }
    }

    function showOrderAlert(order) {
      stopAudio();
      playAudioRepeat();

      Swal.fire({
        title: '<span class="logo-font text-5xl text-red-600">🚨 PESANAN BARU!</span>',
        html: `
                    <div class="receipt-font text-left">
                        <div class="bg-black text-white p-4 mb-4 rounded shadow-lg">
                            <h3 class="text-xl font-bold m-0">${order.invoice}</h3>
                            <p class="text-3xl font-black m-0 mt-2 italic">MEJA: ${order.table.name}</p>
                        </div>
                        <div class="max-h-60 overflow-y-auto border-y-2 border-dashed border-zinc-300 py-4">
                            ${order.items.map(i => `
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xl font-bold">${i.menu.name}</span>
                                    <span class="text-2xl font-black">x${i.qty}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `,
        confirmButtonText: 'TERIMA & MASAK',
        confirmButtonColor: '#10b981',
        allowOutsideClick: false,
        background: '#fdfcf0',
        width: '600px'
      }).then((result) => {
        if (result.isConfirmed) {
          stopAudio();
          updateStatus(order.id, 'cooking');
        }
      });
    }

    function renderOrders(orders) {
      const container = document.getElementById('ordersList');
      container.innerHTML = '';

      // Stats update
      document.getElementById('statsNew').textContent = orders.filter(o => o.status === 'new').length;
      document.getElementById('statsCooking').textContent = orders.filter(o => o.status === 'cooking').length;
      document.getElementById('statsDone').textContent = orders.filter(o => o.status === 'done').length;

      orders.forEach(o => {
        const isNew = o.status === 'new';
        const isCooking = o.status === 'cooking';
        const isDone = o.status === 'done';

        const headerClass = isNew ? 'border-blue-500 text-blue-600' : isCooking ? 'border-yellow-600 text-yellow-600' : 'border-green-600 text-green-600';

        const card = document.createElement('div');
        card.className = `relative thermal-paper jagged-bottom p-6 pt-12 animate-slam`;

        card.innerHTML = `
                    <div class="absolute -top-10 left-1/2 -translate-x-1/2 w-14 h-16 bg-zinc-800 rounded shadow-xl flex justify-center pt-2">
                        <div class="w-10 h-1.5 bg-zinc-600 rounded"></div>
                    </div>

                    <div class="border-b-2 ${headerClass} border-dashed pb-2 mb-4">
                        <div class="flex justify-between font-bold text-[10px] text-zinc-400">
                            <span>#${o.id}</span>
                            <span>${o.status.toUpperCase()}</span>
                        </div>
                        <h2 class="text-3xl font-black text-black leading-tight uppercase italic">${o.table.name}</h2>
                    </div>

                    <div class="receipt-font text-zinc-900 mb-8 space-y-2">
                        ${o.items.map(i => `
                            <div class="flex justify-between items-start">
                                <span class="font-bold text-lg leading-tight">${i.menu.name}</span>
                                <span class="font-black text-xl ml-2">x${i.qty}</span>
                            </div>
                        `).join('')}
                    </div>

                    <div class="flex gap-2">
                        ${isNew ? `
                            <button onclick="updateStatus(${o.id}, 'cooking')" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-black font-black py-4 px-2 text-sm transition uppercase shadow-md active:translate-y-1">
                                Masak
                            </button>
                        ` : ''}
                        ${!isDone ? `
                            <button onclick="updateStatus(${o.id}, 'done')" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-black py-4 px-2 text-sm transition uppercase shadow-md active:translate-y-1">
                                Selesai
                            </button>
                        ` : `
                            <div class="w-full text-center py-3 bg-zinc-200 text-zinc-400 font-bold rounded italic">✓ DISAJIKAN</div>
                        `}
                    </div>
                `;
        container.appendChild(card);
      });
    }

    async function updateStatus(id, status) {
      await fetch('/api/kitchen/orders/' + id, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ status })
      });
      loadOrders();
    }

    // --- Audio Logic ---
    function playAudioRepeat() {
      notifAudio.currentTime = 0;
      notifAudio.play().catch(e => console.warn("Sound blocked", e));
      audioInterval = setInterval(() => {
        notifAudio.currentTime = 0;
        notifAudio.play().catch(e => console.warn(e));
      }, 6000);
    }

    function stopAudio() {
      if (audioInterval) clearInterval(audioInterval);
      notifAudio.pause();
      notifAudio.currentTime = 0;
    }

    document.getElementById('enableSound').addEventListener('click', () => {
      audioEnabled = true;
      const btn = document.getElementById('enableSound');
      btn.innerText = '🔊 NOTIFIKASI AKTIF';
      btn.classList.replace('bg-amber-500', 'bg-green-500');
      btn.classList.add('text-white');
      notifAudio.play().catch(() => { });
    });

    // --- Realtime Initializer ---
    function initRealtime() {
      if (typeof window.Echo !== 'undefined') {
        window.Echo.channel('kitchen').listen('.order.paid', () => loadOrders());
      } else { setTimeout(initRealtime, 500); }
    }

    initRealtime();
    loadOrders();
    setInterval(loadOrders, 4000); // Polling Fallback
  </script>
</body>

</html>