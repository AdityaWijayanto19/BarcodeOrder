@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Menu</h1>

    @if($menus->count() == 0)
        <p>Tidak ada data menu.</p>
    @else
        <table class="table-auto w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">Harga</th>
                    <th class="border px-4 py-2">Kategori</th>
                </tr>
            </thead>
            <tbody>
                @foreach($menus as $menu)
                <tr>
                    <td class="border px-4 py-2">{{ $menu->name }}</td>
                    <td class="border px-4 py-2">Rp {{ number_format($menu->price,0,',','.') }}</td>
                    <td class="border px-4 py-2">{{ $menu->category }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
