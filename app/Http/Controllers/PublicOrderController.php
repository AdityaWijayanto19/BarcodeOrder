<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use App\Events\OrderPaid; // <-- 1. IMPORT EVENT INI

class PublicOrderController extends Controller
{
    public function showOrderForm(Request $r)
    {
        $tableId = $r->query('table');
        $table = Table::find($tableId);
        $menus = Menu::where('available', true)->get();

        return view('order.form', compact('table','menus'));
    }

    public function storeOrder(Request $r)
    {
        $data = $r->validate([
            'table_id'        => 'required|exists:tables,id',
            'customer_name'   => 'nullable|string',
            'customer_email'  => 'required|email',
            'customer_phone'  => 'nullable|string',
            'items'           => 'required|array'
        ]);

        // === HITUNG TOTAL & CEK ITEM ===
        $total = 0;
        $orderItemsData = [];
        
        foreach($data['items'] as $it) {
            $qty = intval($it['qty']);
            if ($qty > 0) {
                $menu = Menu::findOrFail($it['menu_id']);
                $subtotal = $menu->price * $qty;
                $total += $subtotal;
                
                $orderItemsData[] = [
                    'order_id' => null, // akan diisi setelah order dibuat
                    'menu_id'  => $menu->id,
                    'qty'      => $qty,
                    'price'    => $menu->price,
                    'subtotal' => $subtotal
                ];
            }
        }

        if ($total == 0 || empty($orderItemsData)) {
            return response()->json(['error' => 'Pilih minimal satu menu.'], 422);
        }

        // === SIMPAN ORDER (Langsung PAID) ===
        $order = Order::create([
            'invoice'         => 'INV-'.date('Ymd').'-'.Str::upper(Str::random(6)),
            'table_id'        => $data['table_id'],
            'customer_name'   => $data['customer_name'],
            'customer_email'  => $data['customer_email'],
            'customer_phone'  => $data['customer_phone'],
            'total'           => $total,
            'payment_status'  => 'paid', // <-- 2. DISET PAID LANGSUNG
            'status'          => 'new',
        ]);

        // === SIMPAN ITEM ===
        foreach($orderItemsData as $itemData) {
            $itemData['order_id'] = $order->id;
            OrderItem::create($itemData);
        }

        // === KIRIM NOTIFIKASI REAL-TIME KE DAPUR ===
        event(new OrderPaid($order)); // <-- 3. MEMICU EVENT DAPUR

        // Mengarahkan ke halaman sukses order (ganti dengan rute Anda)
        return response()->json([
            'order_id' => $order->id,
            'redirect' => route('order.success', $order->invoice) // <-- 4. Rute sukses
        ]);
    }

    public function showOrderSuccess($invoice)
    {
        // Contoh method yang perlu Anda buat untuk rute 'order.success'
        $order = Order::where('invoice', $invoice)->firstOrFail();
        return view('order.success', compact('order'));
    }
    
    // Method checkoutPage yang lama (dengan Midtrans) dihapus karena sudah tidak digunakan.
    // Anda dapat menggantinya dengan showOrderSuccess di atas.
}