<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>Invoice {{ $order->invoice }}</title>
</head>
<body>
  <h2>Invoice: {{ $order->invoice }}</h2>
  <p>Meja: {{ $order->table->name }}</p>
  <p>Nama: {{ $order->customer_name ?? '-' }}</p>
  <p>Email: {{ $order->customer_email ?? '-' }}</p>
  <hr>
  <h3>Pesanan</h3>
  <ul>
  @foreach($order->items as $item)
    <li>{{ $item->menu->name }} x{{ $item->qty }} — Rp {{ number_format($item->subtotal,0,',','.') }}</li>
  @endforeach
  </ul>
  <p><strong>Total: Rp {{ number_format($order->total,0,',','.') }}</strong></p>
  <p>Status pembayaran: {{ strtoupper($order->payment_status) }}</p>
  <p>Terima kasih sudah memesan.</p>
</body>
</html>
