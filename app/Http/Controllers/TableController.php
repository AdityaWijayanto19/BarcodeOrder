<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::all();
        return view('admin.tables.index', compact('tables'));
    }

    public function create()
    {
        return view('admin.tables.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required'
        ]);

        // Buat slug otomatis
        $slug = Str::slug($request->name);

        // URL tujuan saat scan QR
        $url = url('/order/' . $slug);

        // Generate QR file
        $filename = 'qr_' . time() . '.svg';
        $path = public_path('qrcodes/' . $filename);

        QrCode::format('svg')->size(200)->generate($url, $path);

        Table::create([
            'name'  => $request->name,
            'slug'  => $slug,
            'qrcode'=> 'qrcodes/' . $filename,
        ]);

        return redirect()->route('tables.index')->with('success','Berhasil membuat QR kode meja');
    }
}
