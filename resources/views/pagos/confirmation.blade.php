@extends('layouts.app')

@section('title', 'Pago Satisfactoriamente - Recibo #REC-' . str_pad($pago->id, 6, '0', STR_PAD_LEFT))
@section('header_title', 'Confirmación de Transacción')
@section('header_subtitle', 'Caja Principal • Recaudación y Finanzas')

@section('content')
@php
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

<div class="max-w-xl mx-auto py-4 sm:py-6 space-y-6">

    <!-- TARJETA PRINCIPAL DE CONFIRMACIÓN -->
    <div class="bg-gradient-to-b from-[#111827] to-[#0A0F1E] border border-emerald-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden text-center backdrop-blur-xl">

        <!-- Ambient Glow de éxito -->
        <div class="absolute -top-16 left-1/2 -translate-x-1/2 w-64 h-64 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <!-- 1. CHULO DINÁMICO CON ANIMACIÓN SUAVE Y MODERNA -->
        <div class="flex justify-center mb-5 relative z-10">
            <div class="chulo-animado-wrapper">
                <svg class="chulo-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="chulo-circle" cx="26" cy="26" r="23" fill="none"/>
                    <path class="chulo-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
            </div>
        </div>

        <!-- 2. TÍTULO Y DETALLE -->
        <div class="relative z-10 space-y-1.5">
            <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                Pago satisfactoriamente
            </h2>
            <p class="text-xs sm:text-sm text-slate-400">
                Transacción registrada y acreditada con el comprobante
                <span class="font-mono font-bold text-amber-400">#REC-{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}</span>
            </p>
        </div>

        <!-- 3. CUADRO DE SALDO DEL SOCIO & RESUMEN DE COBRO -->
        <div class="mt-6 relative z-10 bg-[#060911]/90 rounded-2xl p-5 border border-[#1F293D] text-left space-y-4 shadow-inner">
            <!-- Datos del Socio -->
            <div class="flex items-center justify-between border-b border-[#1F293D] pb-3">
                <div>
                    <span class="text-[10px] uppercase tracking-wider font-bold text-slate-500 block">Socio Titular</span>
                    <strong class="text-sm font-bold text-white">{{ $pago->socio->nombre_completo ?? 'Socio' }}</strong>
                </div>
                <div class="text-right">
                    <span class="text-[10px] uppercase tracking-wider font-bold text-slate-500 block">Acción / Membresía</span>
                    <strong class="font-mono text-amber-300 text-sm font-bold">#{{ $pago->socio->accion ?? '-' }}</strong>
                </div>
            </div>

            <!-- SALDO ACTUAL DEL SOCIO (PROTAGONISTA) -->
            <div class="p-4 rounded-xl bg-gradient-to-r from-emerald-500/15 via-emerald-500/10 to-transparent border border-emerald-500/30 flex items-center justify-between">
                <div>
                    <span class="text-[10px] uppercase font-bold tracking-widest text-emerald-300 block">
                        Saldo Actual del Socio
                    </span>
                    <div class="text-2xl font-mono font-black text-emerald-400 mt-0.5">
                        ${{ number_format($pago->socio->saldo ?? 0, 2) }} <span class="text-xs font-semibold text-emerald-300">USD</span>
                    </div>
                </div>
                <div>
                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider {{ ($pago->socio->saldo ?? 0) >= 0 ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-rose-500/20 text-rose-300 border border-rose-500/40' }}">
                        {{ ($pago->socio->saldo ?? 0) >= 0 ? '✓ Solvente' : 'Pendiente' }}
                    </span>
                </div>
            </div>

            <!-- Fila con Monto Acreditado y Equivalencia BCV -->
            <div class="grid grid-cols-2 gap-3 text-xs pt-1">
                <div>
                    <span class="text-slate-400 text-[11px] block">Monto Acreditado:</span>
                    <strong class="font-mono text-white text-sm font-bold">${{ number_format($pago->monto, 2) }} USD</strong>
                </div>
                <div class="text-right">
                    <span class="text-slate-400 text-[11px] block">Total en Bolívares (BCV):</span>
                    <strong class="font-mono text-amber-300 text-sm font-bold">Bs. {{ number_format($montoBs, 2, ',', '.') }}</strong>
                </div>
            </div>

            <!-- Estado de Correo Electrónico Automático -->
            @if(!empty($pago->socio->correo))
            <div class="pt-2 border-t border-[#1F293D] flex items-center space-x-2 text-xs text-slate-400">
                <span class="material-icons-round text-emerald-400 text-sm">mark_email_read</span>
                <span>Factura enviada automáticamente a <strong class="text-slate-200">{{ $pago->socio->correo }}</strong></span>
            </div>
            @endif
        </div>

        <!-- 4. BOTONES DE ACCIÓN: WHATSAPP, IMPRIMIR Y VER FACTURA -->
        <div class="mt-6 relative z-10 space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- BOTÓN ENVIAR POR WHATSAPP -->
                <a href="{{ $wsUrl }}" target="_blank" rel="noopener noreferrer" class="py-3 px-4 rounded-xl bg-[#25D366] hover:bg-[#20BD5A] text-slate-950 font-black text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-lg shadow-[#25D366]/20 transition transform hover:-translate-y-0.5 active:scale-[0.98]">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.423-14.416c-6.627 0-12 5.373-12 12 0 2.155.57 4.178 1.564 5.927l-1.658 6.063 6.22-1.632c1.679.914 3.601 1.43 5.64 1.43 6.627 0 12-5.373 12-12s-5.373-12-12-12z"/>
                    </svg>
                    <span>Enviar a WhatsApp</span>
                </a>

                <!-- BOTÓN IMPRIMIR FACTURA (DIRECTO) -->
                <button type="button" onclick="imprimirFacturaDirecta()" class="py-3 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-lg shadow-amber-500/20 transition transform hover:-translate-y-0.5 active:scale-[0.98]">
                    <span class="material-icons-round text-base">print</span>
                    <span>Imprimir Factura</span>
                </button>
            </div>

            <!-- ENLACES SECUNDARIOS: VER FACTURA Y NUEVO COBRO -->
            <div class="flex items-center justify-between pt-2 text-xs">
                <a href="{{ route('pagos.create') }}" class="inline-flex items-center space-x-1 text-slate-400 hover:text-white transition">
                    <span class="material-icons-round text-sm">add_circle_outline</span>
                    <span>Realizar otro cobro</span>
                </a>

                <a href="{{ route('pagos.receipt', $pago) }}" class="inline-flex items-center space-x-1 text-amber-400 hover:text-amber-300 font-semibold transition">
                    <span class="material-icons-round text-sm">receipt_long</span>
                    <span>Ver Factura Oficial</span>
                    <span class="material-icons-round text-sm">chevron_right</span>
                </a>
            </div>
        </div>

    </div>

</div>

<!-- IFRAME OCULTO PARA IMPRESIÓN DIRECTA SIN SALIR DE LA PANTALLA -->
<iframe id="receiptFrame" src="{{ route('pagos.receipt', $pago) }}" class="hidden" style="position: absolute; width:0; height:0; border:0;"></iframe>

@push('styles')
<style>
    /* ==========================================================
       ANIMACIÓN DEL "CHULO" DINÁMICO (CHECKMARK FINTECH ANIMATION)
       ========================================================== */
    .chulo-animado-wrapper {
        display: inline-block;
        padding: 4px;
    }

    .chulo-svg {
        width: 82px;
        height: 82px;
        border-radius: 50%;
        display: block;
        stroke-width: 3.5;
        stroke: #10B981;
        stroke-miterlimit: 10;
        box-shadow: inset 0px 0px 0px #10B981;
        animation: fill-chulo 0.4s ease-in-out 0.4s forwards, scale-chulo 0.35s ease-in-out 0.9s both;
    }

    .chulo-circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 3.5;
        stroke-miterlimit: 10;
        stroke: #10B981;
        fill: rgba(16, 185, 129, 0.12);
        animation: stroke-circle 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }

    .chulo-check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        stroke-width: 4;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke: #34D399;
        animation: stroke-check 0.4s cubic-bezier(0.65, 0, 0.45, 1) 0.6s forwards;
    }

    @keyframes stroke-circle {
        100% {
            stroke-dashoffset: 0;
        }
    }

    @keyframes stroke-check {
        100% {
            stroke-dashoffset: 0;
        }
    }

    @keyframes scale-chulo {
        0%, 100% {
            transform: none;
        }
        50% {
            transform: scale3d(1.1, 1.1, 1);
        }
    }

    @keyframes fill-chulo {
        100% {
            box-shadow: inset 0px 0px 0px 42px rgba(16, 185, 129, 0.15), 0 0 35px rgba(16, 185, 129, 0.35);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function imprimirFacturaDirecta() {
        const frame = document.getElementById('receiptFrame');
        if (frame && frame.contentWindow) {
            frame.contentWindow.focus();
            frame.contentWindow.print();
        } else {
            window.open("{{ route('pagos.receipt', $pago) }}", '_blank');
        }
    }
</script>
@endpush
@endsection
