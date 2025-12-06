<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class KitchenApiController extends Controller
{
    public function index()
    {
        // show only paid/new orders atau sesuai kebutuhan
        $orders = Order::with('items.menu','table')
            ->where('payment_status','paid')
            ->whereIn('status',['new','cooking'])
            ->orderBy('created_at','desc')
            ->get();
        return response()->json($orders);
    }

    public function updateStatus(Request $r, Order $order)
    {
        $r->validate(['status' => 'required|in:cooking,done,cancel']);
        $order->status = $r->status;
        $order->save();
        return response()->json($order);
    }
}
