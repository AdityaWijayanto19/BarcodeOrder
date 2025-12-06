@extends('layouts.admin')

@section('content')

<h1 class="text-2xl font-bold mb-4">Tambah Meja</h1>

<form action="{{ route('tables.store') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label class="block font-semibold">Nama Meja</label>
        <input type="text" name="name" class="border w-full px-3 py-2" placeholder="Meja 1">
    </div>

    <button class="px-4 py-2 bg-green-600 text-white rounded">
        Simpan
    </button>
</form>

@endsection
