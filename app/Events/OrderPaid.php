<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderPaid implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
        // Muat relasi yang dibutuhkan di frontend dapur
        $this->order->load('items.menu', 'table'); 
    }

    public function broadcastOn(): Channel
    {
        return new Channel('kitchen');
    }
    
    public function broadcastAs(): string
    {
        return 'order.paid'; // Pastikan nama event ini sama dengan yang didengarkan di JS
    }
}