@extends('layouts.app')

@section('title', 'Dashboard')
@section('header_title', 'Tablero de Control')
@section('header_subtitle', 'Resumen ejecutivo de afiliaciones, cobranza y métricas financieras')

@section('content')
<div class="space-y-7">

    <!-- HERO BANNER BIENVENIDA -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#121B30] via-[#101728] to-[#0D1322] border border-amber-500/20 p-8 shadow-2xl">
        <div class="absolute -right-10 -bottom-10 w-80 h-80 bg-gradient-to-br from-amber-500/10 to-emerald-500/0 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-wrap items-center justify-between gap-6">
            <div class="space-y-1.5 max-w-xl">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mr-2 animate-ping"></span>
                    Sistema en Tiempo Real
                </span>
                <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">
                    Bienvenido, {{ Auth::user()->nombre_completo ?? 'Administrador' }}
                </h1>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Control centralizado de miembros, estados de cuenta multimoneda y sincronización con el Banco Central de Venezuela.
                </p>
            </div>

            <!-- Acciones Rápidas -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('pagos.create') }}" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-500/20 flex items-center transition-all duration-200 transform hover:-translate-y-0.5">
                    <span class="material-icons-round text-base mr-2">add_card</span>
                    Registrar Cobro
                </a>
                <a href="{{ route('socios.create') }}" class="px-5 py-3 rounded-2xl bg-[#162238] hover:bg-[#1E2D4A] border border-[#263757] text-slate-200 font-bold text-xs flex items-center transition-all duration-200 transform hover:-translate-y-0.5">
                    <span class="material-icons-round text-base mr-2">person_add</span>
                    Afiliar Socio
                </a>
            </div>
        </div>
    </div>

    <!-- TARJETAS DE MÉTRICAS (KPIs) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- 1. Socios Activos -->
        <div class="glass-panel rounded-2xl p-6 border-l-4 border-l-emerald-400 shadow-xl relative overflow-hidden group hover:border-[#2C3B5E] transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">Socios Activos</span>
                    <h3 class="text-3xl font-extrabold text-white mt-1 font-mono">{{ $totalActivos }}</h3>
                    <p class="text-[11px] text-emerald-400 font-medium mt-1 flex items-center">
                        <span class="material-icons-round text-xs mr-1">trending_up</span>
                        Habilitados para ingreso
                    </p>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center p-3 group-hover:scale-110 transition-transform">
                    <span class="material-icons-round text-2xl">groups</span>
                </div>
            </div>
        </div>

        <!-- 2. Socios en Mora -->
        <div class="glass-panel rounded-2xl p-6 border-l-4 border-l-rose-400 shadow-xl relative overflow-hidden group hover:border-[#2C3B5E] transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">Socios en Mora</span>
                    <h3 class="text-3xl font-extrabold text-rose-400 mt-1 font-mono">{{ $totalMorosos }}</h3>
                    <p class="text-[11px] text-rose-400/80 font-medium mt-1 flex items-center">
                        <span class="material-icons-round text-xs mr-1">warning</span>
                        Con saldo negativo
                    </p>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center p-3 group-hover:scale-110 transition-transform">
                    <span class="material-icons-round text-2xl">error_outline</span>
                </div>
            </div>
        </div>

        <!-- 3. Socios Inactivos -->
        <div class="glass-panel rounded-2xl p-6 border-l-4 border-l-slate-500 shadow-xl relative overflow-hidden group hover:border-[#2C3B5E] transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">Inactivos / Retirados</span>
                    <h3 class="text-3xl font-extrabold text-slate-300 mt-1 font-mono">{{ $totalInactivos }}</h3>
                    <p class="text-[11px] text-slate-400 font-medium mt-1 flex items-center">
                        <span class="material-icons-round text-xs mr-1">person_off</span>
                        Membresías pausadas
                    </p>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-slate-700/30 border border-slate-700/50 text-slate-400 flex items-center justify-center p-3 group-hover:scale-110 transition-transform">
                    <span class="material-icons-round text-2xl">history_toggle_off</span>
                </div>
            </div>
        </div>

        <!-- 4. Dólar BCV Oficial -->
        <div class="glass-panel rounded-2xl p-6 border-l-4 border-l-amber-400 shadow-xl relative overflow-hidden group hover:border-[#2C3B5E] transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">Tasa Oficial BCV</span>
                    <h3 class="text-2xl font-extrabold text-amber-400 mt-1 font-mono">
                        {{ number_format($tasaBcv, 2, ',', '.') }} <span class="text-xs font-sans text-amber-300">Bs</span>
                    </h3>
                    <p class="text-[11px] text-amber-400/80 font-medium mt-1 flex items-center">
                        <span class="material-icons-round text-xs mr-1">currency_exchange</span>
                        Actualizada y en vivo
                    </p>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center p-3 group-hover:scale-110 transition-transform">
                    <span class="material-icons-round text-2xl">account_balance</span>
                </div>
            </div>
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
