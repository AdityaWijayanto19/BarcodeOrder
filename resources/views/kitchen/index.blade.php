@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto">
  {{-- Header --}}
  <div class="mb-8 flex justify-between items-center">
    <div>
      <h1 class="text-4xl font-bold text-amber-400 mb-2">👨‍🍳 Kitchen Dashboard</h1>
      <p class="text-slate-400">Monitor order dan kelola status masakan</p>
    </div>
    <button id="enableSound" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-black font-bold px-6 py-3 rounded-lg transition transform hover:scale-105">
      🔊 Aktifkan Notifikasi
    </button>
  </div>

  {{-- Stats --}}
  <div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-slate-800 rounded-lg p-4 border border-slate-700">
      <div class="text-slate-400 text-sm">Order Baru</div>
      <div id="statsNew" class="text-3xl font-bold text-blue-400">0</div>
    </div>
    <div class="bg-slate-800 rounded-lg p-4 border border-slate-700">
      <div class="text-slate-400 text-sm">Sedang Memasak</div>
      <div id="statsCooking" class="text-3xl font-bold text-yellow-400">0</div>
    </div>
    <div class="bg-slate-800 rounded-lg p-4 border border-slate-700">
      <div class="text-slate-400 text-sm">Siap Disajikan</div>
      <div id="statsDone" class="text-3xl font-bold text-green-400">0</div>
    </div>
  </div>

  {{-- Orders List --}}
  <div id="ordersList" class="space-y-4"></div>
</div>


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  let audioEnabled = false;
  let notifAudio = new Audio('/sounds/new_order.mp3');
  let lastOrderIds = new Set();
  let audioInterval = null;

  async function loadOrders() {
    const res = await fetch('/api/kitchen/orders');
    if (!res.ok) {
      console.error("Gagal memuat orders:", res.status);
      return;
    }
    const orders = await res.json();
    
    // Cek apakah ada order baru yang belum di-track
    const currentOrderIds = new Set(orders.map(o => o.id));
    let newOrders = [];
    
    orders.forEach(o => {
      if (!lastOrderIds.has(o.id)) {
        newOrders.push(o);
        console.log('🆕 Order baru terdeteksi:', o.id);
      }
    });
    
    // Jika ada order baru, tampilkan alert untuk setiap order
    if (newOrders.length > 0 && audioEnabled) {
      newOrders.forEach(order => {
        showOrderAlert(order);
      });
    }
    
    // Update lastOrderIds
    lastOrderIds = currentOrderIds;
    renderOrders(orders);
  }

  function showOrderAlert(order) {
    // Hentikan suara sebelumnya jika ada
    stopAudio();
    
    // Mulai suara berulang
    playAudioRepeat();
    
    // Format items
    const itemsHtml = order.items.map(i => `
      <div style="text-align: left; margin: 8px 0; border-bottom: 1px solid #eee; padding-bottom: 8px;">
        <strong>${i.menu.name}</strong> x${i.qty}
        <br>
        <span style="color: #666; font-size: 12px;">Rp ${(i.price * i.qty).toLocaleString('id-ID')}</span>
      </div>
    `).join('');

    Swal.fire({
      title: '🎉 ORDER BARU MASUK!',
      html: `
        <div style="text-align: left;">
          <div style="background: #f0f9ff; padding: 12px; border-radius: 8px; margin-bottom: 12px;">
            <h3 style="margin: 0; color: #0066cc;">${order.invoice}</h3>
            <p style="margin: 4px 0; color: #666;">Meja: <strong>${order.table.name}</strong></p>
          </div>
          <h4 style="margin-top: 12px; margin-bottom: 8px; text-align: center;">Menu:</h4>
          <div style="max-height: 200px; overflow-y: auto;">
            ${itemsHtml}
          </div>
          <div style="margin-top: 12px; background: #fffbeb; padding: 12px; border-radius: 8px; border: 2px solid #fbbf24;">
            <strong style="font-size: 18px; color: #d97706;">Total: Rp ${order.total.toLocaleString('id-ID')}</strong>
          </div>
        </div>
      `,
      icon: 'success',
      confirmButtonText: 'OK - Terima Order',
      confirmButtonColor: '#10b981',
      allowOutsideClick: false,
      allowEscapeKey: false,
      width: '500px',
      showClass: {
        popup: 'animate__animated animate__slideInDown animate__faster'
      },
      hideClass: {
        popup: 'animate__animated animate__slideOutUp animate__faster'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        // Hentikan suara saat user klik OK
        stopAudio();
        console.log('✓ Order ' + order.invoice + ' dikonfirmasi');
      }
    });
  }

  function playAudioRepeat() {
    notifAudio.currentTime = 0;
    notifAudio.play().catch(error => console.warn("Sound playback blocked:", error));
    
    // Set interval untuk repeat suara setiap kali audio selesai
    audioInterval = setInterval(() => {
      notifAudio.currentTime = 0;
      notifAudio.play().catch(error => console.warn("Sound playback blocked:", error));
    }, 6000);
  }

  function stopAudio() {
    if (audioInterval) {
      clearInterval(audioInterval);
      audioInterval = null;
    }
    notifAudio.pause();
    notifAudio.currentTime = 0;
  }

  // ... (renderOrders function) ...
  function renderOrders(orders) {
    const container = document.getElementById('ordersList');
    container.innerHTML = '';
    
    // Update stats
    const newCount = orders.filter(o => o.status === 'new').length;
    const cookingCount = orders.filter(o => o.status === 'cooking').length;
    const doneCount = orders.filter(o => o.status === 'done').length;
    
    document.getElementById('statsNew').textContent = newCount;
    document.getElementById('statsCooking').textContent = cookingCount;
    document.getElementById('statsDone').textContent = doneCount;
    
    // Group orders by status
    const ordersByStatus = {
      new: orders.filter(o => o.status === 'new'),
      cooking: orders.filter(o => o.status === 'cooking'),
      done: orders.filter(o => o.status === 'done')
    };

    // Render orders
    const statusConfig = {
      new: { label: 'Pesanan Baru', color: 'blue', icon: '📩', textColor: 'text-blue-400' },
      cooking: { label: 'Sedang Memasak', color: 'yellow', icon: '👨‍🍳', textColor: 'text-yellow-400' },
      done: { label: 'Siap Disajikan', icon: '✓', textColor: 'text-green-400' }
    };

    Object.keys(ordersByStatus).forEach(status => {
      if (ordersByStatus[status].length > 0) {
        const section = document.createElement('div');
        section.className = 'mb-8';
        section.innerHTML = `<h3 class="text-xl font-bold ${statusConfig[status].textColor} mb-4">${statusConfig[status].icon} ${statusConfig[status].label}</h3>`;
        
        const orderGrid = document.createElement('div');
        orderGrid.className = 'grid grid-cols-1 lg:grid-cols-2 gap-4';
        
        ordersByStatus[status].forEach(o => {
          const orderCard = document.createElement('div');
          const statusColor = status === 'new' ? 'border-blue-500' : status === 'cooking' ? 'border-yellow-500' : 'border-green-500';
          const statusBg = status === 'new' ? 'bg-blue-900' : status === 'cooking' ? 'bg-yellow-900' : 'bg-green-900';
          
          const itemsHtml = o.items.map(i => `
            <div class="flex justify-between text-sm mb-2">
              <span>${i.menu.name} <span class="text-slate-400">x${i.qty}</span></span>
              <span class="font-semibold">Rp ${(i.price * i.qty).toLocaleString('id-ID')}</span>
            </div>
          `).join('');
          
          orderCard.className = `bg-slate-800 border-2 ${statusColor} rounded-lg p-5 hover:shadow-lg transition`;
          orderCard.innerHTML = `
            <div class="flex justify-between items-start mb-3">
              <div>
                <h4 class="text-lg font-bold text-amber-400">${o.invoice}</h4>
                <p class="text-slate-400 text-sm">${o.table.name}</p>
              </div>
              <span class="${statusBg} px-3 py-1 rounded text-sm font-semibold">${status.toUpperCase()}</span>
            </div>
            
            <div class="bg-slate-900 rounded p-3 mb-4 text-sm">
              ${itemsHtml}
              <div class="border-t border-slate-700 pt-2 mt-2">
                <div class="flex justify-between font-bold">
                  <span>Total:</span>
                  <span class="text-amber-400">Rp ${o.total.toLocaleString('id-ID')}</span>
                </div>
              </div>
            </div>
            
            <div class="flex gap-2">
              ${status === 'new' ? `
                <button class="btn-status flex-1 bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-3 rounded transition" data-id="${o.id}" data-status="cooking">
                  👨‍🍳 Mulai Memasak
                </button>
              ` : ''}
              <button class="btn-status flex-1 ${status === 'done' ? 'bg-slate-600 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600'} text-white font-bold py-2 px-3 rounded transition" data-id="${o.id}" data-status="done" ${status === 'done' ? 'disabled' : ''}>
                ${status === 'done' ? '✓ Selesai' : '✓ Selesai'}
              </button>
            </div>
          `;
          
          orderGrid.appendChild(orderCard);
        });
        
        section.appendChild(orderGrid);
        container.appendChild(section);
      }
    });

    document.querySelectorAll('.btn-status').forEach(b => {
      b.addEventListener('click', async (e) => {
        const id = e.target.dataset.id;
        const status = e.target.dataset.status;
        
        await fetch('/api/kitchen/orders/' + id, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ status })
        });
        
        loadOrders();
      });
    });
  }


  document.getElementById('enableSound').addEventListener('click', () => {
    audioEnabled = true;
    const btn = document.getElementById('enableSound');
    btn.innerText = '🔊 Notifikasi Suara & Alert: ON';
    btn.classList.remove('bg-gray-200');
    btn.classList.add('bg-green-500', 'text-white');
    
    // Test suara dan alert
    notifAudio.currentTime = 0;
    notifAudio.play().catch(() => {/*silent*/ });
    
    Swal.fire({
      title: '✓ Notifikasi Aktif!',
      text: 'Alert dan suara akan muncul saat ada order baru',
      icon: 'success',
      confirmButtonColor: '#10b981',
      timer: 2000
    });
    
    console.log('✓ Notifikasi Suara & Alert DIAKTIFKAN');
  });

  // === ECHO LISTENER ===
  function initializeEchoListener() {
    if (typeof window.Echo !== 'undefined') {
      window.Echo.channel('kitchen')
        .listen('.order.paid', (e) => {
          console.log('OrderPaid Event Received', e);
          if (audioEnabled) {
            loadOrders();
          }
        });
      console.log("Echo Listener berhasil dipasang.");
    } else {
      console.warn("window.Echo belum terdefinisi. Mencoba lagi...");
      setTimeout(initializeEchoListener, 200);
    }
  }

  // Initialize Echo listener dan load orders pertama kali
  initializeEchoListener();
  loadOrders();

  // Polling fallback setiap 3 detik
  setInterval(loadOrders, 3000);

</script>
@endsection