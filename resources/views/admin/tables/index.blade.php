@extends('layouts.admin')

@section('content')

<h1 class="text-2xl font-bold mb-4">Daftar Meja</h1>

<a href="{{ route('tables.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">
    Tambah Meja
</a>

<table class="table-auto w-full mt-4 border">
    <thead>
        <tr class="bg-gray-100">
            <th class="border px-4 py-2">Nama</th>
            <th class="border px-4 py-2">Slug</th>
            <th class="border px-4 py-2">QR</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tables as $table)
        <tr>
            <td class="border px-4 py-2">{{ $table->name }}</td>
            <td class="border px-4 py-2">{{ $table->slug }}</td>
            <td class="border px-4 py-2">
                <img src="{{ asset($table->qrcode) }}" class="w-24">
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
