@extends('layouts.app')

@section('title', 'Bitácora & Auditoría')
@section('header_title', 'Bitácora de Auditoría y Seguridad')
@section('header_subtitle', 'Trazabilidad completa de operaciones, accesos y transacciones financieras')

@section('content')
<div class="space-y-7">

    <!-- Tarjetas de Métrica de Auditoría -->
    @php
        $totalEventos = \App\Models\Movimiento::count();
        $totalLogins = \App\Models\Movimiento::where('tipo', 'like', '%sesión%')->orWhere('tipo', 'like', '%login%')->count();
        $totalPagos = \App\Models\Movimiento::where('tipo', 'like', '%pago%')->orWhere('tipo', 'like', '%cobro%')->count();
        $totalSocios = \App\Models\Movimiento::where('tipo', 'like', '%socio%')->orWhere('tipo', 'like', '%afilia%')->count();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Eventos Auditados</span>
                <h4 class="text-2xl font-extrabold text-white mt-0.5 font-mono">{{ $totalEventos }}</h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                <span class="material-icons-round text-xl">security</span>
            </div>
        </div>

        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Inicios de Sesión</span>
                <h4 class="text-2xl font-extrabold text-blue-400 mt-0.5 font-mono">{{ $totalLogins }}</h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center">
                <span class="material-icons-round text-xl">login</span>
            </div>
        </div>

        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Operaciones de Pago</span>
                <h4 class="text-2xl font-extrabold text-emerald-400 mt-0.5 font-mono">{{ $totalPagos }}</h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                <span class="material-icons-round text-xl">paid</span>
            </div>
        </div>

        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Altas y Registros</span>
                <h4 class="text-2xl font-extrabold text-purple-400 mt-0.5 font-mono">{{ $totalSocios }}</h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center">
                <span class="material-icons-round text-xl">how_to_reg</span>
            </div>
        </div>
    </div>

    <!-- Tabla Principal de Bitácora -->
    <div class="glass-panel rounded-3xl p-7 border border-[#1B253B] shadow-2xl space-y-5">
        
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#1F293D] pb-5">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                    <span class="material-icons-round text-lg">history</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white">Registro Criptográfico y Operativo</h3>
                    <p class="text-[11px] text-slate-400">Historial inmutable de acciones realizadas en el sistema</p>
                </div>
            </div>

            <div class="flex items-center space-x-2 text-xs">
                <span class="flex items-center space-x-1.5 px-3 py-1.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[11px] font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Auditoría en Tiempo Real Activa</span>
                </span>
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-[#1B253B]">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0B101D] text-slate-400 font-bold uppercase tracking-wider border-b border-[#1B253B]">
                    <tr>
                        <th class="px-6 py-4">Fecha & Hora</th>
                        <th class="px-6 py-4">Usuario Responsable</th>
                        <th class="px-6 py-4">Nivel / Rol</th>
                        <th class="px-6 py-4">Evento / Descripción de la Actividad</th>
                        <th class="px-6 py-4 text-right">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1B253B] bg-[#0E1526]/50">
                    @forelse($movimientos as $mov)
                    @php
                        $tipoLower = strtolower($mov->tipo);
                        $badgeClass = 'bg-slate-700/50 text-slate-300 border-slate-600';
                        $icon = 'info';

                        if (str_contains($tipoLower, 'sesión') || str_contains($tipoLower, 'login')) {
                            $badgeClass = 'bg-blue-500/10 text-blue-400 border-blue-500/30';
                            $icon = 'login';
                        } elseif (str_contains($tipoLower, 'pago') || str_contains($tipoLower, 'cobro')) {
                            $badgeClass = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30';
                            $icon = 'receipt';
                        } elseif (str_contains($tipoLower, 'socio') || str_contains($tipoLower, 'afilia')) {
                            $badgeClass = 'bg-amber-500/10 text-amber-300 border-amber-500/30';
                            $icon = 'person_add';
                        } elseif (str_contains($tipoLower, 'actualiz') || str_contains($tipoLower, 'edit')) {
                            $badgeClass = 'bg-purple-500/10 text-purple-400 border-purple-500/30';
                            $icon = 'edit';
                        }
                    @endphp
                    <tr class="hover:bg-[#162238]/60 transition-colors">
                        <td class="px-6 py-4 text-slate-300 whitespace-nowrap font-mono text-[11px]">
                            <span class="text-white font-semibold">{{ $mov->fecha ? $mov->fecha->format('d/m/Y') : '' }}</span>
                            <span class="text-slate-500 ml-1.5">{{ $mov->fecha ? $mov->fecha->format('h:i:s A') : '' }}</span>
                        </td>
                        
                        <td class="px-6 py-4 text-white font-semibold flex items-center space-x-2.5">
                            <div class="w-7 h-7 rounded-lg bg-[#141C30] border border-[#243352] flex items-center justify-center text-amber-400 font-bold text-[10px]">
                                {{ strtoupper(substr($mov->usuario->nombre ?? $mov->usuario->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="truncate max-w-[180px]">{{ $mov->usuario->nombre_completo ?? $mov->usuario->name ?? $mov->usuario->email ?? 'Sistema Central' }}</span>
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider bg-[#141C30] text-amber-300 border border-amber-500/20">
                                {{ $mov->usuario->rol ?? 'SuperAdmin' }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <span class="material-icons-round text-sm {{ str_contains($badgeClass, 'emerald') ? 'text-emerald-400' : (str_contains($badgeClass, 'blue') ? 'text-blue-400' : 'text-amber-400') }}">
                                    {{ $icon }}
                                </span>
                                <span class="font-medium text-slate-100 text-xs">{{ $mov->tipo }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <span class="inline-flex items-center space-x-1 text-[10px] text-emerald-400 font-mono font-semibold">
                                <span class="material-icons-round text-xs">verified</span>
                                <span>Verificado</span>
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <span class="material-icons-round text-4xl text-slate-600 block mb-2">history_toggle_off</span>
                            <span>No hay registros en la bitácora de auditoría.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-2">
            {{ $movimientos->links() }}
        </div>
    </div>

</div>
@endsection
