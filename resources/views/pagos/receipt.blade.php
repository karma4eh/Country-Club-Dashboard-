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
             . "🔗 *Ver y Descargar Factura Digital:*\n"
             . route('pagos.receipt', $pago) . "\n\n"
             . "_Dirección de Administración y Finanzas • Country Club C.A._";

    $wsUrl = !empty($phone) 
        ? "https://wa.me/{$phone}?text=" . rawurlencode($textoWs) 
        : "https://wa.me/?text=" . rawurlencode($textoWs);
@endphp

<div class="max-w-4xl mx-auto space-y-3 pb-6">

    <!-- AVISO DINÁMICO DE PAGO SATISFACTORIO CON SALDO ACTUAL Y WHATSAPP -->
    @if(session('pago_exitoso'))
    <div id="pagoSuccessCard" class="bg-gradient-to-r from-[#064E3B] via-[#065F46] to-[#0A261E] border border-emerald-400/40 rounded-2xl p-4 sm:p-5 shadow-2xl backdrop-blur-xl relative overflow-hidden transition-all duration-300 no-print animate-fade-in">
        <!-- Glow decorativo de fondo -->
        <div class="absolute -right-8 -bottom-8 w-36 h-36 bg-emerald-400/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
            <div class="flex items-start sm:items-center space-x-3.5">
                <!-- Icono animado de confirmación -->
                <div class="w-11 h-11 rounded-2xl bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center flex-shrink-0 text-emerald-300 shadow-lg shadow-emerald-500/20">
                    <span class="material-icons-round text-2xl">verified</span>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h3 class="text-base sm:text-lg font-black text-white tracking-tight flex items-center">
                            ¡Pago Hecho Satisfactoriamente!
                        </h3>
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-400 text-slate-950">
                            Acreditado
                        </span>
                    </div>
                    <p class="text-xs text-emerald-200/90 mt-0.5">
                        Comprobante <strong class="font-mono text-white">#REC-{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}</strong> emitido para <strong class="text-white">{{ $pago->socio->nombre_completo }}</strong> (Acción #{{ $pago->socio->accion }}).
                    </p>
                </div>
            </div>

            <!-- Recuadro Dinámico del Saldo del Socio -->
            <div class="flex items-center space-x-3">
                <div class="bg-[#042F24]/80 border border-emerald-400/40 rounded-xl px-4 py-2 text-right">
                    <span class="block text-[9px] font-bold text-emerald-300 uppercase tracking-widest">Saldo Actual del Socio</span>
                    <div class="flex items-baseline space-x-1.5 justify-end">
                        <span class="text-lg font-mono font-black text-emerald-300">
                            ${{ number_format($pago->socio->saldo ?? 0, 2) }} <span class="text-xs">USD</span>
                        </span>
                        <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-emerald-500/30 text-emerald-200">
                            {{ ($pago->socio->saldo ?? 0) >= 0 ? 'Solvente' : 'Pendiente' }}
                        </span>
                    </div>
                </div>

                <!-- Botón Cerrar Aviso -->
                <button type="button" onclick="document.getElementById('pagoSuccessCard').remove()" class="text-emerald-300/70 hover:text-white p-1 rounded-lg hover:bg-emerald-500/20 transition" title="Cerrar">
                    <span class="material-icons-round text-lg">close</span>
                </button>
            </div>
        </div>

        <!-- Fila de Estado de Notificaciones: Correo & Botón a WhatsApp -->
        <div class="mt-3.5 pt-3 border-t border-emerald-500/30 flex flex-wrap items-center justify-between gap-3 text-xs">
            <div class="flex items-center space-x-2 text-emerald-200/90">
                @if(session('pago_exitoso.correo_enviado'))
                    <span class="inline-flex items-center space-x-1 text-emerald-300 bg-emerald-500/20 px-2.5 py-1 rounded-lg border border-emerald-400/30 font-semibold">
                        <span class="material-icons-round text-sm text-emerald-400">mark_email_read</span>
                        <span>Factura enviada automáticamente a: <strong>{{ $pago->socio->correo }}</strong></span>
                    </span>
                @elseif(!empty($pago->socio->correo))
                    <span class="inline-flex items-center space-x-1 text-emerald-300/90 bg-emerald-500/10 px-2 py-1 rounded-lg">
                        <span class="material-icons-round text-sm">mail</span>
                        <span>Correo del titular: {{ $pago->socio->correo }}</span>
                    </span>
                @else
                    <span class="text-emerald-200/80 text-[11px] italic">
                        * Socio sin correo registrado. Puedes enviarle el comprobante oficial directamente a su WhatsApp.
                    </span>
                @endif
            </div>

            <!-- Botón WhatsApp Destacado -->
            <a href="{{ $wsUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-xl bg-[#25D366] hover:bg-[#20BD5A] text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-[#25D366]/20 transition transform hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.423-14.416c-6.627 0-12 5.373-12 12 0 2.155.57 4.178 1.564 5.927l-1.658 6.063 6.22-1.632c1.679.914 3.601 1.43 5.64 1.43 6.627 0 12-5.373 12-12s-5.373-12-12-12z"/>
                </svg>
                <span>Enviar Factura a WhatsApp</span>
            </a>
        </div>
    </div>
    @endif

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
            <!-- Botón WhatsApp permanente en barra superior -->
            <a href="{{ $wsUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl bg-[#25D366] hover:bg-[#20BD5A] text-slate-950 font-bold text-xs uppercase tracking-wider shadow-md shadow-[#25D366]/20 transition active:scale-[0.98]">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.423-14.416c-6.627 0-12 5.373-12 12 0 2.155.57 4.178 1.564 5.927l-1.658 6.063 6.22-1.632c1.679.914 3.601 1.43 5.64 1.43 6.627 0 12-5.373 12-12s-5.373-12-12-12z"/>
                </svg>
                <span>WhatsApp</span>
            </a>

            <!-- Botón de Impresión de Factura -->
            <button type="button" onclick="window.print()" class="inline-flex items-center space-x-1.5 px-4 py-1.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-amber-500/20 transition active:scale-[0.98]">
                <span class="material-icons-round text-base">print</span>
                <span>Imprimir Factura</span>
            </button>
        </div>
    </div>

    <!-- DOCUMENTO FORMAL: FACTURA / RECIBO OFICIAL EN PALETA OSCURA INSTITUCIONAL -->
    <div id="factura-imprimible" class="bg-[#0F172A] text-slate-100 rounded-2xl shadow-2xl border border-amber-500/30 overflow-hidden relative print:shadow-none print:m-0 print:rounded-none">

        <!-- Banda decorativa institucional superior -->
        <div class="h-1.5 bg-gradient-to-r from-[#1E293B] via-[#F59E0B] to-[#10B981]"></div>

        <div class="p-5 sm:p-6 space-y-3.5 relative z-10">

            <!-- 1. ENCABEZADO INSTITUCIONAL & METADATOS FISCALES -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-3.5 border-b border-[#1F293D]">
                <!-- Logotipo e Identidad Institucional -->
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('img/logo.png') }}" alt="Country Club" class="h-12 w-auto object-contain filter drop-shadow-md">
                    <div class="border-l border-[#1F293D] pl-3.5">
                        <h1 class="text-lg sm:text-xl font-extrabold tracking-tight text-white font-serif leading-tight">COUNTRY CLUB C.A.</h1>
                        <p class="text-[10px] font-bold text-amber-400 uppercase tracking-widest">Dirección de Administración y Finanzas</p>
                        <p class="text-[9px] text-slate-400 mt-0.5">R.I.F.: J-00028491-3 • NIT: 0142859201</p>
                        <p class="text-[8px] text-slate-400">Av. Principal del Country Club, Quinta Club House, Caracas, Venezuela • Tel: (0212) 261-0000</p>
                    </div>
                </div>

                <!-- Recuadro Fiscal de Emisión -->
                <div class="border border-amber-500/30 rounded-xl p-2.5 bg-[#0A0F1E] text-right min-w-[200px] shadow-sm">
                    <span class="inline-block px-2 py-0.5 rounded text-[8px] font-extrabold uppercase tracking-widest bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 mb-0.5">
                        COMPROBANTE OFICIAL DE INGRESO
                    </span>
                    <div class="text-[9px] font-bold text-slate-400 uppercase">Factura / Recibo N°</div>
                    <div class="text-lg font-mono font-extrabold text-amber-400 tracking-wider leading-tight">
                        REC-{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="text-[8px] font-mono text-slate-400">
                        Control: 00-{{ str_pad($pago->id * 11 + 1042, 6, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="text-[9px] text-slate-300 font-medium mt-0.5">
                        Emisión: <strong>{{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y') : now()->format('d/m/Y') }}</strong> • <strong>{{ $pago->fecha_pago ? $pago->fecha_pago->format('h:i A') : now()->format('h:i A') }}</strong>
                    </div>
                </div>
            </div>

            <!-- 2. DATOS DEL CLIENTE / SOCIO TITULAR -->
            <div class="bg-[#0A0F1E] rounded-xl p-3 border border-[#1F293D]">
                <div class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400 mb-2 flex items-center justify-between">
                    <span>Datos del Socio Titular (Contribuyente / Beneficiario)</span>
                    <span class="px-2 py-0.5 rounded-full text-[8px] font-bold {{ ($pago->socio->saldo ?? 0) >= 0 ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/15 text-rose-400 border border-rose-500/30' }}">
                        {{ ($pago->socio->saldo ?? 0) >= 0 ? 'ESTADO: SOLVENTE' : 'ESTADO: PENDIENTE' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                    <div>
                        <span class="text-slate-400 text-[10px] block">Nombre Completo:</span>
                        <strong class="text-xs font-bold text-white">{{ $pago->socio->nombre_completo ?? 'Socio Titular' }}</strong>
                    </div>

                    <div>
                        <span class="text-slate-400 text-[10px] block">Cédula de Identidad:</span>
                        <strong class="font-mono font-bold text-slate-200 text-xs">V-{{ $pago->socio->cedula ?? '' }}</strong>
                    </div>

                    <div>
                        <span class="text-slate-400 text-[10px] block">Acción / Membresía:</span>
                        <strong class="font-mono font-bold text-amber-300 text-xs">Acción #{{ $pago->socio->accion ?? '' }}</strong>
                    </div>

                    <div>
                        <span class="text-slate-400 text-[10px] block">Contacto:</span>
                        <span class="text-slate-300 text-[11px] truncate block">{{ $pago->socio->correo ?? $pago->socio->numero ?? 'Registrado' }}</span>
                    </div>
                </div>
            </div>

            <!-- 3. REFERENCIA CAMBIARIA OFICIAL BCV -->
            <div class="flex items-center justify-between px-3 py-1.5 rounded-xl bg-amber-500/10 border border-amber-500/25 text-xs">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('img/Banco_Central_de_Venezuela_logo.svg') }}" alt="BCV" class="h-3.5 w-auto">
                    <span class="font-semibold text-amber-200 text-[11px]">
                        Convenio Cambiario N° 1 • Tasa Oficial BCV:
                    </span>
                    <strong class="font-mono text-amber-400 font-extrabold text-xs">
                        {{ number_format($tasa, 2, ',', '.') }} Bs / USD
                    </strong>
                </div>
                <span class="text-[8px] text-amber-300/80 font-medium hidden sm:inline font-mono">
                    Referencia Cambiaria Oficial
                </span>
            </div>

            <!-- 4. TABLA DETALLADA DE CONCEPTOS FACTURADOS -->
            <div class="overflow-hidden rounded-xl border border-[#1F293D]">
                <table class="w-full text-xs text-left">
                    <thead class="bg-[#111C33] text-slate-300 font-bold uppercase text-[9px] tracking-wider">
                        <tr>
                            <th class="px-3.5 py-2 text-center w-10">#</th>
                            <th class="px-3.5 py-2">Descripción del Concepto / Servicio</th>
                            <th class="px-3.5 py-2 text-center">Período</th>
                            <th class="px-3.5 py-2 text-right">Monto ($ USD)</th>
                            <th class="px-3.5 py-2 text-right">Tasa BCV</th>
                            <th class="px-3.5 py-2 text-right">Total en Bolívares (Bs.)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1F293D] bg-[#0A0F1E]">
                        <tr>
                            <td class="px-3.5 py-2.5 text-center font-mono font-bold text-slate-500">01</td>
                            <td class="px-3.5 py-2.5">
                                <strong class="text-xs text-white block">{{ $pago->descripcion }}</strong>
                                <span class="text-[10px] text-slate-400">Cuota ordinaria de membresía • Acceso integral a instalaciones</span>
                            </td>
                            <td class="px-3.5 py-2.5 text-center font-medium text-slate-300 text-[11px]">
                                {{ $pago->fecha_pago ? $pago->fecha_pago->format('m/Y') : now()->format('m/Y') }}
                            </td>
                            <td class="px-3.5 py-2.5 text-right font-mono font-bold text-emerald-400 text-xs">
                                ${{ number_format($pago->monto, 2) }}
                            </td>
                            <td class="px-3.5 py-2.5 text-right font-mono text-slate-400 text-[11px]">
                                {{ number_format($tasa, 2, ',', '.') }}
                            </td>
                            <td class="px-3.5 py-2.5 text-right font-mono font-extrabold text-amber-300 text-xs">
                                Bs. {{ number_format($montoBs, 2, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- 5. BLOQUE DE LIQUIDACIÓN, TOTALES Y SELLO DE TINTA DE CAJA -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 pt-1 items-center">
                <!-- Columna Izquierda: QR de Verificación y Sello de Tinta Realista (7 cols) -->
                <div class="md:col-span-7 flex flex-row items-center justify-start space-x-3 sm:space-x-4">
                    <!-- Código QR Oficial Fiscal / Verificación -->
                    <div class="p-2 rounded-xl border border-amber-500/25 bg-[#060911] flex flex-col items-center justify-center flex-shrink-0 shadow-inner">
                        <div id="qrcodeFactura" class="flex items-center justify-center p-0.5 bg-[#060911]"></div>
                        <span class="text-[7px] font-mono text-amber-300 font-bold uppercase tracking-wider mt-0.5">
                            QR Fiscal
                        </span>
                    </div>

                    <!-- SELLO DE TINTA DE CAUCHO REALISTA ("CANCELADO") -->
                    <div class="stamp-ink-container select-none">
                        <svg viewBox="0 0 280 120" class="w-56 sm:w-64 h-auto filter drop-shadow(0 2px 6px rgba(0,0,0,0.45)) transition-transform duration-300 hover:rotate-[-6deg]" style="transform: rotate(-8deg); transform-origin: center center;" aria-label="Sello de Tinta Cancelado">
                            <defs>
                                <!-- Filtro de Tinta Real: Simula la absorción de tinta líquida en papel y bordes de caucho imperfectos -->
                                <filter id="stamp-real-ink" x="-20%" y="-20%" width="140%" height="140%">
                                    <feTurbulence type="fractalNoise" baseFrequency="0.09" numOctaves="4" result="paperFiber" />
                                    <feDisplacementMap in="SourceGraphic" in2="paperFiber" scale="2.8" xChannelSelector="R" yChannelSelector="G" result="roughInk" />
                                    <feGaussianBlur in="roughInk" stdDeviation="0.3" result="bleed" />
                                    <feMerge>
                                        <feMergeNode in="bleed" opacity="0.6" />
                                        <feMergeNode in="roughInk" />
                                    </feMerge>
                                </filter>

                                <!-- Máscara de Desgaste de Almohadilla: Puntos de menor entintado y presión manual no uniforme -->
                                <mask id="stamp-wear-mask">
                                    <rect x="0" y="0" width="280" height="120" fill="#FFFFFF" />
                                    <!-- Micro-poros de absorción irregular -->
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

                            <!-- Grupo Estampado con Tinta y Filtro Orgánico -->
                            <g filter="url(#stamp-real-ink)" mask="url(#stamp-wear-mask)" fill="#DC2626" stroke="#DC2626">
                                <!-- Base tenue de tinta absorbida por la almohadilla -->
                                <rect x="4" y="4" width="272" height="112" rx="8" ry="8" fill="rgba(220, 38, 38, 0.06)" stroke="none" />

                                <!-- Borde exterior de caucho grueso -->
                                <rect x="4" y="4" width="272" height="112" rx="8" ry="8" fill="none" stroke="#DC2626" stroke-width="3.2" stroke-linecap="round" />

                                <!-- Borde interior fino con holgura -->
                                <rect x="10" y="10" width="260" height="100" rx="5" ry="5" fill="none" stroke="#DC2626" stroke-width="1.4" opacity="0.9" />

                                <!-- 1. COUNTRY CLUB C.A. (Sin estrellas) -->
                                <text x="140" y="28" text-anchor="middle" font-family="'Impact', 'Arial Black', sans-serif" font-weight="900" font-size="12" letter-spacing="3" fill="#DC2626" stroke="none">COUNTRY CLUB C.A.</text>

                                <!-- Separador superior doble de sello -->
                                <line x1="18" y1="35" x2="262" y2="35" stroke="#DC2626" stroke-width="1.8" />
                                <line x1="22" y1="38" x2="258" y2="38" stroke="#DC2626" stroke-width="0.8" opacity="0.7" />

                                <!-- 2. CANCELADO (Imprenta condensada clásica de sello contable) -->
                                <text x="140" y="67" text-anchor="middle" font-family="'Impact', 'Arial Black', sans-serif" font-weight="900" font-size="28" letter-spacing="4" fill="#DC2626" stroke="none">CANCELADO</text>

                                <!-- Separador inferior doble de sello -->
                                <line x1="22" y1="74" x2="258" y2="74" stroke="#DC2626" stroke-width="0.8" opacity="0.7" />
                                <line x1="18" y1="77" x2="262" y2="77" stroke="#DC2626" stroke-width="1.8" />

                                <!-- 3. CAJA PRINCIPAL • RECAUDACIÓN -->
                                <text x="140" y="91" text-anchor="middle" font-family="'Arial Black', sans-serif" font-weight="800" font-size="9" letter-spacing="1.8" fill="#DC2626" stroke="none">CAJA PRINCIPAL • RECAUDACIÓN</text>

                                <!-- 4. Fecha de Pago -->
                                <text x="140" y="105" text-anchor="middle" font-family="'Courier New', monospace" font-weight="bold" font-size="11.5" letter-spacing="2.5" fill="#EF4444" stroke="none">{{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y') : date('d/m/Y') }}</text>
                            </g>
                        </svg>
                    </div>
                </div>

                <!-- Columna Derecha: Cuadro de Totales Oficial (5 cols) -->
                <div class="md:col-span-5 bg-[#0A0F1E] rounded-xl p-3 border border-amber-500/30 space-y-1 text-xs">
                    <div class="flex justify-between items-center text-slate-400 text-[10px]">
                        <span>Subtotal Divisas:</span>
                        <span class="font-mono font-semibold text-slate-200">${{ number_format($pago->monto, 2) }} USD</span>
                    </div>

                    <div class="flex justify-between items-center text-slate-400 text-[10px] pb-1 border-b border-[#1F293D]">
                        <span>Exento de I.V.A.:</span>
                        <span class="font-mono font-semibold text-slate-200">$0.00 USD</span>
                    </div>

                    <div class="flex justify-between items-baseline pt-0.5">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-300">Total Liquidado:</span>
                        <span class="text-lg font-mono font-black text-emerald-400">${{ number_format($pago->monto, 2) }} USD</span>
                    </div>

                    <div class="pt-1 border-t border-[#1F293D]">
                        <div class="flex justify-between items-baseline bg-amber-500/15 p-1.5 rounded-lg border border-amber-500/30">
                            <span class="text-[9px] font-black uppercase text-amber-300">Total en Bolívares:</span>
                            <span class="text-sm font-mono font-black text-amber-200">
                                Bs. {{ number_format($montoBs, 2, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 6. BLOQUE DE FIRMAS AUTORIZADAS -->
            <div class="pt-2 grid grid-cols-2 gap-8 text-center text-xs border-t border-[#1F293D] mt-1">
                <div class="space-y-0.5 relative">
                    <div class="h-7 flex items-end justify-center relative">
                        <!-- Trazo caligráfico de firma de recaudador -->
                        <svg class="w-28 h-7 text-amber-400/70 -mb-1" viewBox="0 0 160 40" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M 12 28 Q 35 4, 52 22 T 85 18 Q 110 8, 126 30 T 145 20" />
                            <path d="M 38 32 Q 65 24, 110 26" stroke-width="1.2" opacity="0.6" />
                        </svg>
                    </div>
                    <div class="border-b border-dashed border-slate-600 w-44 mx-auto"></div>
                    <p class="font-bold text-slate-200 text-[11px] mt-0.5">Cajero / Recaudador Autorizado</p>
                    <p class="text-[8px] text-slate-400 uppercase tracking-wider">Firma y Sello de Caja</p>
                </div>

                <div class="space-y-0.5">
                    <div class="h-7"></div>
                    <div class="border-b border-dashed border-slate-600 w-44 mx-auto"></div>
                    <p class="font-bold text-slate-200 text-[11px] mt-0.5">{{ $pago->socio->nombre_completo ?? 'Socio Titular' }}</p>
                    <p class="text-[8px] text-slate-400 uppercase tracking-wider">Firma Conforme del Titular</p>
                </div>
            </div>

            <!-- 7. PIE LEGAL INSTITUCIONAL & HASH FISCAL -->
            <div class="pt-1.5 border-t border-[#1F293D] text-center text-[8px] text-slate-400 space-y-0.5">
                <p>
                    Comprobante oficial emitido de conformidad con la normativa cambiaria vigente del Banco Central de Venezuela (Convenio Cambiario N° 1).
                </p>
                <p class="font-mono text-[7px] text-slate-500">
                    Firma Digital: {{ strtoupper(substr(hash('sha256', $pago->id . '-' . $pago->monto . '-' . $pago->fecha_pago . '-' . $pago->socios_id), 0, 32)) }}
                </p>
            </div>

        </div>
    </div>

</div>

@push('styles')
<style>
    /* Estilos de Impresión Fidedigna */
    @media print {
        @page {
            size: letter portrait;
            margin: 8mm 10mm;
        }

        body {
            background: #0F172A !important;
            color: #FFFFFF !important;
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
            border: 1px solid #F59E0B !important;
            box-shadow: none !important;
            margin: 0 !important;
            background: #0F172A !important;
            color: #FFFFFF !important;
            page-break-inside: avoid;
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
                width: 62,
                height: 62,
                colorDark: "#FFFFFF",
                colorLight: "#060911",
                correctLevel: QRCode.CorrectLevel.M
            });
        }
    });
</script>
@endpush
@endsection
