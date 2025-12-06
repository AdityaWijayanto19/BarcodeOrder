<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $tables = Table::all();
        $stats = [
            'total_tables' => Table::count(),
            'new_orders' => Order::where('status', 'new')->count(),
            'cooking_orders' => Order::where('status', 'cooking')->count(),
            'done_orders' => Order::where('status', 'done')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
        ];
        
        return view('admin.dashboard', compact('tables', 'stats'));
    }

    public function barcodes()
    {
        $tables = Table::all();
        return view('admin.barcodes', compact('tables'));
    }

    public function printBarcodes()
    {
        $tables = Table::all();
        return view('admin.print-barcodes', compact('tables'));
    }
}
