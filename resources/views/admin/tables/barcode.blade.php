<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>QR Code Meja {{ $table->name ?? $table->id }}</title>
</head>
<body style="text-align:center; font-family:sans-serif;">

    <h2>QR Code Meja {{ $table->name ?? $table->id }}</h2>

    <div>
        {!! QrCode::size(250)->generate($url) !!}
    </div>

    <p style="margin-top:20px; font-size:14px;">
        Scan untuk order: <br>
        <strong>{{ $url }}</strong>
    </p>

    <button onclick="window.print()" style="padding:10px 20px; margin-top:30px; cursor:pointer;">
        Print QR Code
    </button>

</body>
</html>
