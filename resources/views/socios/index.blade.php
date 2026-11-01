@extends('layouts.app')

@section('title', 'Directorio de Socios & Carnets')
@section('header_title', 'Directorio de Socios & Carnets Digitales')
@section('header_subtitle', 'Padrón oficial de miembros titulares, consulta censal y emisión de credenciales')

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

        @if(!Auth::user()->esVigilante())
        <a href="{{ route('socios.create') }}" class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-500/20 flex items-center transition">
            <span class="material-icons-round text-base mr-1.5">person_add</span>
            Afiliar Nuevo Socio
        </a>
        @endif
    </div>

    <!-- Tabla de Socios -->
    <div class="glass-panel rounded-3xl p-6 sm:p-7 border border-[#1B253B] shadow-2xl space-y-4">
        <div class="overflow-x-auto rounded-2xl border border-[#1B253B]">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0B101D] text-slate-400 font-bold uppercase tracking-wider border-b border-[#1B253B]">
                    <tr>
                        <th class="px-4 py-3.5">Socio & Acción</th>
                        <th class="px-3.5 py-3.5">Teléfono</th>
                        <th class="px-3.5 py-3.5">Estado</th>
                        <th class="px-3.5 py-3.5">Saldo Contable</th>
                        <th class="px-4 py-3.5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1B253B] bg-[#0E1526]/50">
                    @forelse($socios as $socio)
                    <tr class="hover:bg-[#162238]/60 transition-colors">
                        <td class="px-4 py-3.5">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $socio->avatar_url }}" alt="avatar" class="w-10 h-10 rounded-xl border border-amber-500/30 shadow-md object-cover flex-shrink-0">
                                <div class="min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('socios.show', $socio) }}" class="font-bold text-white hover:text-amber-300 transition text-xs truncate max-w-[170px] lg:max-w-[220px]" title="{{ $socio->nombre_completo }}">
                                            {{ $socio->nombre_completo }}
                                        </a>
                                        <span class="px-2 py-0.5 rounded-md bg-amber-500/15 text-amber-300 font-mono font-bold text-[10px] border border-amber-500/30 flex-shrink-0">
                                            {{ $socio->accion }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-[11px] text-slate-400 font-mono mt-0.5">
                                        <span class="text-slate-300">V-{{ $socio->cedula }}</span>
                                        <span class="text-slate-600">&bull;</span>
                                        <span class="truncate max-w-[150px] text-slate-500" title="{{ $socio->correo }}">{{ $socio->correo }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-3.5 py-3.5 text-slate-300 font-mono text-[11px] whitespace-nowrap">
                            <div class="flex items-center space-x-1.5">
                                <span class="material-icons-round text-xs text-slate-500">call</span>
                                <span>{{ $socio->numero ?? 'Sin número' }}</span>
                            </div>
                        </td>

                        <td class="px-3.5 py-3.5 whitespace-nowrap">
                            @if($socio->estado === 'activo')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                                    Activo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-500/10 text-rose-400 border border-rose-500/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400 mr-1.5"></span>
                                    Inactivo
                                </span>
                            @endif
                        </td>

                        <td class="px-3.5 py-3.5 whitespace-nowrap">
                            @if($socio->saldo < 0)
                                <div class="flex items-baseline space-x-1.5">
                                    <span class="text-rose-400 font-black font-mono text-xs">
                                        ${{ number_format($socio->saldo, 2) }}
                                    </span>
                                    <span class="px-1.5 py-0.5 rounded bg-rose-500/10 text-rose-300 border border-rose-500/20 text-[9px] font-bold">
                                        {{ $socio->meses_deuda }} {{ $socio->meses_deuda == 1 ? 'mes' : 'meses' }}
                                    </span>
                                </div>
                            @else
                                <div class="flex items-baseline space-x-1.5">
                                    <span class="text-emerald-400 font-black font-mono text-xs">
                                        ${{ number_format($socio->saldo, 2) }}
                                    </span>
                                    <span class="text-[10px] text-emerald-400 font-semibold font-mono">Solvente</span>
                                </div>
                            @endif
                        </td>

                        <td class="px-4 py-3.5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center space-x-1.5">
                                <a href="{{ route('socios.show', $socio) }}" 
                                   title="Ver Ficha y Estadísticas"
                                   class="inline-flex items-center px-2.5 py-1.5 rounded-xl bg-[#141C30] hover:bg-[#1E293B] border border-[#243352] text-slate-200 hover:text-white font-semibold text-xs transition active:scale-95 shadow-sm">
                                    <span class="material-icons-round text-xs mr-1 text-amber-400">visibility</span>
                                    <span>Ficha</span>
                                </a>

                                <button type="button" 
                                        onclick="abrirCarnetRapido({{ $socio->id }}, {{ json_encode($socio->nombre_completo) }}, '{{ $socio->cedula }}', '{{ $socio->accion }}', '{{ $socio->avatar_url }}', '{{ $socio->socio_desde ? $socio->socio_desde->format('m/Y') : date('m/Y') }}')" 
                                        title="Ver e Imprimir Carnet con QR"
                                        class="inline-flex items-center px-2.5 py-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-300 font-semibold text-xs transition active:scale-95 shadow-sm cursor-pointer">
                                    <span class="material-icons-round text-xs mr-1 text-amber-400">qr_code</span>
                                    <span>Carnet</span>
                                </button>

                                <a href="{{ route('pagos.create', ['cedula' => $socio->cedula]) }}" 
                                   title="Cobrar Cuota en Caja"
                                   class="inline-flex items-center px-3 py-1.5 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-500 hover:to-emerald-600 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition active:scale-95">
                                    <span class="material-icons-round text-xs mr-1">payments</span>
                                    <span>Cobrar</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500 font-medium">No se encontraron socios con los filtros aplicados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-2 border-t border-[#1B253B]">
            {{ $socios->links() }}
        </div>
    </div>

</div>

<!-- MODAL: CARNET DIGITAL OFICIAL DEL SOCIO -->
<div id="modalCarnet" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-sm rounded-3xl p-6 border border-[#243352] shadow-2xl space-y-4 bg-[#0C1222] relative">
        <div class="flex items-center justify-between border-b border-[#1F293D] pb-3">
            <h3 class="text-sm font-bold text-white flex items-center space-x-1.5">
                <span class="material-icons-round text-amber-400 text-base">badge</span>
                <span>Carnet Digital Oficial</span>
            </h3>
            <button type="button" onclick="cerrarModalCarnet()" class="text-slate-400 hover:text-white">
                <span class="material-icons-round">close</span>
            </button>
        </div>

        <div id="carnetPrintArea" class="rounded-2xl p-5 bg-gradient-to-br from-[#111A2E] via-[#0E1526] to-[#162238] border border-amber-500/30 text-center space-y-4 shadow-xl">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <div class="text-left">
                    <h4 class="text-xs font-extrabold text-white uppercase tracking-wider">Country Club</h4>
                    <span class="text-[9px] text-amber-400 font-mono block">Miembro Oficial</span>
                </div>
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-6 w-auto object-contain">
            </div>

            <div class="flex flex-col items-center space-y-2">
                <img id="carnetAvatar" src="" alt="Avatar" class="w-16 h-16 rounded-2xl border-2 border-amber-400/50 object-cover shadow-lg">
                <h5 id="carnetNombre" class="text-sm font-bold text-white"></h5>
                <span id="carnetAccion" class="px-3 py-0.5 rounded-full bg-amber-500/15 border border-amber-500/30 text-amber-300 font-mono font-bold text-xs"></span>
                <span id="carnetCedula" class="text-xs font-mono text-slate-400"></span>
            </div>

            <div class="carnet-qr-box p-3.5 rounded-2xl bg-[#060911] border border-amber-500/30 flex flex-col items-center justify-center space-y-1.5 shadow-inner">
                <div id="qrcodeCarnet" class="flex items-center justify-center p-1 rounded-xl bg-[#060911]"></div>
                <span class="carnet-qr-label text-[9px] font-mono text-amber-300 font-bold uppercase tracking-wider">Código de Acceso Garita</span>
            </div>

            <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1 border-t border-white/10">
                <span id="carnetDesde"></span>
                <span class="font-mono text-emerald-400 font-bold">SOLVENTE</span>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-2">
            <button type="button" onclick="cerrarModalCarnet()" class="px-4 py-2 rounded-xl bg-[#141C30] hover:bg-[#1E293B] text-slate-300 text-xs font-semibold transition border border-[#243352]">
                Cerrar
            </button>
            <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs flex items-center space-x-1 transition shadow-lg shadow-amber-500/10">
                <span class="material-icons-round text-sm">print</span>
                <span>Imprimir</span>
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    body * { visibility: hidden; }
    #modalCarnet, #modalCarnet * { visibility: visible; }
    #modalCarnet { position: absolute; left: 0; top: 0; width: 100%; background: transparent !important; }
    .carnet-qr-box { background: #FFFFFF !important; border-color: #CBD5E1 !important; }
    #qrcodeCarnet img, #qrcodeCarnet canvas { filter: invert(1) contrast(1.5) !important; }
    .carnet-qr-label { color: #0F172A !important; }
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/qrcode.min.js') }}"></script>
<script>
    let qrCarnetInstance = null;

    function abrirCarnetRapido(id, nombre, cedula, accion, avatarUrl, desde) {
        document.getElementById('carnetAvatar').src = avatarUrl;
        document.getElementById('carnetNombre').textContent = nombre;
        document.getElementById('carnetAccion').textContent = accion;
        document.getElementById('carnetCedula').textContent = 'Cédula: V-' + cedula;
        document.getElementById('carnetDesde').textContent = 'Desde: ' + desde;

        const qrContainer = document.getElementById('qrcodeCarnet');
        qrContainer.innerHTML = '';
        qrCarnetInstance = new QRCode(qrContainer, {
            text: JSON.stringify({
                club: "COUNTRY_CLUB",
                id: id,
                cedula: cedula,
                accion: accion
            }),
            width: 130,
            height: 130,
            colorDark: "#FBBF24",
            colorLight: "#060911",
            correctLevel: QRCode.CorrectLevel.M
        });

        document.getElementById('modalCarnet').classList.remove('hidden');
    }

    function cerrarModalCarnet() {
        document.getElementById('modalCarnet').classList.add('hidden');
    }
</script>
@endpush
@endsection
