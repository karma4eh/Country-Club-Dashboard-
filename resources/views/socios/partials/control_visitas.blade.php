<div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#1F293D] pb-4">
    <div class="flex items-center space-x-3">
        <div class="w-9 h-9 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center">
            <span class="material-icons-round text-lg">door_front</span>
        </div>
        <div>
            <div class="flex items-center space-x-2">
                <h3 class="text-sm font-bold text-white">Control Mensual de Visitas al Club</h3>
                <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-mono font-semibold">
                    En Vivo
                </span>
            </div>
            <p class="text-[11px] text-slate-400">Monitoreo de frecuencia y días de asistencia del socio mes a mes</p>
        </div>
    </div>

    <!-- Selector de Mes Interactivo (Sin recargar la página) -->
    <div class="flex items-center space-x-2 text-xs">
        <a href="{{ route('socios.show', [$socio, 'year' => $mesAnterior->year, 'month' => $mesAnterior->month]) }}" 
           onclick="cambiarMesVisitas(event, {{ $mesAnterior->year }}, {{ $mesAnterior->month }})"
           title="Mes anterior"
           class="px-3 py-1.5 rounded-xl bg-[#141C30] hover:bg-[#1E293B] border border-[#243352] text-slate-300 font-semibold transition flex items-center space-x-1 cursor-pointer active:scale-95">
            <span class="material-icons-round text-xs">chevron_left</span>
            <span>{{ $mesAnterior->translatedFormat('M Y') }}</span>
        </a>

        <span class="px-4 py-1.5 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-300 font-bold font-mono text-xs uppercase flex items-center space-x-1.5 shadow-sm">
            <span class="material-icons-round text-xs text-amber-400">calendar_month</span>
            <span>{{ $fechaSeleccionada->translatedFormat('F Y') }}</span>
        </span>

        <a href="{{ route('socios.show', [$socio, 'year' => $mesSiguiente->year, 'month' => $mesSiguiente->month]) }}" 
           onclick="cambiarMesVisitas(event, {{ $mesSiguiente->year }}, {{ $mesSiguiente->month }})"
           title="Mes siguiente"
           class="px-3 py-1.5 rounded-xl bg-[#141C30] hover:bg-[#1E293B] border border-[#243352] text-slate-300 font-semibold transition flex items-center space-x-1 cursor-pointer active:scale-95">
            <span>{{ $mesSiguiente->translatedFormat('M Y') }}</span>
            <span class="material-icons-round text-xs">chevron_right</span>
        </a>
    </div>
</div>

<!-- Tarjetas Resumen Mensual de Este Socio -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
    <div class="p-5 rounded-2xl bg-[#0A0F1E] border border-[#243352] flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Visitas en {{ $fechaSeleccionada->translatedFormat('F') }}</span>
            <h4 class="text-2xl font-extrabold text-emerald-400 mt-1 font-mono">{{ $totalVisitasMes ?? 0 }}</h4>
            <p class="text-[10px] text-slate-400 mt-0.5">Ingresos registrados</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
            <span class="material-icons-round text-xl">event_available</span>
        </div>
    </div>

    <div class="p-5 rounded-2xl bg-[#0A0F1E] border border-[#243352] flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Acompañantes / Invitados</span>
            <h4 class="text-2xl font-extrabold text-blue-400 mt-1 font-mono">{{ $totalInvitadosMes ?? 0 }}</h4>
            <p class="text-[10px] text-slate-400 mt-0.5">Personas invitadas</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center">
            <span class="material-icons-round text-xl">group</span>
        </div>
    </div>

    <div class="p-5 rounded-2xl bg-[#0A0F1E] border border-[#243352] flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Días de Asistencia</span>
            <h4 class="text-2xl font-extrabold text-purple-400 mt-1 font-mono">{{ count($diasAsistidos ?? []) }} días</h4>
            <p class="text-[10px] text-slate-400 mt-0.5">Días distintos en el mes</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center">
            <span class="material-icons-round text-xl">calendar_today</span>
        </div>
    </div>
</div>

<!-- Heatmap / Calendario Visual de Asistencia en el Mes -->
<div class="space-y-2.5">
    <div class="flex items-center justify-between">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            Mapa Visual de Días de Asistencia ({{ $fechaSeleccionada->translatedFormat('F Y') }})
        </span>
        <span class="text-[10px] text-slate-400">
            🟢 Verde = Asistió al Club ese día
        </span>
    </div>

    <div class="grid grid-cols-7 sm:grid-cols-10 md:grid-cols-16 gap-1.5 p-3.5 rounded-2xl bg-[#0A0F1E] border border-[#243352]/40 text-center">
        @php
            $diasTotal = $fechaSeleccionada->daysInMonth;
            $diasSet = collect($diasAsistidos ?? []);
        @endphp
        @for($d = 1; $d <= $diasTotal; $d++)
            @php
                $asistio = $diasSet->contains($d);
            @endphp
            <div class="p-2 rounded-xl border text-xs font-mono transition-all {{ $asistio ? 'bg-emerald-500/25 border-emerald-500/50 text-emerald-300 font-bold shadow-sm shadow-emerald-500/20' : 'bg-[#141C30]/40 border-[#243352]/30 text-slate-600' }}"
                 title="{{ $asistio ? "Día $d: Asistió al club" : "Día $d: Sin registro" }}">
                <span class="block text-[11px]">{{ $d }}</span>
                @if($asistio)
                    <span class="material-icons-round text-[10px] text-emerald-400 leading-none">check</span>
                @else
                    <span class="text-[9px] text-slate-700 leading-none">&bull;</span>
                @endif
            </div>
        @endfor
    </div>
</div>

<!-- Detalle de Visitas de Este Mes -->
<div class="overflow-x-auto rounded-2xl border border-[#1B253B]">
    <table class="w-full text-left text-xs">
        <thead class="bg-[#0B101D] text-slate-400 font-bold uppercase tracking-wider border-b border-[#1B253B]">
            <tr>
                <th class="px-6 py-4">Fecha & Entrada</th>
                <th class="px-6 py-4">Salida / Permanencia</th>
                <th class="px-6 py-4">Oficial Vigilante</th>
                <th class="px-6 py-4">Invitados / Acompañantes</th>
                <th class="px-6 py-4">Observaciones / Vehículo</th>
                <th class="px-6 py-4 text-right">Estatus</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#1B253B] bg-[#0E1526]/50">
            @forelse($visitasMes ?? [] as $v)
            <tr class="hover:bg-[#162238]/60 transition-colors">
                <td class="px-6 py-4 text-slate-300 whitespace-nowrap font-mono text-[11px]">
                    <span class="text-white font-semibold">{{ $v->fecha_entrada ? $v->fecha_entrada->format('d/m/Y') : '' }}</span>
                    <span class="text-amber-400 ml-1.5 font-bold">{{ $v->fecha_entrada ? $v->fecha_entrada->format('h:i A') : '' }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap font-mono text-[11px]">
                    @if($v->fecha_salida)
                        <span class="text-slate-300">{{ $v->fecha_salida->format('h:i A') }}</span>
                        <span class="text-[10px] text-amber-400 block font-bold">({{ $v->tiempo_permanencia }})</span>
                    @elseif($v->en_club)
                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 text-[9px] font-bold">
                            EN CLUB
                        </span>
                    @else
                        <span class="text-slate-500 italic text-[10px]">-</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-slate-200">
                    <div class="flex items-center space-x-1.5">
                        <span class="material-icons-round text-xs text-amber-400">shield</span>
                        <span class="font-semibold text-slate-200">{{ $v->vigilante->nombre_completo ?? 'Personal de Seguridad' }}</span>
                    </div>
                    <span class="text-[10px] text-slate-500 block font-mono pl-4">Garita Principal</span>
                </td>
                <td class="px-6 py-4 font-mono text-xs text-slate-300">
                    @if($v->invitados > 0)
                        <span class="px-2.5 py-0.5 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20 font-bold">
                            +{{ $v->invitados }} persona(s)
                        </span>
                    @else
                        <span class="text-slate-500">Solo titular</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-slate-400 truncate max-w-[220px]">
                    {{ $v->observaciones ?? '-' }}
                </td>
                <td class="px-6 py-4 text-right">
                    <span class="inline-flex items-center space-x-1 text-[10px] text-emerald-400 font-mono font-semibold">
                        <span class="material-icons-round text-xs">verified</span>
                        <span>Ingreso Válido</span>
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                    <span class="material-icons-round text-3xl text-slate-600 block mb-1">no_meeting_room</span>
                    <span>No se registraron ingresos para este socio en {{ $fechaSeleccionada->translatedFormat('F de Y') }}.</span>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
