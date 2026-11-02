<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante Oficial de Pago - Country Club C.A.</title>
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
            padding: 30px 25px;
            text-align: center;
            border-bottom: 2px solid #F59E0B;
        }
        .logo-img {
            max-height: 60px;
            width: auto;
            display: block;
            margin: 0 auto 12px auto;
        }
        .logo-text {
            color: #FBBF24;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 2px;
            margin: 0;
            text-transform: uppercase;
        }
        .logo-sub {
            color: #94A3B8;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 4px;
        }
        .body-content {
            padding: 30px 25px;
        }
        .success-badge {
            background-color: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.35);
            border-radius: 12px;
            padding: 12px 18px;
            text-align: center;
            margin-bottom: 24px;
        }
        .success-title {
            color: #34D399;
            font-size: 16px;
            font-weight: 800;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .success-sub {
            color: #94A3B8;
            font-size: 12px;
            margin: 0;
        }
        .receipt-card {
            background: #0A0F1E;
            border: 1px solid #1F293D;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #1F293D;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .receipt-num {
            font-family: monospace;
            font-size: 18px;
            font-weight: 800;
            color: #FBBF24;
        }
        .receipt-date {
            font-size: 12px;
            color: #94A3B8;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .data-table td {
            padding: 8px 0;
            border-bottom: 1px solid rgba(31, 41, 61, 0.5);
        }
        .data-label {
            color: #94A3B8;
            width: 40%;
        }
        .data-value {
            color: #FFFFFF;
            font-weight: 600;
            text-align: right;
        }
        .balance-box {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 12px;
            padding: 14px 18px;
            margin-top: 16px;
            text-align: center;
        }
        .balance-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6EE7B7;
            font-weight: 700;
        }
        .balance-amount {
            font-family: monospace;
            font-size: 22px;
            font-weight: 900;
            color: #10B981;
            margin-top: 4px;
        }
        .button-container {
            text-align: center;
            margin: 28px 0 10px 0;
        }
        .btn-receipt {
            display: inline-block;
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            color: #0B0F19 !important;
            text-decoration: none;
            font-weight: 800;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 14px 32px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.25);
        }
        .footer {
            background-color: #090D17;
            padding: 22px 25px;
            text-align: center;
            font-size: 11px;
            color: #64748B;
            border-top: 1px solid #1F293D;
        }
        .footer p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- ENCABEZADO CORPORATIVO -->
        <div class="header">
            @if(file_exists(public_path('img/logo.png')))
                <img src="{{ $message->embed(public_path('img/logo.png')) }}" alt="Country Club" class="logo-img">
            @endif
            <h1 class="logo-text">Country Club C.A.</h1>
            <div class="logo-sub">Dirección de Administración y Finanzas • Caja Principal</div>
        </div>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="body-content">
            <!-- BANNER DINÁMICO DE PAGO SATISFACTORIO -->
            <div class="success-badge">
                <div class="success-title">✓ Pago Hecho Satisfactoriamente</div>
                <div class="success-sub">Su pago ha sido procesado y acreditado con éxito en su estado de cuenta.</div>
            </div>

            <!-- DETALLE DEL RECIBO -->
            <div class="receipt-card">
                <table style="width: 100%; margin-bottom: 12px;">
                    <tr>
                        <td align="left">
                            <span style="font-size: 10px; color: #94A3B8; text-transform: uppercase; font-weight: 700;">Comprobante de Caja</span><br>
                            <span class="receipt-num">#REC-{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td align="right">
                            <span style="font-size: 10px; color: #94A3B8; text-transform: uppercase; font-weight: 700;">Fecha de Emisión</span><br>
                            <span class="receipt-date">{{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y h:i A') : date('d/m/Y h:i A') }}</span>
                        </td>
                    </tr>
                </table>

                <table class="data-table">
                    <tr>
                        <td class="data-label">Socio Titular:</td>
                        <td class="data-value">{{ $socio->nombre_completo }}</td>
                    </tr>
                    <tr>
                        <td class="data-label">Cédula de Identidad:</td>
                        <td class="data-value">V-{{ $socio->cedula }}</td>
                    </tr>
                    <tr>
                        <td class="data-label">Acción / Membresía:</td>
                        <td class="data-value" style="color: #FBBF24;">Acción #{{ $socio->accion }}</td>
                    </tr>
                    <tr>
                        <td class="data-label">Concepto Facturado:</td>
                        <td class="data-value">{{ $pago->descripcion }}</td>
                    </tr>
                    <tr>
                        <td class="data-label">Monto Abonado ($ USD):</td>
                        <td class="data-value" style="color: #34D399; font-size: 15px;">${{ number_format($pago->monto, 2) }} USD</td>
                    </tr>
                    <tr>
                        <td class="data-label">Tasa Oficial BCV:</td>
                        <td class="data-value">{{ number_format($tasa, 2, ',', '.') }} Bs / USD</td>
                    </tr>
                    <tr>
                        <td class="data-label" style="border-bottom: none;">Total en Bolívares (Bs.):</td>
                        <td class="data-value" style="color: #FBBF24; font-size: 14px; border-bottom: none;">Bs. {{ number_format($montoBs, 2, ',', '.') }}</td>
                    </tr>
                </table>

                <!-- ESTADO ACTUAL DEL SOCIO POST-PAGO -->
                <div class="balance-box">
                    <div class="balance-label">Estado de Cuenta Actual del Socio</div>
                    <div class="balance-amount">${{ number_format($socio->saldo, 2) }} USD</div>
                    <div style="font-size: 11px; color: #34D399; font-weight: 700; margin-top: 2px;">
                        {{ $socio->saldo >= 0 ? '✓ SOCIO SOLVENTE Y AL DÍA' : 'PENDIENTE' }}
                    </div>
                </div>
            </div>

            <!-- BOTÓN DE ACCESO A LA FACTURA DIGITAL -->
            <div class="button-container">
                <a href="{{ route('pagos.receipt', $pago) }}" class="btn-receipt">
                    Ver y Descargar Factura Oficial
                </a>
            </div>
            <p style="text-align: center; font-size: 11px; color: #94A3B8; margin-top: 10px;">
                También puede consultar este recibo con su código QR fiscal en cualquier momento.
            </p>
        </div>

        <!-- PIE DE PÁGINA -->
        <div class="footer">
            <p><strong>Country Club C.A. • R.I.F.: J-00028491-3</strong></p>
            <p>Av. Principal del Country Club, Quinta Club House, Caracas, Venezuela</p>
            <p>Atención al Socio: (0212) 261-0000 • administracion@countryclub.com.ve</p>
        </div>
    </div>
</body>
</html>
