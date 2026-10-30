@extends('layouts.app')

@section('title', 'Recibo de Pago #' . $pago->id)
@section('header_title', 'Comprobante de Caja')
@section('header_subtitle', 'Recibo oficial emitido por la administración del club')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Barra de Acciones Superior (Oculta al imprimir) -->
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-xs font-semibold text-slate-400 hover:text-white transition">
            <span class="material-icons-round mr-1 text-sm">arrow_back</span>
            Regresar al Dashboard
        </a>
        <div class="flex items-center space-x-3">
            <a href="{{ route('pagos.create') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 text-xs font-bold transition">
                Nuevo Cobro
            </a>
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-extrabold text-xs shadow-lg shadow-amber-500/20 flex items-center transition">
                <span class="material-icons-round mr-1.5 text-base">print</span>
                Imprimir Comprobante Oficial
            </button>
        </div>
    </div>

    <!-- DOCUMENTO OFICIAL / FACTURA / RECIBO -->
    <div class="receipt-card glass-panel rounded-3xl p-10 border border-amber-500/30 shadow-2xl relative overflow-hidden bg-[#0D1424] text-slate-100 print:bg-white print:text-black print:border-none print:shadow-none print:p-4">
        
        <!-- Línea dorada superior decorativa -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-400 via-amber-500 to-emerald-400"></div>

        <!-- Sello de Agua de Fondo (Solo visible en pantalla o impresión sutil) -->
        <div class="absolute right-6 top-24 opacity-5 pointer-events-none print:opacity-10">
            <img src="{{ asset('img/logo.png') }}" alt="Watermark" class="w-64 h-auto">
        </div>

        <!-- Encabezado del Recibo -->
        <div class="flex flex-wrap items-center justify-between gap-6 pb-8 border-b border-[#1B253B] print:border-gray-300">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 p-[1px] shadow-lg shadow-amber-500/10">
                    <div class="w-full h-full bg-[#0B0F19] print:bg-white rounded-[15px] flex items-center justify-center">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-8 w-auto">
                    </div>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white print:text-black tracking-tight">COUNTRY CLUB</h2>
                    <p class="text-xs text-amber-400 print:text-gray-700 font-semibold tracking-wider uppercase">Dirección de Administración y Finanzas</p>
                    <p class="text-[11px] text-slate-400 print:text-gray-500">R.I.F. J-00000000-0 | Registro Oficial de Clubes Sociales</p>
                </div>
            </div>

            <div class="text-right">
                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 print:border-black print:text-black mb-1.5">
                    RECIBO DE CAJA
                </span>
                <p class="text-2xl font-mono font-black text-amber-400 print:text-black tracking-tight">
                    #REC-{{ str_pad($pago->id, 5, '0', STR_PAD_LEFT) }}
                </p>
                <p class="text-[11px] text-slate-400 print:text-gray-600">
                    Emisión: {{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y h:i A') : now()->format('d/m/Y h:i A') }}
                </p>
            </div>
        </div>

        <!-- Bloque de Datos: Socio y Liquidación -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-8 border-b border-[#1B253B] print:border-gray-300 text-xs">
            <!-- Datos del Socio -->
            <div class="space-y-2">
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 print:text-gray-600 block">Titular del Pago</span>
                <h3 class="text-base font-extrabold text-white print:text-black">{{ $pago->socio->nombre_completo ?? 'Socio Registrado' }}</h3>
                <div class="space-y-1 text-slate-300 print:text-gray-700">
                    <p><strong class="text-slate-400 print:text-gray-600">Cédula:</strong> V-{{ $pago->socio->cedula ?? '' }}</p>
                    <p><strong class="text-slate-400 print:text-gray-600">Acción / Membresía:</strong> <span class="font-mono font-bold text-amber-400 print:text-black">{{ $pago->socio->accion ?? '' }}</span></p>
                    <p><strong class="text-slate-400 print:text-gray-600">Correo:</strong> {{ $pago->socio->correo ?? '' }}</p>
                </div>
            </div>

            <!-- Datos Económicos -->
            <div class="space-y-2 md:text-right">
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 print:text-gray-600 block">Condición Cambiaria</span>
                <p class="text-xs text-slate-300 print:text-gray-700">Tasa Oficial Banco Central de Venezuela:</p>
                <p class="text-base font-mono font-extrabold text-amber-400 print:text-black">
                    {{ number_format($tasa, 2, ',', '.') }} Bs / USD
                </p>
                <p class="text-[11px] text-slate-400 print:text-gray-500">
                    Operación respaldada según Convenio Cambiario N° 1
                </p>
            </div>
        </div>

        <!-- Tabla Desglose -->
        <div class="py-6 space-y-4">
            <div class="rounded-2xl border border-[#1B253B] print:border-gray-300 overflow-hidden">
                <table class="w-full text-xs">
                    <thead class="bg-[#0B101D] print:bg-gray-100 text-slate-400 print:text-gray-800 uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-5 py-3 text-left">Descripción del Concepto</th>
                            <th class="px-5 py-3 text-center">Divisa ($)</th>
                            <th class="px-5 py-3 text-right">Equivalente en Bolívares (Bs.)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1B253B] print:divide-gray-300 bg-[#0E1526]/40 print:bg-white">
                        <tr>
                            <td class="px-5 py-4 font-semibold text-white print:text-black">
                                {{ $pago->descripcion }}
                                <span class="block text-[11px] text-slate-400 print:text-gray-500 font-normal mt-0.5">Abono directo a cuenta de membresía</span>
                            </td>
                            <td class="px-5 py-4 text-center font-mono font-extrabold text-emerald-400 print:text-black text-sm">
                                ${{ number_format($pago->monto, 2) }} USD
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-extrabold text-amber-400 print:text-black text-sm">
                                Bs. {{ number_format($montoBs, 2, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Resumen Total y Nuevo Saldo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center pt-4">
                <div class="p-4 rounded-2xl bg-[#090D17] print:bg-gray-50 border border-[#1B253B] print:border-gray-300">
                    <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400 print:text-gray-600 block">Nuevo Saldo Contable</span>
                    <p class="text-xl font-mono font-extrabold {{ ($pago->socio->saldo ?? 0) < 0 ? 'text-rose-400' : 'text-emerald-400' }} print:text-black mt-0.5">
                        ${{ number_format($pago->socio->saldo ?? 0, 2) }} USD
                    </p>
                    <span class="text-[10px] text-slate-400 print:text-gray-500">
                        Estado del socio: <strong class="text-white print:text-black">{{ $pago->socio->condicion ?? 'SOLVENTE' }}</strong>
                    </span>
                </div>

                <div class="p-4 rounded-2xl bg-[#090D17] print:bg-gray-100 border border-amber-500/30 print:border-gray-400 text-right">
                    <span class="text-[10px] uppercase font-extrabold tracking-widest text-slate-400 print:text-gray-600 block">Total Recibido</span>
                    <h3 class="text-2xl font-mono font-black text-emerald-400 print:text-black mt-0.5">
                        ${{ number_format($pago->monto, 2) }} USD
                    </h3>
                    <p class="text-xs font-mono font-bold text-amber-400 print:text-black">
                        Bs. {{ number_format($montoBs, 2, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Firmas y Pie Legal -->
        <div class="pt-12 grid grid-cols-2 gap-12 text-center text-xs border-t border-[#1B253B] print:border-gray-300">
            <div class="space-y-1">
                <div class="border-b border-dashed border-slate-600 print:border-gray-400 pb-12 w-48 mx-auto"></div>
                <p class="font-bold text-slate-300 print:text-black">Cajero / Operador</p>
                <p class="text-[10px] text-slate-500 print:text-gray-500">Firma Autorizada</p>
            </div>

            <div class="space-y-1">
                <div class="border-b border-dashed border-slate-600 print:border-gray-400 pb-12 w-48 mx-auto"></div>
                <p class="font-bold text-slate-300 print:text-black">{{ $pago->socio->nombre_completo ?? 'Socio Titular' }}</p>
                <p class="text-[10px] text-slate-500 print:text-gray-500">Conforme Socio</p>
            </div>
        </div>

        <div class="pt-8 text-center text-[10px] text-slate-500 print:text-gray-500 border-t border-[#1B253B] print:border-gray-200 mt-6">
            <p>Documento generado electrónicamente por el Sistema Country Club.</p>
            <p>Válido como comprobante interno de pago de cuota de mantenimiento.</p>
        </div>

    </div>

</div>
@endsection

@push('styles')
<style>
    @media print {
        body { background: white !important; color: black !important; font-size: 12px; }
        aside, header, .no-print, .ambient-glow-amber, .ambient-glow-emerald { display: none !important; }
        main { padding: 0 !important; margin: 0 !important; }
        .receipt-card {
            border: 1px solid #ccc !important;
            background: white !important;
            box-shadow: none !important;
            color: black !important;
        }
    }
</style>
@endpush
