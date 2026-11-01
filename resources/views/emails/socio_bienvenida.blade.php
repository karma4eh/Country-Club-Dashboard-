<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida Country Club</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #0B0F19;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #E2E8F0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #111827;
            border: 1px solid #1F293D;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
        }
        .header {
            background: linear-gradient(135deg, #18233C 0%, #0F172A 100%);
            padding: 35px 30px;
            text-align: center;
            border-bottom: 2px solid #F59E0B;
        }
        .logo-img {
            max-height: 75px;
            width: auto;
            display: block;
            margin: 0 auto 14px auto;
        }
        .logo-text {
            color: #FBBF24;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 3px;
            margin: 0;
            text-transform: uppercase;
        }
        .logo-sub {
            color: #94A3B8;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 6px;
        }
        .body-content {
            padding: 35px 30px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 700;
            color: #FFFFFF;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .lead-text {
            color: #94A3B8;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .carnet-box {
            background: linear-gradient(145deg, #141D30 0%, #0A0F1D 100%);
            border: 1px solid rgba(245, 158, 11, 0.4);
            border-radius: 18px;
            padding: 24px;
            text-align: center;
            margin-bottom: 28px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
        }
        .carnet-header {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #F59E0B;
            font-weight: 800;
            margin-bottom: 6px;
        }
        .action-number {
            font-family: 'Courier New', Courier, monospace;
            font-size: 32px;
            font-weight: 800;
            color: #FDE68A;
            letter-spacing: 3px;
            margin: 0 0 4px 0;
        }
        .socio-titular {
            font-size: 13px;
            color: #CBD5E1;
            margin-bottom: 18px;
        }
        .qr-wrapper {
            display: inline-block;
            background-color: #060911;
            border: 1px solid rgba(245, 158, 11, 0.35);
            border-radius: 16px;
            padding: 14px;
            margin: 0 auto;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.6);
        }
        .qr-img {
            width: 180px;
            height: 180px;
            display: block;
            margin: 0 auto;
            border-radius: 8px;
        }
        .qr-legend {
            font-size: 10px;
            font-family: 'Courier New', monospace;
            color: #FBBF24;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 10px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 13px;
        }
        .details-table td {
            padding: 10px 14px;
            border-bottom: 1px solid #1F293D;
        }
        .details-table td.label {
            color: #94A3B8;
            font-weight: 600;
            width: 40%;
        }
        .details-table td.value {
            color: #F1F5F9;
            font-weight: bold;
        }
        .access-box {
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.25);
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 25px;
        }
        .access-box h4 {
            color: #34D399;
            margin: 0 0 6px 0;
            font-size: 13px;
            font-weight: 700;
        }
        .access-box p {
            color: #CBD5E1;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
        }
        .footer {
            background: #0A0E1A;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #1F293D;
            font-size: 11px;
            color: #64748B;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Cabecera Oficial con Logo del Country Club -->
        <div class="header">
            @if(file_exists(public_path('img/logo.png')))
                <img src="{{ $message->embed(public_path('img/logo.png')) }}" alt="Country Club" class="logo-img">
            @endif
            <h1 class="logo-text">COUNTRY CLUB</h1>
            <div class="logo-sub">Constancia de Afiliación &amp; Carnet Digital Oficial</div>
        </div>

        <!-- Contenido Principal -->
        <div class="body-content">
            <h2 class="greeting">¡Bienvenido(a), {{ $socio->nombre_completo }}!</h2>
            <p class="lead-text">
                Nos complace informarle que su proceso de afiliación como socio titular del <strong>Country Club</strong> ha sido procesado exitosamente. A partir de este momento, usted y su grupo familiar pueden disfrutar de todas las instalaciones sociales, deportivas y recreativas.
            </p>

            <!-- CARNET DIGITAL CON QR EN TEMA OSCURO (SIN FONDO BLANCO) -->
            <div class="carnet-box">
                <div class="carnet-header">Membresía Oficial • Control de Acceso</div>
                <div class="action-number">Acción #{{ $socio->accion }}</div>
                <div class="socio-titular">
                    Titular: <strong style="color: #FFFFFF;">{{ $socio->nombre_completo }}</strong> • C.I: <strong style="color: #F8FAFC;">V-{{ $socio->cedula }}</strong>
                </div>

                <div class="qr-wrapper">
                    @if(isset($qrPath) && file_exists($qrPath))
                        <img src="{{ $message->embed($qrPath) }}" alt="QR Acceso Garita" class="qr-img">
                    @endif
                    <div class="qr-legend">
                        Código de Acceso para Garita
                    </div>
                </div>

                <div style="margin-top: 14px; font-size: 11px; color: #94A3B8;">
                    Presente este carnet digital con código QR directamente desde su móvil o indique su <strong>Acción #{{ $socio->accion }}</strong> al ingresar.
                </div>
            </div>

            <!-- Ficha Detallada de Registro -->
            <table class="details-table">
                <tr>
                    <td class="label">Socio Titular:</td>
                    <td class="value">{{ $socio->nombre_completo }}</td>
                </tr>
                <tr>
                    <td class="label">Cédula de Identidad:</td>
                    <td class="value">V-{{ $socio->cedula }}</td>
                </tr>
                <tr>
                    <td class="label">Número de Acción:</td>
                    <td class="value" style="color: #F59E0B;">Acción #{{ $socio->accion }}</td>
                </tr>
                <tr>
                    <td class="label">Fecha de Emisión:</td>
                    <td class="value">{{ now()->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Estado de Afiliación:</td>
                    <td class="value" style="color: #34D399;">ACTIVO / HABILITADO</td>
                </tr>
            </table>

            <!-- Instrucciones de Garita y Control de Visitas -->
            <div class="access-box">
                <h4>🛡️ Instrucciones para el Control de Acceso (Garita de Vigilancia)</h4>
                <p>
                    Al ingresar a las instalaciones del club, el personal de vigilancia en la garita principal registrará su visita mediante el <strong>código QR de su carnet digital</strong> o simplemente indicando su <strong>número de cédula o de acción (#{{ $socio->accion }})</strong>.
                </p>
            </div>

            <p style="font-size: 12px; color: #94A3B8; line-height: 1.6;">
                Si tiene alguna consulta administrativa o desea realizar pagos de mantenimiento, puede comunicarse con la oficina de atención al socio o acercarse a nuestras cajas en horario habitual.
            </p>
        </div>

        <!-- Pie de Página -->
        <div class="footer">
            <p style="margin: 0 0 5px 0;">&copy; {{ date('Y') }} Country Club C.A. Todos los derechos reservados.</p>
            <p style="margin: 0;">Dirección de Administración, Membresías y Seguridad.</p>
        </div>
    </div>
</body>
</html>
