@extends('layouts.app')

@section('title', 'Dashboard')
@section('header_title', 'Tablero de Control')
@section('header_subtitle', 'Resumen ejecutivo de afiliaciones, cobranza y métricas financieras')

@section('content')
<div class="space-y-7">

    <!-- HERO INSTITUCIONAL DORADO -->
    <div class="relative overflow-hidden rounded-3xl glass-panel gold-border shine-sweep p-8 md:p-10">
        <div class="absolute -right-16 -top-16 w-96 h-96 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(217,119,6,.22), transparent 68%);"></div>
        <svg class="absolute right-8 top-1/2 -translate-y-1/2 w-56 h-56 opacity-[.10] pointer-events-none hidden md:block" viewBox="0 0 400 400" fill="none" stroke="#FCF6BA" stroke-width="2">
            <circle cx="200" cy="200" r="190"/><circle cx="200" cy="200" r="130"/><circle cx="200" cy="200" r="70"/><circle cx="200" cy="200" r="12" fill="#FCF6BA"/>
            <path d="M200 0v110M200 290v110M0 200h110M290 200h110" stroke-linecap="round"/>
        </svg>

        <div class="relative z-10 flex flex-wrap items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <img src="{{ asset('img/logo.png') }}" alt="SCOCC" class="h-20 w-auto object-contain hidden sm:block" style="filter: drop-shadow(0 0 22px rgba(217,119,6,.5)) drop-shadow(0 8px 16px rgba(0,0,0,.9));">
                <div class="space-y-2 max-w-xl">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-[0.18em] text-amber-200" style="background: linear-gradient(135deg, rgba(191,149,63,.2), rgba(120,82,16,.1)); border: 1px solid rgba(191,149,63,.35);">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-2 animate-pulse"></span>
                        {{ now()->translatedFormat('l, d \d\e F') }}
                    </span>
                    <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">
                        Bienvenido, <span class="gold-prestige-title" style="letter-spacing:.02em">{{ Auth::user()->nombre_completo ?? 'Administrador' }}</span>
                    </h1>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Polígono de Tiro &bull; Control de socios, cobranza multimoneda y acceso en garita, sincronizado con el BCV.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('pagos.create') }}" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-amber-500 via-amber-400 to-amber-500 hover:from-amber-400 hover:to-amber-300 text-slate-950 font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-amber-500/25 flex items-center transition transform hover:-translate-y-0.5">
                    <span class="material-icons-round text-base mr-2">add_card</span>
                    Registrar Cobro
                </a>
                <a href="{{ route('socios.create') }}" class="px-5 py-3 rounded-2xl bg-[#0F172A]/80 hover:bg-[#1A253E] border border-amber-500/30 text-amber-200 font-bold text-xs uppercase tracking-wider flex items-center transition transform hover:-translate-y-0.5">
                    <span class="material-icons-round text-base mr-2">person_add</span>
                    Afiliar Socio
                </a>
            </div>
        </div>
    </div>

    <!-- TARJETAS DE MÉTRICAS (KPIs) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <div class="kpi-card glass-panel gold-border rounded-2xl p-6 relative overflow-hidden" style="animation-delay:.05s">
            <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full" style="background: radial-gradient(circle, rgba(16,185,129,.22), transparent 70%);"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.18em] block">Socios Activos</span>
                    <h3 class="text-4xl font-black text-white mt-2 font-mono tabular-nums" data-count="{{ $totalActivos }}">{{ $totalActivos }}</h3>
                    <p class="text-[11px] text-emerald-400 font-medium mt-1.5 flex items-center"><span class="material-icons-round text-xs mr-1">verified</span>de {{ $totalSocios }} registrados</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 flex items-center justify-center"><span class="material-icons-round text-2xl">groups</span></div>
            </div>
        </div>

        <div class="kpi-card glass-panel gold-border rounded-2xl p-6 relative overflow-hidden" style="animation-delay:.12s">
            <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full" style="background: radial-gradient(circle, rgba(244,63,94,.22), transparent 70%);"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.18em] block">Socios en Mora</span>
                    <h3 class="text-4xl font-black text-rose-400 mt-2 font-mono tabular-nums" data-count="{{ $totalMorosos }}">{{ $totalMorosos }}</h3>
                    <p class="text-[11px] text-rose-400/80 font-medium mt-1.5 flex items-center"><span class="material-icons-round text-xs mr-1">warning</span>con saldo negativo</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/25 text-rose-400 flex items-center justify-center"><span class="material-icons-round text-2xl">error_outline</span></div>
            </div>
        </div>

        <div class="kpi-card glass-panel gold-border rounded-2xl p-6 relative overflow-hidden" style="animation-delay:.19s">
            <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full" style="background: radial-gradient(circle, rgba(245,158,11,.25), transparent 70%);"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.18em] block">Ingresos del Mes</span>
                    <h3 class="text-3xl font-black text-amber-300 mt-2 font-mono tabular-nums">${{ number_format($ingresosMes, 2) }}</h3>
                    <p class="text-[11px] text-amber-400/80 font-medium mt-1.5 flex items-center"><span class="material-icons-round text-xs mr-1">receipt_long</span>{{ $pagosMesCount }} cobros &bull; &asymp; {{ number_format($ingresosMes * $tasaBcv, 0, ',', '.') }} Bs</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/25 text-amber-400 flex items-center justify-center"><span class="material-icons-round text-2xl">payments</span></div>
            </div>
        </div>

        <div class="kpi-card glass-panel gold-border rounded-2xl p-6 relative overflow-hidden" style="animation-delay:.26s">
            <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full" style="background: radial-gradient(circle, rgba(59,130,246,.22), transparent 70%);"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.18em] block">En el Club Ahora</span>
                    <h3 class="text-4xl font-black text-sky-300 mt-2 font-mono tabular-nums" data-count="{{ $dentroAhora }}">{{ $dentroAhora }}</h3>
                    <p class="text-[11px] text-sky-400/80 font-medium mt-1.5 flex items-center"><span class="material-icons-round text-xs mr-1">login</span>{{ $visitasHoy }} ingresos hoy</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-sky-500/10 border border-sky-500/25 text-sky-400 flex items-center justify-center"><span class="material-icons-round text-2xl">security</span></div>
            </div>
        </div>
    </div>

    <!-- SALUD DE COBRANZA + TASA BCV -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="kpi-card glass-panel gold-border rounded-3xl p-7 lg:col-span-2 flex items-center gap-8" style="animation-delay:.3s">
            <div class="relative w-32 h-32 flex-shrink-0">
                <div class="w-full h-full rounded-full" style="background: conic-gradient(#FBBF24 0 {{ $pctSolventes }}%, rgba(244,63,94,.55) {{ $pctSolventes }}% 100%); -webkit-mask: radial-gradient(circle, transparent 54%, #000 55%); mask: radial-gradient(circle, transparent 54%, #000 55%);"></div>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-2xl font-black text-white font-mono">{{ $pctSolventes }}%</span>
                    <span class="text-[9px] uppercase tracking-widest text-slate-400">solventes</span>
                </div>
            </div>
            <div class="space-y-2">
                <h3 class="text-base font-bold text-white flex items-center"><span class="material-icons-round mr-2 text-amber-400">insights</span>Salud de la Cobranza</h3>
                <p class="text-xs text-slate-400 leading-relaxed">{{ $solventes }} de {{ $totalSocios }} socios están al día. {{ $totalMorosos }} requieren gestión de cobro.</p>
                <a href="{{ route('socios.index') }}" class="inline-flex items-center text-[11px] font-bold text-amber-300 hover:text-amber-200 uppercase tracking-wider">Ver directorio <span class="material-icons-round text-sm ml-1">arrow_forward</span></a>
            </div>
        </div>

        <div class="kpi-card glass-panel gold-border rounded-3xl p-7 relative overflow-hidden" style="animation-delay:.36s">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 rounded-full" style="background: radial-gradient(circle, rgba(245,158,11,.22), transparent 70%);"></div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.18em] block">Tasa Oficial BCV</span>
            <h3 class="text-3xl font-black text-amber-300 mt-2 font-mono">{{ number_format($tasaBcv, 2, ',', '.') }} <span class="text-xs font-sans text-amber-200">Bs / USD</span></h3>
            <p class="text-[11px] text-amber-400/80 font-medium mt-2 flex items-center"><span class="material-icons-round text-xs mr-1">currency_exchange</span>Cuota mensual &asymp; {{ number_format((float) config('club.cuota_mensual', 10) * $tasaBcv, 0, ',', '.') }} Bs</p>
        </div>
    </div>

    <!-- DIRECTORIO DE SOCIOS INTERACTIVO -->
    <div class="glass-panel rounded-3xl p-7 border border-[#1B253B] shadow-2xl space-y-6">
        
        <!-- Header de la tabla -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-white tracking-tight flex items-center">
                    <span class="material-icons-round mr-2 text-amber-400">group</span>
                    Socios del Club
                </h3>
                <p class="text-xs text-slate-400">Busca socios instantáneamente por nombre, cédula o número de acción</p>
            </div>

            <!-- Buscador Reactivo -->
            <div class="relative w-full md:w-96">
                <input type="text" id="searchInput" placeholder="Buscar por nombre, cédula o acción..." 
                       class="w-full bg-[#0A0E1A] border border-[#23314D] rounded-2xl pl-11 pr-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all">
                <span class="material-icons-round absolute left-3.5 top-2.5 text-slate-500 text-sm">search</span>
            </div>
        </div>

        <!-- Tabla Estilizada -->
        <div class="overflow-x-auto rounded-2xl border border-[#1B253B]">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0B101D] text-slate-400 font-bold uppercase tracking-wider border-b border-[#1B253B]">
                    <tr>
                        <th class="px-6 py-4">Socio Titular</th>
                        <th class="px-6 py-4">Cédula</th>
                        <th class="px-6 py-4">Acción</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4">Saldo Contable</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody id="sociosTbody" class="divide-y divide-[#1B253B] bg-[#0E1526]/50">
                    @forelse($sociosRecientes as $socio)
                    <tr class="hover:bg-[#162238]/60 transition-colors">
                        <td class="px-6 py-4 font-semibold text-white flex items-center space-x-3.5">
                            <img src="{{ $socio->avatar_url }}" alt="avatar" class="w-9 h-9 rounded-xl border border-slate-700 shadow-sm object-cover">
                            <div>
                                <span class="block text-slate-100 font-bold text-xs">{{ $socio->nombre_completo }}</span>
                                <span class="text-[11px] text-slate-400 font-normal">{{ $socio->correo }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-300 font-mono">V-{{ $socio->cedula }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg bg-amber-500/10 text-amber-400 font-mono font-bold border border-amber-500/20 text-[11px]">
                                {{ $socio->accion }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($socio->estado === 'activo')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                                    Activo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide uppercase bg-rose-500/10 text-rose-400 border border-rose-500/30 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400 mr-1.5"></span>
                                    Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($socio->saldo < 0)
                                <div class="space-y-0.5">
                                    <span class="text-rose-400 font-extrabold font-mono text-sm block">
                                        ${{ number_format($socio->saldo, 2) }}
                                    </span>
                                    <span class="text-[10px] text-rose-400/90 font-medium">Debe {{ $socio->meses_deuda }} {{ $socio->meses_deuda == 1 ? 'mes' : 'meses' }}</span>
                                </div>
                            @else
                                <div class="space-y-0.5">
                                    <span class="text-emerald-400 font-extrabold font-mono text-sm block">
                                        ${{ number_format($socio->saldo, 2) }}
                                    </span>
                                    <span class="text-[10px] text-emerald-400 font-medium">Solvente</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('socios.show', $socio) }}" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 font-semibold text-xs transition">
                                <span class="material-icons-round text-xs mr-1 text-amber-400">visibility</span>
                                Ficha
                            </a>
                            <a href="{{ route('pagos.create', ['cedula' => $socio->cedula]) }}" class="inline-flex items-center px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-500 hover:to-emerald-600 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition">
                                <span class="material-icons-round text-xs mr-1">payments</span>
                                Cobrar
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500 font-medium">No hay socios registrados en el sistema.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- HISTORIAL DE COBROS RECIENTES -->
    <div class="glass-panel rounded-3xl p-7 border border-[#1B253B] shadow-2xl space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-white tracking-tight flex items-center">
                    <span class="material-icons-round mr-2 text-emerald-400">receipt_long</span>
                    Últimas Transacciones Registradas
                </h3>
                <p class="text-xs text-slate-400">Cobros procesados recientemente con liquidación cambiaria</p>
            </div>
            <a href="{{ route('pagos.create') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs font-semibold text-slate-200 flex items-center transition">
                <span class="material-icons-round text-sm mr-1.5 text-emerald-400">add</span>
                Nuevo Cobro
            </a>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-[#1B253B]">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0B101D] text-slate-400 font-bold uppercase tracking-wider border-b border-[#1B253B]">
                    <tr>
                        <th class="px-6 py-4">Socio Titular</th>
                        <th class="px-6 py-4">Concepto del Pago</th>
                        <th class="px-6 py-4">Monto ($ USD)</th>
                        <th class="px-6 py-4">Fecha y Hora</th>
                        <th class="px-6 py-4 text-right">Comprobante</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1B253B] bg-[#0E1526]/50">
                    @forelse($pagosRecientes as $pago)
                    <tr class="hover:bg-[#162238]/60 transition-colors">
                        <td class="px-6 py-4 font-bold text-white">
                            {{ $pago->socio->nombre_completo ?? 'Socio' }}
                            <span class="block text-[11px] text-slate-400 font-normal">V-{{ $pago->socio->cedula ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-300 font-medium">{{ $pago->descripcion }}</td>
                        <td class="px-6 py-4 font-mono font-extrabold text-emerald-400 text-sm">
                            ${{ number_format($pago->monto, 2) }}
                        </td>
                        <td class="px-6 py-4 text-slate-400">
                            {{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y h:i A') : '' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('pagos.receipt', $pago) }}" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-[#162238] hover:bg-[#1E2D4A] border border-[#263757] text-slate-300 text-xs font-semibold transition">
                                <span class="material-icons-round text-sm mr-1.5 text-amber-400">print</span>
                                Recibo
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500 font-medium">No se han registrado pagos recientemente.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Conteo animado de los KPIs
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count, 10) || 0;
        if (target === 0) return;
        const t0 = performance.now(), dur = 900;
        (function tick(t) {
            const k = Math.min(1, (t - t0) / dur);
            el.textContent = Math.round(target * (1 - Math.pow(1 - k, 3)));
            if (k < 1) requestAnimationFrame(tick);
        })(t0);
    });

    // Búsqueda interactiva en tiempo real con debounce
    const searchInput = document.getElementById('searchInput');
    const tbody = document.getElementById('sociosTbody');
    let timer = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timer);
        const query = this.value.trim();

        timer = setTimeout(() => {
            fetch(`{{ route('socios.search.json') }}?query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    tbody.innerHTML = '';
                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-slate-500 font-medium">No se encontraron socios que coincidan con la búsqueda.</td></tr>';
                        return;
                    }

                    data.forEach(s => {
                        const tr = document.createElement('tr');
                        tr.className = "hover:bg-[#162238]/60 transition-colors";

                        const badgeEstado = s.estado === 'activo'
                            ? `<span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>Activo</span>`
                            : `<span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide uppercase bg-rose-500/10 text-rose-400 border border-rose-500/30 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-rose-400 mr-1.5"></span>Inactivo</span>`;

                        const saldoBlock = s.saldo < 0
                            ? `<span class="text-rose-400 font-extrabold font-mono text-sm block">$${Number(s.saldo).toFixed(2)}</span><span class="text-[10px] text-rose-400/90 font-medium">En Mora</span>`
                            : `<span class="text-emerald-400 font-extrabold font-mono text-sm block">$${Number(s.saldo).toFixed(2)}</span><span class="text-[10px] text-emerald-400 font-medium">Solvente</span>`;

                        tr.innerHTML = `
                            <td class="px-6 py-4 font-semibold text-white flex items-center space-x-3.5">
                                <img src="${s.avatar}" alt="avatar" class="w-9 h-9 rounded-xl border border-slate-700 shadow-sm object-cover">
                                <div>
                                    <span class="block text-slate-100 font-bold text-xs">${s.nombre_completo}</span>
                                    <span class="text-[11px] text-slate-400 font-normal">Socio Afiliado</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-300 font-mono">V-${s.cedula}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg bg-amber-500/10 text-amber-400 font-mono font-bold border border-amber-500/20 text-[11px]">${s.accion}</span>
                            </td>
                            <td class="px-6 py-4">${badgeEstado}</td>
                            <td class="px-6 py-4">${saldoBlock}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="/socios/${s.id}" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 font-semibold text-xs transition">
                                    <span class="material-icons-round text-xs mr-1 text-amber-400">visibility</span> Ficha
                                </a>
                                <a href="/pagos/create?cedula=${encodeURIComponent(s.cedula)}" class="inline-flex items-center px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-500 hover:to-emerald-600 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition">
                                    <span class="material-icons-round text-xs mr-1">payments</span> Cobrar
                                </a>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(err => console.error(err));
        }, 300);
    });
</script>
@endpush