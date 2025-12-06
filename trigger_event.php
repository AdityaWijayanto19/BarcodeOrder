<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$order = \App\Models\Order::latest()->first();
if ($order) {
    echo "Triggering OrderPaid event for Order ID: {$order->id}\n";
    event(new \App\Events\OrderPaid($order));
    echo "Event triggered successfully!\n";
} else {
    echo "No orders found in database.\n";
}
