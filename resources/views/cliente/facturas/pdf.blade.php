<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #{{ $factura->id_factura }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; margin: 24px; }
        h1 { font-size: 18px; margin: 0 0 8px; }
        .muted { color: #6b7280; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px 10px; text-align: left; }
        th { background: #f9fafb; font-weight: 600; font-size: 11px; }
        td.price { text-align: right; }
        tfoot td { font-weight: bold; background: #f9fafb; }
        .header-box { margin-bottom: 20px; }
        .row { margin-bottom: 4px; }
    </style>
</head>
<body>
    <div class="header-box">
        <h1>La Cuponera SV</h1>
        <div class="muted">Comprobante de compra</div>
    </div>

    <div class="row"><strong>Factura N.º</strong> {{ $factura->id_factura }}</div>
    <div class="row"><strong>Fecha</strong>
        @if($factura->fecha_compra instanceof \Carbon\Carbon)
            {{ $factura->fecha_compra->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
        @else
            {{ $factura->fecha_compra }}
        @endif
    </div>
    <div class="row"><strong>Cliente</strong> {{ Auth::user()->cliente->nombres }} {{ Auth::user()->cliente->apellidos }}</div>
    <div class="row"><strong>Método de pago</strong> {{ $factura->metodo_pago }}</div>
    <div class="row"><strong>Número de tarjeta</strong> {{ $factura->numero_tarjeta }}</div>

    <table>
        <thead>
            <tr>
                <th>Oferta</th>
                <th>Código</th>
                <th style="text-align:right;">Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($factura->cuponesComprados as $cupon)
                <tr>
                    <td>{{ $cupon->oferta->titulo ?? '—' }}</td>
                    <td style="font-size: 10px;">{{ $cupon->codigo_unico }}</td>
                    <td class="price">${{ number_format($cupon->precio_al_comprar, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align:right;">Total</td>
                <td class="price">${{ number_format($factura->total_pagado, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
