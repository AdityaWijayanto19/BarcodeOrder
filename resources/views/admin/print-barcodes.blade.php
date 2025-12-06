<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Print QR Barcode - RestoQR</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
        }
        
        .barcode-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .barcode-card {
            background: white;
            border: 2px solid #333;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            page-break-inside: avoid;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .barcode-card h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 15px;
            border-bottom: 2px solid #fbbf24;
            padding-bottom: 10px;
        }
        
        .barcode-card svg {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
        
        .barcode-card p {
            color: #666;
            font-size: 12px;
            margin-top: 10px;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .header {
                margin-bottom: 30px;
                page-break-after: avoid;
            }
            
            .barcode-grid {
                gap: 15px;
            }
            
            .barcode-card {
                padding: 15px;
                page-break-inside: avoid;
            }
            
            .no-print {
                display: none;
            }
        }
        
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .no-print button {
            background: #f59e0b;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        .no-print button:hover {
            background: #d97706;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print">
            <button onclick="window.print()">🖨️ Print Sekarang</button>
            <a href="{{ route('admin.barcodes') }}" style="margin-left: 10px; padding: 10px 30px; background: #666; color: white; text-decoration: none; border-radius: 6px; display: inline-block;">← Kembali</a>
        </div>

        <div class="header">
            <h1>🍽️ RestoQR - QR Code untuk Meja</h1>
            <p>Cetak dan tempel di setiap meja. Pelanggan bisa scan untuk memesan.</p>
        </div>

        <div class="barcode-grid">
            @foreach($tables as $table)
                <div class="barcode-card">
                    <h3>{{ $table->name }}</h3>
                    {!! \QrCode::size(200)->generate(url('/order/form?table=' . $table->id)) !!}
                    <p>Kapasitas: {{ $table->capacity }} orang</p>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
