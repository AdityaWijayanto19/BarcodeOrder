/*
BarcodeOrder — Quick Setup & Migration Notes

This README contains a concise migration guide and copy/paste snippets
to help you reproduce the core features (ordering, broadcasts, QR codes,
SweetAlert notifications) in another Laravel project.
*/

## Project Overview
- Purpose: Simple restaurant ordering system where customers scan a table QR, create an order, and the kitchen receives real-time notifications (SweetAlert2 + looping audio) until acknowledged. Admin area to view/print table QR codes.
- Stack: Laravel 12 (PHP 8.2), MySQL, Vite + Tailwind, Pusher (or self-hosted WebSockets), database queue (or Redis), Simple QR Code package.

---

**Prerequisites**
- PHP 8.2+, Composer, Node.js + npm, MySQL (or compatible DB)
- Optional: Redis (recommended), Supervisor (or other process manager)

---

## Key Dependencies (Composer)
- `pusher/pusher-php-server` — Pusher integration
- `simplesoftwareio/simple-qrcode` — QR code generator
- (optional) `beyondcode/laravel-websockets` — self-hosted websockets alternative

Install:
```
composer require pusher/pusher-php-server simplesoftwareio/simple-qrcode
# optional: composer require beyondcode/laravel-websockets
```

## Frontend (NPM)
- `sweetalert2` — popups
- `laravel-echo` + `pusher-js` — Echo + Pusher client
- Tailwind & Vite (Laravel default setup)

Install:
```
npm install
npm install sweetalert2 laravel-echo pusher-js --save
npm run dev
```

---

## Third-Party Services / Env Variables
Add these to `.env`:

- Database
	- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

- Broadcast (Pusher)
	- `BROADCAST_DRIVER=pusher`
	- `PUSHER_APP_ID`, `PUSHER_APP_KEY`, `PUSHER_APP_SECRET`, `PUSHER_APP_CLUSTER`

- Queue
	- `QUEUE_CONNECTION=database` (or `redis`)

- Session
	- `SESSION_DRIVER=file` (or `redis`)

- Mail
	- `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`

- App URL
	- `APP_URL=http://localhost:8000`

---

## Files / Components You Should Add (copy/paste-ready)
- Models & Migrations: `Table`, `Menu`, `Order`, `OrderItem` (fields described below)
- Controllers:
	- `PublicOrderController` (order form + store + success)
	- `Api\KitchenApiController` (kitchen API endpoints)
	- `AdminController` (dashboard, barcodes, print)
- Event: `OrderPaid` (implements `ShouldBroadcastNow`)
- Views: `order/form.blade.php`, `order/success.blade.php`, `kitchen/index.blade.php`, admin views
- Routes: `routes/web.php` and `routes/api.php` snippets (see below)

### Model field summary (minimal)
- `tables`: id, name
- `menus`: id, name, price (int), available (bool), category_id (nullable)
- `orders`: id, invoice, table_id, customer_name, customer_email, customer_phone, total (int), payment_status (enum), status (enum), created_at
- `order_items`: id, order_id, menu_id, qty, price, subtotal

---

## Paste-ready snippets

### Event: `app/Events/OrderPaid.php`
```php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderPaid implements ShouldBroadcastNow
{
		use InteractsWithSockets, SerializesModels;

		public Order $order;

		public function __construct(Order $order)
		{
				$this->order = $order;
		}

		public function broadcastOn()
		{
				return new Channel('kitchen');
		}

		public function broadcastAs()
		{
				return 'order.paid';
		}
}
```

### Controller snippet: store order (minimal) — `PublicOrderController@storeOrder`
```php
public function storeOrder(Request $r)
{
		$data = $r->validate([
				'table_id' => 'required|exists:tables,id',
				'customer_email' => 'required|email',
				'items' => 'required|array'
		]);

		// calculate total & save order + items (similar to project implementation)

		$order = Order::create([
				'invoice' => 'INV-'.date('Ymd').'-'.Str::upper(Str::random(6)),
				'table_id' => $data['table_id'],
				'customer_email' => $data['customer_email'],
				'total' => $total,
				'payment_status' => 'paid',
				'status' => 'new'
		]);

		// save items...

		event(new OrderPaid($order));

		return response()->json([
				'order_id' => $order->id,
				'redirect' => route('order.success', $order->invoice)
		]);
}
```

### Frontend: meta + CSRF (put in layout `head`)
```blade
<meta name="csrf-token" content="{{ csrf_token() }}">
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

### Frontend: include SweetAlert2 (CDN or via bundle)
```html
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
```

### Echo initialisation (resources/js/bootstrap.js or equivalent)
```js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;
window.Echo = new Echo({
	broadcaster: 'pusher',
	key: import.meta.env.VITE_PUSHER_APP_KEY,
	cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
	forceTLS: true
});
```

### Route examples (`routes/web.php`)
```php
use App\Http\Controllers\PublicOrderController;

Route::get('order', [PublicOrderController::class, 'showOrderForm']);
Route::post('order', [PublicOrderController::class, 'storeOrder'])->name('order.store');
Route::get('order/success/{invoice}', [PublicOrderController::class, 'showOrderSuccess'])->name('order.success');

Route::get('kitchen', [App\Http\Controllers\KitchenController::class, 'index'])->name('kitchen.index');
```

---

## Running locally & tests
- Migrate & seed:
```
php artisan migrate
php artisan db:seed
```
- If using `database` queue:
```
php artisan queue:table
php artisan migrate
php artisan queue:work
```
- Start dev server:
```
php artisan serve
npm run dev
```
- Test broadcast: create an order via UI or via tinker/trigger script. On kitchen page you should see SweetAlert + audio.

---

## Production checklist
- Use Supervisor (Linux) or service manager to run `php artisan queue:work` persistently.
- Ensure `APP_URL` and `PUSHER` credentials match production.
- Use Redis for queue & cache in production for performance.
- Use HTTPS for websockets if self-hosted and Pusher secure connection.

---

## Optional enhancements
- Swap Pusher for `beyondcode/laravel-websockets` to avoid Pusher costs.
- Add role-based auth for admin pages.
- Add tests for order creation & event dispatch.

---

If you want, I can also:
- Add the `app/Events/OrderPaid.php` file and `PublicOrderController` implementation directly into this repo (copy/paste),
- OR generate a `README_SNIPPETS.md` with more copy/paste code blocks.

Tell me which of the two you prefer and I will proceed.
