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
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }
        .header {
            background: linear-gradient(135deg, #18233C 0%, #0F172A 100%);
            padding: 35px 30px;
            text-align: center;
            border-bottom: 2px solid #F59E0B;
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
            margin-top: 5px;
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
        .action-box {
            background: #0A0F1E;
            border: 1px solid rgba(245, 158, 11, 0.4);
            border-radius: 16px;
            padding: 22px;
            text-align: center;
            margin-bottom: 28px;
        }
        .action-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #F59E0B;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .action-number {
            font-family: 'Courier New', Courier, monospace;
            font-size: 32px;
            font-weight: 800;
            color: #FDE68A;
            letter-spacing: 4px;
            margin: 0;
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
        <!-- Cabecera -->
        <div class="header">
            <h1 class="logo-text">COUNTRY CLUB</h1>
            <div class="logo-sub">Constancia de Afiliación & Membresía Oficial</div>
        </div>

        <!-- Contenido Principal -->
        <div class="body-content">
            <h2 class="greeting">¡Bienvenido(a), {{ $socio->nombre_completo }}!</h2>
            <p class="lead-text">
                Nos complace informarle que su proceso de afiliación como socio titular del <strong>Country Club</strong> ha sido procesado exitosamente. A partir de este momento, usted y su grupo familiar pueden disfrutar de todas las instalaciones sociales, deportivas y recreativas.
            </p>

            <!-- NÚMERO DE ACCIÓN DESTACADO -->
            <div class="action-box">
                <div class="action-label">Número de Acción / Membresía Asignada</div>
                <div class="action-number">{{ $socio->accion }}</div>
            </div>

            <!-- Ficha de Registro -->
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
                    <td class="value" style="color: #F59E0B;">{{ $socio->accion }}</td>
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
                    Al ingresar a las instalaciones del club, el personal de vigilancia en la garita principal registrará su visita mediante el <strong>código QR de su carnet digital</strong> o simplemente indicando su <strong>número de cédula o de acción ({{ $socio->accion }})</strong>.
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
