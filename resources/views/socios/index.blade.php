@extends('layouts.app')

@section('title', 'Directorio de Socios')
@section('header_title', 'Registro y Padrón de Miembros')
@section('header_subtitle', 'Directorio integral de socios titulares, acciones y solvencia')

@section('content')
<div class="space-y-7">

    <!-- Tarjetas Resumen de Membresías -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total Padrón</span>
                <h4 class="text-2xl font-extrabold text-white mt-0.5 font-mono">{{ $socios->total() }}</h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                <span class="material-icons-round text-xl">badge</span>
            </div>
        </div>

        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Socios Solventes</span>
                <h4 class="text-2xl font-extrabold text-emerald-400 mt-0.5 font-mono">
                    {{ \App\Models\Socio::where('saldo', '>=', 0)->where('estado', 'activo')->count() }}
                </h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                <span class="material-icons-round text-xl">check_circle</span>
            </div>
        </div>

        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Cuentas con Mora</span>
                <h4 class="text-2xl font-extrabold text-rose-400 mt-0.5 font-mono">
                    {{ \App\Models\Socio::where('saldo', '<', 0)->count() }}
                </h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-400 flex items-center justify-center">
                <span class="material-icons-round text-xl">warning_amber</span>
            </div>
        </div>
    </div>

    <!-- Barra de Búsqueda y Filtros -->
    <div class="glass-panel rounded-3xl p-6 border border-[#1B253B] shadow-2xl flex flex-wrap items-center justify-between gap-4">
        <form action="{{ route('socios.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            
            <div class="relative w-64 md:w-80">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, cédula o acción..."
                       class="w-full bg-[#0A0E1A] border border-[#23314D] rounded-2xl pl-10 pr-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all">
                <span class="material-icons-round absolute left-3.5 top-2.5 text-slate-500 text-sm">search</span>
            </div>

            <select name="estado" class="bg-[#0A0E1A] border border-[#23314D] rounded-2xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-amber-400">
                <option value="">Todos los Estados</option>
                <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Socios Activos</option>
                <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Socios Inactivos</option>
            </select>

            <button type="submit" class="px-5 py-2.5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs transition shadow-md shadow-amber-500/10">
                Filtrar
            </button>

            @if(request()->hasAny(['search', 'estado']))
                <a href="{{ route('socios.index') }}" class="text-xs text-slate-400 hover:text-white px-2 py-1">Limpiar</a>
            @endif
        </form>

        <a href="{{ route('socios.create') }}" class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-500/20 flex items-center transition">
            <span class="material-icons-round text-base mr-1.5">person_add</span>
            Afiliar Nuevo Socio
        </a>
    </div>

    <!-- Tabla de Socios -->
    <div class="glass-panel rounded-3xl p-7 border border-[#1B253B] shadow-2xl space-y-4">
        <div class="overflow-x-auto rounded-2xl border border-[#1B253B]">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0B101D] text-slate-400 font-bold uppercase tracking-wider border-b border-[#1B253B]">
                    <tr>
                        <th class="px-6 py-4">Socio Titular</th>
                        <th class="px-6 py-4">Cédula</th>
                        <th class="px-6 py-4">Acción</th>
                        <th class="px-6 py-4">Contacto</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4">Saldo Contable</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1B253B] bg-[#0E1526]/50">
                    @forelse($socios as $socio)
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
                        <td class="px-6 py-4 text-slate-300">
                            {{ $socio->numero ?? '-' }}
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
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500 font-medium">No se encontraron socios con los filtros aplicados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4">
            {{ $socios->links() }}
        </div>
    </div>

</div>
@endsection
