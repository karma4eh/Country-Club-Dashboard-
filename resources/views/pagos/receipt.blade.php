@extends('layouts.app')

@section('title', 'Comprobante Oficial de Pago #REC-' . str_pad($pago->id, 6, '0', STR_PAD_LEFT))
@section('header_title', 'Factura & Recibo de Caja')
@section('header_subtitle', 'Comprobante oficial emitido por la Administración del Country Club')

@section('content')
@php
    // Formateo del teléfono para enlace directo a WhatsApp (E.164 para Venezuela)
    $phone = preg_replace('/[^0-9]/', '', $pago->socio->numero ?? '');
    if (str_starts_with($phone, '0')) {
        $phone = '58' . substr($phone, 1);
    } elseif (!empty($phone) && !str_starts_with($phone, '58') && strlen($phone) == 10) {
        $phone = '58' . $phone;
    }

    $textoWs = "★ *COUNTRY CLUB C.A.* ★\n"
             . "📄 *RECIBO OFICIAL DE COBRO #REC-" . str_pad($pago->id, 6, '0', STR_PAD_LEFT) . "*\n\n"
             . "Estimado(a) *" . ($pago->socio->nombre_completo ?? 'Socio Titular') . "*,\n"
             . "Le confirmamos que su pago ha sido procesado satisfactoriamente en Caja Principal:\n\n"
             . "• *Comprobante N°:* REC-" . str_pad($pago->id, 6, '0', STR_PAD_LEFT) . "\n"
             . "• *Acción / Membresía:* #" . ($pago->socio->accion ?? 'N/A') . "\n"
             . "• *Cédula:* V-" . ($pago->socio->cedula ?? 'N/A') . "\n"
             . "• *Concepto:* " . $pago->descripcion . "\n"
             . "• *Monto Abonado:* $" . number_format($pago->monto, 2) . " USD (Bs. " . number_format($montoBs, 2, ',', '.') . ")\n"
             . "• *Tasa BCV:* " . number_format($tasa, 2, ',', '.') . " Bs/USD\n"
             . "• *Fecha:* " . ($pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y h:i A') : date('d/m/Y h:i A')) . "\n"
             . "• *Saldo Actual:* $" . number_format($pago->socio->saldo ?? 0, 2) . " USD (" . (($pago->socio->saldo ?? 0) >= 0 ? 'SOLVENTE' : 'PENDIENTE') . ")\n\n"
             . "🔗 *Ver Factura Digital:*\n"
             . route('pagos.receipt', $pago) . "\n\n"
             . "_Dirección de Administración y Finanzas • Country Club C.A._";

    $wsUrl = !empty($phone) 
        ? "https://wa.me/{$phone}?text=" . rawurlencode($textoWs) 
        : "https://wa.me/?text=" . rawurlencode($textoWs);
@endphp

<div class="max-w-3xl mx-auto space-y-4 pb-12">

    <!-- BARRA DE ACCIONES SUPERIOR (Oculta al imprimir) -->
    <div class="flex flex-wrap items-center justify-between gap-3 bg-[#111827] border border-[#1F293D] rounded-2xl px-5 py-2.5 shadow-lg no-print">
        <div class="flex items-center space-x-2">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-[#141C30] hover:bg-[#1C2742] text-slate-300 hover:text-white text-xs font-semibold border border-[#243352] transition">
                <span class="material-icons-round text-sm">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('pagos.index') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-[#141C30] hover:bg-[#1C2742] text-slate-300 hover:text-white text-xs font-semibold border border-[#243352] transition">
                <span class="material-icons-round text-sm">receipt_long</span>
                <span>Historial</span>
            </a>
            <a href="{{ route('pagos.create') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-[#141C30] hover:bg-[#1C2742] text-slate-300 hover:text-white text-xs font-semibold border border-[#243352] transition">
                <span class="material-icons-round text-sm">point_of_sale</span>
                <span>Nuevo Cobro</span>
            </a>
        </div>

        <div class="flex items-center space-x-2.5">
            <!-- Botón WhatsApp -->
            <a href="{{ $wsUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl bg-[#25D366] hover:bg-[#20BD5A] text-slate-950 font-bold text-xs uppercase tracking-wider shadow-md shadow-[#25D366]/20 transition active:scale-[0.98]">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.423-14.416c-6.627 0-12 5.373-12 12 0 2.155.57 4.178 1.564 5.927l-1.658 6.063 6.22-1.632c1.679.914 3.601 1.43 5.64 1.43 6.627 0 12-5.373 12-12s-5.373-12-12-12z"/>
                </svg>
                <span>WhatsApp</span>
            </a>

            <!-- Botón Imprimir Factura -->
            <button type="button" onclick="window.print()" class="inline-flex items-center space-x-1.5 px-4 py-1.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-amber-500/20 transition active:scale-[0.98]">
                <span class="material-icons-round text-base">print</span>
                <span>Imprimir Factura</span>
            </button>
        </div>
    </div>

    <!-- =========================================================================================
         DOCUMENTO FORMAL: FACTURA CORPORATIVA BASADA EN EL DISEÑO DE REFERENCIA (ONDAS DORADO/OSCURO)
         ========================================================================================= -->
    <div id="factura-imprimible" class="bg-white text-slate-900 rounded-2xl shadow-2xl overflow-hidden relative border border-slate-200 print:shadow-none print:border-none print:m-0 print:rounded-none">

        <!-- ONDAS GEOMÉTRICAS SUPERIORES (DISEÑO CORPORATIVO BORCELLE / COUNTRY CLUB) -->
        <div class="relative w-full h-[120px] sm:h-[135px] overflow-hidden select-none bg-white">
            <svg viewBox="0 0 800 135" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full block" preserveAspectRatio="none">
                <!-- Onda Dorada Superior Derecha -->
                <path d="M350,0 C480,48 640,55 800,105 L800,0 Z" fill="#F2B818" />
                <!-- Onda Oscura Superior Izquierda que cruza -->
                <path d="M0,0 L440,0 C345,45 220,65 130,85 C50,102 15,118 0,135 Z" fill="#24292F" />
            </svg>
        </div>

        <!-- CUERPO DE LA FACTURA -->
        <div class="px-8 sm:px-12 pt-1 pb-4 relative z-10 space-y-5">

            <!-- 1. ENCABEZADO: DATOS DEL CLIENTE (IZQUIERDA) & LOGO OFICIAL (DERECHA) -->
            <div class="flex flex-col sm:flex-row items-start justify-between gap-6">
                <!-- Columna Izquierda: Datos del Cliente -->
                <div class="space-y-1">
                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-slate-900">DATOS DEL CLIENTE</h2>
                    <div class="text-xs text-slate-600 leading-relaxed font-sans">
                        <p><strong class="font-semibold text-slate-800">Nombre:</strong> {{ $pago->socio->nombre_completo ?? 'Socio Titular' }}</p>
                        <p><strong class="font-semibold text-slate-800">Cédula:</strong> V-{{ $pago->socio->cedula ?? '' }} &nbsp;•&nbsp; <strong class="font-semibold text-slate-800">Acción:</strong> #{{ $pago->socio->accion ?? '' }}</p>
                        <p><strong class="font-semibold text-slate-800">Dirección:</strong> Av. Principal del Country Club, Quinta Club House, Caracas</p>
                        <p><strong class="font-semibold text-slate-800">Mail:</strong> {{ $pago->socio->correo ?? 'No registrado' }}</p>
                        <p><strong class="font-semibold text-slate-800">Teléfono:</strong> {{ $pago->socio->numero ?? 'No registrado' }}</p>
                    </div>
                </div>

                <!-- Columna Derecha: Logotipo Oficial del Country Club & Badge -->
                <div class="flex flex-col items-end sm:text-right">
                    <img src="{{ asset('img/logo.png') }}" alt="Country Club C.A." class="h-14 sm:h-16 w-auto object-contain">
                    <h1 class="text-base font-extrabold text-slate-900 font-serif tracking-tight mt-1">COUNTRY CLUB C.A.</h1>
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-800 border border-emerald-300">
                        COMPROBANTE OFICIAL DE INGRESO
                    </span>
                    <span class="text-[9px] font-mono text-slate-500 mt-0.5">Control: 00-{{ str_pad($pago->id * 11 + 1042, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>

            <!-- 2. BARRA DE FECHA & IDENTIFICACIÓN DE FACTURA -->
            <div class="flex flex-wrap items-center justify-between border-b border-slate-200 pb-2 pt-1 text-xs">
                <div class="font-extrabold text-slate-900 text-sm">
                    Fecha: <span class="font-medium text-slate-700">{{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y') : date('d/m/Y') }}</span>
                </div>
                <div class="font-mono text-xs text-slate-600">
                    Factura / Recibo: <strong class="text-slate-900 font-bold text-sm">REC-{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}</strong>
                </div>
            </div>

            <!-- 3. TABLA DE CONCEPTOS (4 PILL HEADER CHARCOAL COMO EN LA IMAGEN DE REFERENCIA) -->
            <div class="overflow-hidden">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-white text-xs font-semibold">
                            <th class="py-2 px-4 text-left bg-[#24292F] rounded-l-md font-bold">Concepto</th>
                            <th class="py-2 px-3 text-center bg-[#24292F] font-bold w-24">Cantidad</th>
                            <th class="py-2 px-4 text-right bg-[#24292F] font-bold w-32">Precio ($ USD)</th>
                            <th class="py-2 px-4 text-right bg-[#24292F] rounded-r-md font-bold w-32">Total ($ USD)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800 font-sans">
                        <tr>
                            <td class="py-3 px-4 font-semibold text-slate-900">
                                {{ $pago->descripcion }}
                                <span class="block text-[10px] text-slate-500 font-normal mt-0.5">
                                    Cuota ordinaria de membresía • Período: {{ $pago->fecha_pago ? $pago->fecha_pago->format('m/Y') : now()->format('m/Y') }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center font-mono">1</td>
                            <td class="py-3 px-4 text-right font-mono font-semibold">${{ number_format($pago->monto, 2) }}</td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-slate-950">${{ number_format($pago->monto, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- 4. BLOQUE DE LIQUIDACIÓN: FORMA DE PAGO/NOTA (IZQUIERDA) & TOTALES (DERECHA) -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 pt-2">
                <!-- Columna Izquierda: Forma de Pago y Nota Legal con Tasa BCV -->
                <div class="md:col-span-7 space-y-2 text-xs text-slate-600">
                    <p><strong class="font-semibold text-slate-800">Forma de pago:</strong> Transferencia Bancaria / Caja Principal</p>
                    <p class="leading-relaxed">
                        <strong class="font-semibold text-slate-800">Nota:</strong> El pago acreditado corresponde al mantenimiento de membresía y acceso integral a instalaciones del Country Club.
                    </p>
                    <div class="p-2.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-[11px] flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <img src="{{ asset('img/Banco_Central_de_Venezuela_logo.svg') }}" alt="BCV" class="h-3.5 w-auto">
                            <span>Convenio Cambiario N° 1 • Tasa Oficial BCV:</span>
                        </div>
                        <strong class="font-mono text-xs text-amber-950 font-bold">{{ number_format($tasa, 2, ',', '.') }} Bs / USD</strong>
                    </div>
                </div>

                <!-- Columna Derecha: Cuadro de Totales (Exacto a la imagen de referencia) -->
                <div class="md:col-span-5 space-y-1.5 text-xs text-right">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal:</span>
                        <span class="font-mono font-semibold text-slate-800">${{ number_format($pago->monto, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Exento I.V.A. (0%):</span>
                        <span class="font-mono font-semibold text-slate-800">$0.00</span>
                    </div>
                    <div class="border-t border-slate-300 pt-1 flex justify-between items-baseline">
                        <span class="text-sm font-black text-slate-900 uppercase">Total USD:</span>
                        <span class="text-xl font-mono font-black text-slate-950">${{ number_format($pago->monto, 2) }}</span>
                    </div>
                    <div class="pt-1">
                        <div class="p-2 rounded-lg bg-[#24292F] text-white flex justify-between items-baseline">
                            <span class="text-[9px] font-bold text-amber-400 uppercase tracking-wider">Total en Bolívares:</span>
                            <span class="text-sm font-mono font-black text-amber-300">Bs. {{ number_format($montoBs, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. BLOQUE INFERIOR: DATOS DE LA EMPRESA (IZQ) & FIRMA DEL TITULAR CON SELLO DE TINTA (DER) -->
            <div class="pt-4 grid grid-cols-1 md:grid-cols-12 gap-6 items-end relative">

                <!-- Columna Izquierda: Datos del Emisor (Como en la imagen de referencia) -->
                <div class="md:col-span-6 text-xs text-slate-600 space-y-0.5">
                    <p class="font-bold text-slate-900 text-sm">Dirección de Administración y Finanzas</p>
                    <p><strong class="font-semibold text-slate-800">Empresa:</strong> Country Club C.A.</p>
                    <p><strong class="font-semibold text-slate-800">R.I.F.:</strong> J-00028491-3 • <strong class="font-semibold text-slate-800">N.I.T.:</strong> 0142859201</p>
                    <p><strong class="font-semibold text-slate-800">Dirección:</strong> Av. Principal del Country Club, Quinta Club House, Caracas</p>
                    <p><strong class="font-semibold text-slate-800">Mail:</strong> administracion@countryclub.com.ve</p>
                    <p><strong class="font-semibold text-slate-800">Teléfono:</strong> (0212) 261-0000</p>
                </div>

                <!-- Columna Derecha: Firma Conforme y Sello de Tinta Realista -->
                <div class="md:col-span-6 flex flex-col items-center sm:items-end space-y-3 relative">

                    <!-- SELLO DE TINTA DE CAUCHO REALISTA ("CANCELADO" / CAJA PRINCIPAL) & QR FISCAL -->
                    <div class="flex items-center space-x-3 select-none">
                        <!-- QR Verificación -->
                        <div class="p-1 rounded-lg border border-slate-300 bg-white flex flex-col items-center justify-center flex-shrink-0 shadow-sm">
                            <div id="qrcodeFactura" class="flex items-center justify-center"></div>
                            <span class="text-[6px] font-mono text-slate-500 font-bold uppercase mt-0.5">QR Fiscal</span>
                        </div>

                        <!-- Sello de Tinta de Caucho Realista (Sin estrellas, con desgaste de tinta real) -->
                        <div class="stamp-ink-container select-none">
                            <svg viewBox="0 0 280 120" class="w-48 sm:w-52 h-auto filter drop-shadow(0 2px 4px rgba(0,0,0,0.25))" style="transform: rotate(-7deg); transform-origin: center center;" aria-label="Sello de Tinta Cancelado">
                                <defs>
                                    <filter id="stamp-real-ink" x="-20%" y="-20%" width="140%" height="140%">
                                        <feTurbulence type="fractalNoise" baseFrequency="0.09" numOctaves="4" result="paperFiber" />
                                        <feDisplacementMap in="SourceGraphic" in2="paperFiber" scale="2.8" xChannelSelector="R" yChannelSelector="G" result="roughInk" />
                                        <feGaussianBlur in="roughInk" stdDeviation="0.3" result="bleed" />
                                        <feMerge>
                                            <feMergeNode in="bleed" opacity="0.6" />
                                            <feMergeNode in="roughInk" />
                                        </feMerge>
                                    </filter>

                                    <mask id="stamp-wear-mask">
                                        <rect x="0" y="0" width="280" height="120" fill="#FFFFFF" />
                                        <circle cx="38" cy="22" r="3" fill="#666" />
                                        <circle cx="85" cy="58" r="2" fill="#777" />
                                        <circle cx="140" cy="54" r="3" fill="#666" />
                                        <circle cx="210" cy="38" r="2.5" fill="#777" />
                                        <circle cx="245" cy="74" r="3" fill="#666" />
                                        <circle cx="65" cy="90" r="2.5" fill="#777" />
                                        <circle cx="180" cy="95" r="2" fill="#888" />
                                        <circle cx="120" cy="24" r="2.5" fill="#777" />
                                        <circle cx="195" cy="62" r="2.5" fill="#666" />
                                        <line x1="90" y1="60" x2="155" y2="60" stroke="#777" stroke-width="1.2" />
                                    </mask>
                                </defs>

                                <g filter="url(#stamp-real-ink)" mask="url(#stamp-wear-mask)" fill="#DC2626" stroke="#DC2626">
                                    <rect x="4" y="4" width="272" height="112" rx="8" ry="8" fill="rgba(220, 38, 38, 0.05)" stroke="none" />
                                    <rect x="4" y="4" width="272" height="112" rx="8" ry="8" fill="none" stroke="#DC2626" stroke-width="3.2" />
                                    <rect x="10" y="10" width="260" height="100" rx="5" ry="5" fill="none" stroke="#DC2626" stroke-width="1.4" opacity="0.9" />

                                    <text x="140" y="28" text-anchor="middle" font-family="'Impact', 'Arial Black', sans-serif" font-weight="900" font-size="12" letter-spacing="3" fill="#DC2626" stroke="none">COUNTRY CLUB C.A.</text>
                                    <line x1="18" y1="35" x2="262" y2="35" stroke="#DC2626" stroke-width="1.8" />
                                    <line x1="22" y1="38" x2="258" y2="38" stroke="#DC2626" stroke-width="0.8" opacity="0.7" />

                                    <text x="140" y="67" text-anchor="middle" font-family="'Impact', 'Arial Black', sans-serif" font-weight="900" font-size="28" letter-spacing="4" fill="#DC2626" stroke="none">CANCELADO</text>
                                    <line x1="22" y1="74" x2="258" y2="74" stroke="#DC2626" stroke-width="0.8" opacity="0.7" />
                                    <line x1="18" y1="77" x2="262" y2="77" stroke="#DC2626" stroke-width="1.8" />

                                    <text x="140" y="91" text-anchor="middle" font-family="'Arial Black', sans-serif" font-weight="800" font-size="9" letter-spacing="1.8" fill="#DC2626" stroke="none">CAJA PRINCIPAL • RECAUDACIÓN</text>
                                    <text x="140" y="105" text-anchor="middle" font-family="'Courier New', monospace" font-weight="bold" font-size="11.5" letter-spacing="2.5" fill="#EF4444" stroke="none">{{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y') : date('d/m/Y') }}</text>
                                </g>
                            </svg>
                        </div>
                    </div>

                    <!-- Línea de Firma Idéntica a la Imagen de Referencia ("Sandra Haro") -->
                    <div class="w-56 text-center pt-2">
                        <div class="border-b-2 border-slate-400 w-full mb-1"></div>
                        <p class="font-bold text-slate-900 text-xs tracking-wide">{{ $pago->socio->nombre_completo ?? 'Socio Titular' }}</p>
                        <p class="text-[9px] text-slate-500 uppercase tracking-wider">Firma Conforme del Titular</p>
                    </div>
                </div>

            </div>

        </div>

        <!-- ONDAS GEOMÉTRICAS INFERIORES (DISEÑO CORPORATIVO BORCELLE / COUNTRY CLUB) -->
        <div class="relative w-full h-[100px] sm:h-[115px] overflow-hidden select-none bg-white -mt-2">
            <svg viewBox="0 0 800 115" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full block" preserveAspectRatio="none">
                <!-- Detalle Dorado Inferior Izquierdo -->
                <path d="M0,45 C65,45 105,80 125,115 L0,115 Z" fill="#F2B818" />
                <!-- Onda Oscura Inferior que cruza hacia la derecha -->
                <path d="M0,95 C160,88 420,70 800,0 L800,115 L0,115 Z" fill="#24292F" />
            </svg>
        </div>

    </div>

</div>

@push('styles')
<style>
    /* Estilos de Impresión Fidedigna */
    @media print {
        @page {
            size: letter portrait;
            margin: 0;
        }

        body {
            background: #FFFFFF !important;
            color: #000000 !important;
            font-size: 11px !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        aside, header, footer, .no-print, .ambient-glow-amber, .ambient-glow-emerald {
            display: none !important;
        }

        main {
            padding: 0 !important;
            margin: 0 !important;
            overflow: visible !important;
        }

        #factura-imprimible {
            border: none !important;
            box-shadow: none !important;
            margin: 0 !important;
            background: #FFFFFF !important;
            color: #000000 !important;
            page-break-inside: avoid;
            border-radius: 0 !important;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/qrcode.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const qrContainer = document.getElementById('qrcodeFactura');
        if (qrContainer) {
            new QRCode(qrContainer, {
                text: JSON.stringify({
                    doc: "REC-{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}",
                    socio: "{{ $pago->socio->nombre_completo ?? 'Socio' }}",
                    cedula: "{{ $pago->socio->cedula ?? '' }}",
                    accion: "{{ $pago->socio->accion ?? '' }}",
                    monto_usd: {{ (float) $pago->monto }},
                    monto_bs: {{ round((float) $montoBs, 2) }},
                    tasa: {{ round((float) $tasa, 2) }},
                    fecha: "{{ $pago->fecha_pago ? $pago->fecha_pago->format('Y-m-d') : date('Y-m-d') }}"
                }),
                width: 55,
                height: 55,
                colorDark: "#111827",
                colorLight: "#FFFFFF",
                correctLevel: QRCode.CorrectLevel.M
            });
        }
    });
</script>
@endpush
@endsection
