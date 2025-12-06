@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-amber-400 mb-2">🔲 QR Code Barcodes</h1>
            <p class="text-slate-400">Barcode untuk setiap meja</p>
        </div>
        <a href="{{ route('admin.print-barcodes') }}" class="bg-amber-500 hover:bg-amber-600 text-black font-bold px-6 py-3 rounded-lg transition">
            🖨️ Print Semua
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($tables as $table)
            <div class="bg-slate-800 rounded-lg border border-slate-700 p-6 text-center hover:border-amber-400 transition">
                <h3 class="text-2xl font-bold text-amber-400 mb-4">{{ $table->name }}</h3>
                
                {{-- QR Code --}}
                <div class="bg-white p-4 rounded mb-4 flex justify-center">
                    {!! \QrCode::size(200)->generate(url('/order/form?table=' . $table->id)) !!}
                </div>
                
                <p class="text-slate-300 text-sm mb-4">Kapasitas: {{ $table->capacity }} orang</p>
                
                <div class="flex gap-2">
                    <a href="{{ route('table.barcode', $table->id) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3 rounded text-sm transition">
                        Preview
                    </a>
                    <button onclick="downloadBarcode({{ $table->id }}, '{{ $table->name }}')" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-3 rounded text-sm transition">
                        Download
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection

@section('scripts')
<script>
    function downloadBarcode(tableId, tableName) {
        const qrCodeDiv = event.target.closest('div').querySelector('svg');
        if (!qrCodeDiv) return;
        
        const svg = qrCodeDiv.outerHTML;
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        
        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
            
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png');
            link.download = `barcode-${tableName}.png`;
            link.click();
        };
        
        img.src = 'data:image/svg+xml;base64,' + btoa(svg);
    }
</script>
@endsection
