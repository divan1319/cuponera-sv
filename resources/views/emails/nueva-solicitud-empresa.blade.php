<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva solicitud de empresa</title>
</head>
<body style="font-family: system-ui, sans-serif; line-height: 1.5; color: #111827; max-width: 560px; margin: 0 auto; padding: 24px;">
    <p>Hola,</p>
    <p>Una nueva empresa ha solicitado registrarse en La Cuponera SV y está pendiente de revisión.</p>

    <table cellpadding="0" cellspacing="0" style="width: 100%; margin: 20px 0; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Empresa</strong></td>
            <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $empresa->nombre_empresa }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>NIT</strong></td>
            <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $empresa->nit }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Representante</strong></td>
            <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $representante->name }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Correo</strong></td>
            <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $representante->email }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Teléfono</strong></td>
            <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $empresa->telefono }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; vertical-align: top;"><strong>Dirección</strong></td>
            <td style="padding: 8px 0;">{{ $empresa->direccion }}</td>
        </tr>
    </table>

    <p>
        <a href="{{ route('admin.solicitudes') }}" style="display: inline-block; background: #2563eb; color: #fff; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-weight: 600;">Ver solicitudes pendientes</a>
    </p>

    <p style="margin-top: 28px; font-size: 13px; color: #6b7280;">Este mensaje se envió automáticamente desde La Cuponera SV.</p>
</body>
</html>
