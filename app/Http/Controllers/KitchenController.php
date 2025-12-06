<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        // Simple page for kitchen. Add auth or simple middleware if needed.
        return view('kitchen.index');
    }
}
