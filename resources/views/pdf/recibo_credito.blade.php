<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Cuota</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Recibo de Cuota</h1>
        <p>Fecha: {{ date('d/m/Y') }}</p>
    </div>

    <div class="details">
        <p><strong>Cliente:</strong> {{ $cliente->nombre ?? 'N/A' }}</p>
        <p><strong>Número de Venta:</strong> {{ $venta->num_comprobante ?? 'N/A' }}</p>
        <p><strong>Fecha de Venta:</strong> {{ $venta->fecha_hora ? \Carbon\Carbon::parse($venta->fecha_hora)->format('d/m/Y') : 'N/A' }}</p>
        <p><strong>Número de Cuota:</strong> {{ $numeroCuotaActual }} de {{ $totalCuotas }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Monto de la Cuota</td>
                <td>{{ number_format($cuota->precio_cuota, 2) }}</td>
            </tr>
            <tr>
                <td>Fecha de Pago</td>
                <td>{{ \Carbon\Carbon::parse($cuota->fecha_pago)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Estado</td>
                <td>{{ $cuota->estado }}</td>
            </tr>
            @if($cuota->fecha_cancelado)
            <tr>
                <td>Fecha de Cancelación</td>
                <td>{{ \Carbon\Carbon::parse($cuota->fecha_cancelado)->format('d/m/Y') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <p class="total">Saldo Restante del Crédito: {{ number_format($cuota->saldo ?? 0, 2) }}</p>

    <h3>Cuotas Restantes</h3>
    <table>
        <thead>
            <tr>
                <th>Número de Cuota</th>
                <th>Fecha de Pago</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cuotasRestantes as $index => $cuotaRestante)
            <tr>
                <td>{{ $numeroCuotaActual + $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($cuotaRestante->fecha_pago)->format('d/m/Y') }}</td>
                <td>{{ number_format($cuotaRestante->precio_cuota, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p>Total de cuotas restantes: {{ $cuotasRestantes->count() }}</p>
</body>
</html>