<!-- resources/views/pdf/boleta.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Boleta de Compra</title>
</head>
<body>
    

    <h1>Boleta de Compra</h1>
    <p><strong>Proveedor:</strong> {{ $ingreso->nombre }}</p>
    <p><strong>Tipo Comprobante:</strong> {{ $ingreso->tipo_comprobante }}</p>
    <p><strong>Serie:</strong> {{ $ingreso->serie_comprobante }}</p>
    <p><strong>Número:</strong> {{ $ingreso->num_comprobante }}</p>
    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($ingreso->created_at)->format('d/m/Y') }}</p>
    <p><strong>Total:</strong> {{ number_format($ingreso->total, 2) }}</p>
    <h2>Detalles</h2>
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Artículo</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $detalle)
            <tr>
                <td>{{ $detalle->articulo }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>{{ number_format($detalle->precio, 2) }}</td>
                <td>{{ number_format($detalle->cantidad * $detalle->precio, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>