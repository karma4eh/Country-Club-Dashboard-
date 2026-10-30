@extends('layouts.app')

@section('title', 'Historial de Cobros')
@section('header_title', 'Historial General de Cobros y Caja')
@section('header_subtitle', 'Registro detallado de transacciones, liquidaciones y recibos emitidos')

@section('content')
<div class="space-y-7">

    @php
        $tasaBcv = \App\Models\TasaDolar::tasaActual();
        $totalUsd = \App\Models\Pago::sum('monto');
        $totalBs = $totalUsd * $tasaBcv;
        $totalTransacciones = \App\Models\Pago::count();
        $promedioUsd = $totalTransacciones > 0 ? $totalUsd / $totalTransacciones : 0;
    @endphp

    <!-- Tarjetas de Métrica de Cobranza -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total Recaudado (USD)</span>
                <h4 class="text-2xl font-extrabold text-emerald-400 mt-0.5 font-mono">
                    ${{ number_format($totalUsd, 2) }}
                </h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                <span class="material-icons-round text-xl">attach_money</span>
            </div>
        </div>

        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total en Bolívares (BCV)</span>
                <h4 class="text-xl font-extrabold text-amber-300 mt-0.5 font-mono truncate max-w-[180px]">
                    Bs. {{ number_format($totalBs, 2, ',', '.') }}
                </h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                <span class="material-icons-round text-xl">currency_exchange</span>
            </div>
        </div>

        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Transacciones Totales</span>
                <h4 class="text-2xl font-extrabold text-white mt-0.5 font-mono">
                    {{ $totalTransacciones }}
                </h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center">
                <span class="material-icons-round text-xl">receipt</span>
            </div>
        </div>

        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Ticket Promedio</span>
                <h4 class="text-2xl font-extrabold text-purple-400 mt-0.5 font-mono">
                    ${{ number_format($promedioUsd, 2) }}
                </h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center">
                <span class="material-icons-round text-xl">analytics</span>
            </div>
        </div>
    </div>

    <!-- Barra Superior de Acciones -->
    <div class="glass-panel rounded-3xl p-6 border border-[#1B253B] shadow-2xl flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                <span class="material-icons-round text-lg">receipt_long</span>
            </div>
            <div>
                <h3 class="text-sm font-bold text-white">Registro de Cobros y Facturación</h3>
                <p class="text-[11px] text-slate-400">Comprobantes oficiales emitidos en caja</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('pagos.create') }}" class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 flex items-center space-x-1.5 transition active:scale-[0.98]">
                <span class="material-icons-round text-base">point_of_sale</span>
                <span>Nuevo Cobro / Abono</span>
            </a>
        </div>
    </div>

    <!-- Tabla Principal de Cobros -->
    <div class="glass-panel rounded-3xl p-7 border border-[#1B253B] shadow-2xl space-y-5">
        <div class="overflow-x-auto rounded-2xl border border-[#1B253B]">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0B101D] text-slate-400 font-bold uppercase tracking-wider border-b border-[#1B253B]">
                    <tr>
                        <th class="px-6 py-4">N° Recibo</th>
                        <th class="px-6 py-4">Fecha & Hora</th>
                        <th class="px-6 py-4">Socio Titular</th>
                        <th class="px-6 py-4">Concepto / Motivo</th>
                        <th class="px-6 py-4">Monto USD</th>
                        <th class="px-6 py-4">Equivalente BCV</th>
                        <th class="px-6 py-4 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1B253B] bg-[#0E1526]/50">
                    @forelse($pagos as $pago)
                    <tr class="hover:bg-[#162238]/60 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-amber-400 whitespace-nowrap">
                            #REC-{{ str_pad($pago->id, 5, '0', STR_PAD_LEFT) }}
                        </td>

                        <td class="px-6 py-4 text-slate-300 whitespace-nowrap font-mono text-[11px]">
                            <span class="text-white font-semibold">{{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y') : 'N/A' }}</span>
                            <span class="text-slate-500 ml-1">{{ $pago->fecha_pago ? $pago->fecha_pago->format('h:i A') : '' }}</span>
                        </td>

                        <td class="px-6 py-4">
                            @if($pago->socio)
                            <div class="flex items-center space-x-2.5">
                                <img src="{{ $pago->socio->avatar_url }}" alt="avatar" class="w-7 h-7 rounded-lg border border-slate-700 object-cover">
                                <div>
                                    <a href="{{ route('socios.show', $pago->socio) }}" class="font-bold text-slate-100 hover:text-amber-400 transition block truncate max-w-[160px]">
                                        {{ $pago->socio->nombre_completo }}
                                    </a>
                                    <span class="text-[10px] font-mono text-amber-400/90">{{ $pago->socio->accion }} • V-{{ $pago->socio->cedula }}</span>
                                </div>
                            </div>
                            @else
                                <span class="text-slate-500 italic">Socio no asignado</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 font-medium text-slate-200">
                            {{ $pago->descripcion }}
                        </td>

                        <td class="px-6 py-4 font-mono font-extrabold text-emerald-400 whitespace-nowrap text-sm">
                            ${{ number_format($pago->monto, 2) }}
                        </td>

                        <td class="px-6 py-4 font-mono font-bold text-amber-300 whitespace-nowrap text-xs">
                            Bs. {{ number_format($pago->monto * $tasaBcv, 2, ',', '.') }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('pagos.receipt', $pago) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl bg-[#141C30] hover:bg-[#1E293B] text-slate-200 text-xs font-semibold border border-[#243352] transition">
                                <span class="material-icons-round text-xs text-amber-400">print</span>
                                <span>Recibo</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            <span class="material-icons-round text-4xl text-slate-600 block mb-2">point_of_sale</span>
                            <span>No hay pagos registrados en el sistema.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-2">
            {{ $pagos->links() }}
        </div>
    </div>

</div>
@endsection
