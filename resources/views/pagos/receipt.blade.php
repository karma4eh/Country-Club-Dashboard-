@extends('layouts.app')

@section('title', 'Factura Oficial de Pago #REC-' . str_pad($pago->id, 6, '0', STR_PAD_LEFT))
@section('header_title', 'Factura & Recibo de Caja')
@section('header_subtitle', 'Comprobante oficial emitido por la Administración del Country Club')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">

    <!-- BARRA DE ACCIONES SUPERIOR (Exclusiva para pantalla, oculta al imprimir) -->
    <div class="flex flex-wrap items-center justify-between gap-4 bg-[#111827] border border-[#1F293D] rounded-2xl p-4 shadow-xl no-print">
        <div class="flex items-center space-x-3">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-[#162035] hover:bg-[#1E2B48] text-slate-300 hover:text-white text-xs font-semibold border border-[#243352] transition">
                <span class="material-icons-round text-base">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('pagos.index') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-[#162035] hover:bg-[#1E2B48] text-slate-300 hover:text-white text-xs font-semibold border border-[#243352] transition">
                <span class="material-icons-round text-base">receipt_long</span>
                <span>Historial de Cobros</span>
            </a>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('pagos.create') }}" class="inline-flex items-center space-x-1.5 px-4 py-2.5 rounded-xl bg-[#141C30] hover:bg-[#1C2742] text-amber-300 hover:text-amber-200 text-xs font-bold border border-amber-500/30 transition shadow-sm">
                <span class="material-icons-round text-base">point_of_sale</span>
                <span>Registrar Nuevo Cobro</span>
            </a>

            <button type="button" onclick="window.print()" class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-amber-500/20 transition active:scale-[0.98]">
                <span class="material-icons-round text-lg">print</span>
                <span>Imprimir Factura Oficial</span>
            </button>
        </div>
    </div>

    <!-- DOCUMENTO FORMAL: FACTURA / RECIBO DE COBRO (HOJA OFICIAL) -->
    <div id="factura-imprimible" class="bg-white text-slate-900 rounded-2xl shadow-2xl border border-slate-200 overflow-hidden relative print:shadow-none print:border-none print:m-0 print:p-0 print:rounded-none">

        <!-- Banda decorativa superior institucional -->
        <div class="h-2.5 bg-gradient-to-r from-[#0F172A] via-[#B45309] to-[#047857]"></div>

        <div class="p-8 sm:p-12 space-y-7">

            <!-- 1. ENCABEZADO INSTITUCIONAL & METADATOS FISCALES -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-6 border-b-2 border-slate-200">
                <!-- Logotipo y Datos de la Institución -->
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-2xl bg-slate-900 p-2.5 flex items-center justify-center shadow-md flex-shrink-0">
                        <img src="{{ asset('img/logo.png') }}" alt="Country Club" class="max-h-full w-auto object-contain">
                    </div>
                    <div>
                        <h1 class="text-2xl font-black tracking-tight text-slate-950 font-serif leading-none">COUNTRY CLUB C.A.</h1>
                        <p class="text-[11px] font-bold text-amber-800 uppercase tracking-widest mt-1">Dirección de Administración, Membresías y Finanzas</p>
                        <p class="text-[11px] text-slate-600 mt-0.5">R.I.F.: J-00028491-3 • NIT: 0142859201</p>
                        <p class="text-[10px] text-slate-500">Av. Principal del Country Club, Quinta Club House, Caracas 1060, Venezuela</p>
                        <p class="text-[10px] text-slate-500">Teléfonos: +58 (212) 261-0000 / 261-4433 • cobranzas@countryclub.com.ve</p>
                    </div>
                </div>

                <!-- Recuadro Fiscal de Emisión -->
                <div class="border-2 border-slate-800 rounded-xl p-4 bg-slate-50 text-right min-w-[240px] shadow-sm">
                    <span class="inline-block px-2.5 py-0.5 rounded text-[9px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-800 border border-emerald-300 mb-1.5">
                        COMPROBANTE OFICIAL DE INGRESO
                    </span>
                    <div class="text-[11px] font-bold text-slate-600 uppercase">Factura / Recibo N°</div>
                    <div class="text-2xl font-mono font-black text-slate-950 tracking-wider">
                        REC-{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="text-[10px] font-mono text-slate-500 mt-1">
                        Control: 00-{{ str_pad($pago->id * 11 + 1042, 6, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="text-[11px] text-slate-700 font-medium mt-1">
                        Emisión: <strong>{{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y') : now()->format('d/m/Y') }}</strong> • <strong>{{ $pago->fecha_pago ? $pago->fecha_pago->format('h:i A') : now()->format('h:i A') }}</strong>
                    </div>
                </div>
            </div>

            <!-- 2. DATOS DEL CLIENTE / SOCIO TITULAR -->
            <div class="bg-slate-50 rounded-xl p-5 border border-slate-200">
                <div class="text-[10px] font-extrabold uppercase tracking-widest text-slate-500 mb-3 flex items-center justify-between">
                    <span>Datos del Socio Titular (Contribuyente / Beneficiario)</span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        {{ ($pago->socio->saldo ?? 0) >= 0 ? 'ESTADO: SOLVENTE' : 'ESTADO: PENDIENTE' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-xs">
                    <div>
                        <span class="text-slate-500 text-[11px] block">Nombre Completo:</span>
                        <strong class="text-sm font-bold text-slate-900">{{ $pago->socio->nombre_completo ?? 'Socio Titular' }}</strong>
                    </div>

                    <div>
                        <span class="text-slate-500 text-[11px] block">Cédula de Identidad:</span>
                        <strong class="font-mono font-bold text-slate-900 text-sm">V-{{ $pago->socio->cedula ?? '' }}</strong>
                    </div>

                    <div>
                        <span class="text-slate-500 text-[11px] block">Número de Acción / Membresía:</span>
                        <strong class="font-mono font-bold text-amber-800 text-sm">Acción #{{ $pago->socio->accion ?? '' }}</strong>
                    </div>

                    <div>
                        <span class="text-slate-500 text-[11px] block">Correo Electrónico:</span>
                        <span class="text-slate-800 font-medium">{{ $pago->socio->correo ?? 'No registrado' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-500 text-[11px] block">Teléfono de Contacto:</span>
                        <span class="text-slate-800 font-medium">{{ $pago->socio->numero ?? 'No registrado' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-500 text-[11px] block">Dirección de Domicilio:</span>
                        <span class="text-slate-800 font-medium truncate block">{{ $pago->socio->direccion ?? 'Caracas, Venezuela' }}</span>
                    </div>
                </div>
            </div>

            <!-- 3. REFERENCIA CAMBIARIA OFICIAL BCV -->
            <div class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-amber-50 border border-amber-200 text-xs">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('img/Banco_Central_de_Venezuela_logo.svg') }}" alt="BCV" class="h-5 w-auto">
                    <span class="font-semibold text-amber-900">
                        Convenio Cambiario N° 1 • Tasa Oficial Banco Central de Venezuela:
                    </span>
                    <strong class="font-mono text-amber-950 font-extrabold text-sm">
                        {{ number_format($tasa, 2, ',', '.') }} Bs / USD
                    </strong>
                </div>
                <span class="text-[10px] text-amber-800 font-medium hidden sm:inline">
                    Liquidación oficial según normativa cambiaria vigente
                </span>
            </div>

            <!-- 4. TABLA DETALLADA DE CONCEPTOS Y RUBROS FACTURADOS -->
            <div class="overflow-hidden rounded-xl border border-slate-300">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-800 text-white font-bold uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-center w-12">#</th>
                            <th class="px-4 py-3">Descripción del Concepto / Servicio</th>
                            <th class="px-4 py-3 text-center">Período</th>
                            <th class="px-4 py-3 text-right">Monto ($ USD)</th>
                            <th class="px-4 py-3 text-right">Tasa Oficial</th>
                            <th class="px-4 py-3 text-right">Total en Bolívares (Bs.)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-4 text-center font-mono font-bold text-slate-500">01</td>
                            <td class="px-4 py-4">
                                <strong class="text-sm text-slate-900 block">{{ $pago->descripcion }}</strong>
                                <span class="text-[11px] text-slate-500">Mantenimiento de áreas sociales, campos deportivos y servicios de membresía titular</span>
                            </td>
                            <td class="px-4 py-4 text-center font-medium text-slate-700">
                                {{ $pago->fecha_pago ? $pago->fecha_pago->format('m/Y') : now()->format('m/Y') }}
                            </td>
                            <td class="px-4 py-4 text-right font-mono font-bold text-slate-900 text-sm">
                                ${{ number_format($pago->monto, 2) }}
                            </td>
                            <td class="px-4 py-4 text-right font-mono text-slate-600">
                                {{ number_format($tasa, 2, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right font-mono font-extrabold text-slate-900 text-sm">
                                Bs. {{ number_format($montoBs, 2, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- 5. BLOQUE DE LIQUIDACIÓN, TOTALES Y SELLO DE COBRO -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 pt-2">
                <!-- Columna Izquierda: Sello Digital y QR de Verificación (7 cols) -->
                <div class="md:col-span-7 flex flex-col justify-between space-y-4">
                    <div class="flex items-center space-x-6">
                        <!-- Código QR Oficial Fiscal / Verificación -->
                        <div class="p-2.5 rounded-xl border border-slate-300 bg-slate-50 flex flex-col items-center justify-center flex-shrink-0">
                            <div id="qrcodeFactura" class="flex items-center justify-center"></div>
                            <span class="text-[8px] font-mono text-slate-600 font-bold uppercase tracking-wider mt-1">
                                Verificación Fiscal
                            </span>
                        </div>

                        <!-- Sello Digital Oficial de Cancelado -->
                        <div class="border-2 border-emerald-700 rounded-xl p-3 bg-emerald-50/60 text-center flex-1 max-w-[260px]">
                            <div class="text-[10px] font-black text-emerald-900 uppercase tracking-widest">★ COUNTRY CLUB C.A. ★</div>
                            <div class="text-xl font-black text-emerald-800 tracking-wider my-0.5">CANCELADO</div>
                            <div class="text-[9px] font-bold text-emerald-700 uppercase tracking-wider">Caja Principal • Recaudación</div>
                            <div class="text-[10px] font-mono font-semibold text-emerald-900 mt-1">
                                Fecha: {{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y') : date('d/m/Y') }}
                            </div>
                        </div>
                    </div>

                    <!-- Estado Contable del Socio -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600 font-medium">Estado de Cuenta Post-Operación:</span>
                            <span class="font-mono font-extrabold text-sm {{ ($pago->socio->saldo ?? 0) < 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                                Saldo: ${{ number_format($pago->socio->saldo ?? 0, 2) }} USD
                            </span>
                        </div>
                        <span class="text-[10px] text-slate-500 block mt-0.5">
                            Forma de Pago: <strong>Caja / Divisas en Efectivo o Transferencia Conciliada</strong>
                        </span>
                    </div>
                </div>

                <!-- Columna Derecha: Cuadro de Totales Oficial (5 cols) -->
                <div class="md:col-span-5 bg-slate-50 rounded-xl p-5 border-2 border-slate-800 space-y-2 text-xs">
                    <div class="flex justify-between items-center text-slate-600">
                        <span>Subtotal Divisas:</span>
                        <span class="font-mono font-semibold">${{ number_format($pago->monto, 2) }} USD</span>
                    </div>

                    <div class="flex justify-between items-center text-slate-600 pb-2 border-b border-slate-200">
                        <span>Exento de I.V.A. (Art. 18):</span>
                        <span class="font-mono font-semibold">$0.00 USD</span>
                    </div>

                    <div class="flex justify-between items-baseline pt-1">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-800">Total Liquidado:</span>
                        <span class="text-2xl font-mono font-black text-slate-950">${{ number_format($pago->monto, 2) }} USD</span>
                    </div>

                    <div class="pt-2 border-t border-slate-300">
                        <div class="flex justify-between items-center text-[11px] text-slate-600 mb-0.5">
                            <span>Tasa Oficial BCV:</span>
                            <span class="font-mono font-bold">{{ number_format($tasa, 2, ',', '.') }} Bs.</span>
                        </div>
                        <div class="flex justify-between items-baseline bg-amber-100/70 p-2.5 rounded-lg border border-amber-300">
                            <span class="text-xs font-black uppercase text-amber-950">Total en Bolívares:</span>
                            <span class="text-lg font-mono font-black text-amber-950">
                                Bs. {{ number_format($montoBs, 2, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 6. BLOQUE DE FIRMAS AUTORIZADAS -->
            <div class="pt-8 grid grid-cols-2 gap-12 text-center text-xs border-t-2 border-slate-200 mt-4">
                <div class="space-y-1">
                    <div class="border-b-2 border-dashed border-slate-400 pb-12 w-52 mx-auto"></div>
                    <p class="font-bold text-slate-900 text-xs">Cajero / Recaudador Autorizado</p>
                    <p class="text-[10px] text-slate-500 uppercase tracking-wider">Firma y Sello de Caja</p>
                </div>

                <div class="space-y-1">
                    <div class="border-b-2 border-dashed border-slate-400 pb-12 w-52 mx-auto"></div>
                    <p class="font-bold text-slate-900 text-xs">{{ $pago->socio->nombre_completo ?? 'Socio Titular' }}</p>
                    <p class="text-[10px] text-slate-500 uppercase tracking-wider">Firma Conforme del Titular</p>
                </div>
            </div>

            <!-- 7. PIE LEGAL INSTITUCIONAL & HASH FISCAL -->
            <div class="pt-4 border-t border-slate-200 text-center text-[10px] text-slate-500 space-y-1">
                <p>
                    Comprobante emitido de conformidad con la normativa cambiaria vigente del Banco Central de Venezuela (Convenio Cambiario N° 1).
                </p>
                <p>
                    Documento original válido como constancia formal de pago y solvencia de membresía ante la Dirección del Club y Garita de Seguridad.
                </p>
                <p class="font-mono text-[9px] text-slate-400 pt-1">
                    Firma Digital: {{ strtoupper(substr(hash('sha256', $pago->id . '-' . $pago->monto . '-' . $pago->fecha_pago . '-' . $pago->socios_id), 0, 32)) }}
                </p>
            </div>

        </div>
    </div>

</div>

@push('styles')
<style>
    /* Estilos de Impresión Oficial y Limpia */
    @media print {
        @page {
            size: letter portrait;
            margin: 10mm 12mm;
        }

        /* Ocultar completamente la interfaz del sistema */
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
            border: 1px solid #94A3B8 !important;
            box-shadow: none !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #FFFFFF !important;
            color: #000000 !important;
            page-break-inside: avoid;
        }

        /* Garantizar colores de impresión fieles */
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
                width: 75,
                height: 75,
                colorDark: "#0F172A",
                colorLight: "#FFFFFF",
                correctLevel: QRCode.CorrectLevel.M
            });
        }
    });
</script>
@endpush
@endsection
