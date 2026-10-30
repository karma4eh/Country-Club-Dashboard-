@extends('layouts.app')

@section('title', 'Garita & Control de Visitas')
@section('header_title', 'Garita de Vigilancia & Control de Acceso')
@section('header_subtitle', 'Registro de visitas mediante Código QR, cédula o acción con control mensual')

@section('content')
<div class="space-y-7">

    <!-- Tarjetas de Métrica de Visitas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Ingresos de Hoy</span>
                <h4 class="text-3xl font-extrabold text-emerald-400 mt-0.5 font-mono">{{ $visitasHoy }}</h4>
                <p class="text-[10px] text-emerald-400/80 mt-1 flex items-center">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                    Garita en servicio
                </p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                <span class="material-icons-round text-2xl">sensor_door</span>
            </div>
        </div>

        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Visitas en {{ $fechaSeleccionada->translatedFormat('F Y') }}</span>
                <h4 class="text-3xl font-extrabold text-amber-300 mt-0.5 font-mono">{{ $totalVisitasMes }}</h4>
                <p class="text-[10px] text-slate-400 mt-1">Registros en el mes</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                <span class="material-icons-round text-2xl">event_available</span>
            </div>
        </div>

        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Acompañantes / Invitados</span>
                <h4 class="text-3xl font-extrabold text-blue-400 mt-0.5 font-mono">{{ $totalInvitadosMes }}</h4>
                <p class="text-[10px] text-slate-400 mt-1">Personas acompañantes</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-blue-500/10 text-blue-400 flex items-center justify-center">
                <span class="material-icons-round text-2xl">group_add</span>
            </div>
        </div>

        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Socios Titulares Únicos</span>
                <h4 class="text-3xl font-extrabold text-purple-400 mt-0.5 font-mono">{{ $sociosUnicosMes }}</h4>
                <p class="text-[10px] text-slate-400 mt-1">Socios distintos asistieron</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-purple-500/10 text-purple-400 flex items-center justify-center">
                <span class="material-icons-round text-2xl">badge</span>
            </div>
        </div>
    </div>

    <!-- TERMINAL DE ACCESO EN GARITA (QR & BÚSQUEDA) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-7">

        <!-- Formulario de Búsqueda y Validación (7 Cols) -->
        <div class="lg:col-span-7 glass-panel rounded-3xl p-7 border border-[#1B253B] shadow-2xl space-y-6">
            <div class="flex items-center justify-between border-b border-[#1F293D] pb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center">
                        <span class="material-icons-round text-xl">qr_code_scanner</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white">Terminal de Control de Acceso</h3>
                        <p class="text-[11px] text-slate-400">Escanee el carnet QR del socio o busque por cédula / acción</p>
                    </div>
                </div>

                <button type="button" id="btnToggleCamera" onclick="toggleCameraScanner()" class="px-3.5 py-2 rounded-xl bg-[#141C30] hover:bg-[#1C2742] border border-[#243352] text-slate-200 text-xs font-semibold flex items-center space-x-1.5 transition">
                    <span class="material-icons-round text-sm text-amber-400">photo_camera</span>
                    <span id="txtCameraBtn">Activar Cámara QR</span>
                </button>
            </div>

            <!-- Visor de Cámara QR (Oculto inicialmente) -->
            <div id="cameraContainer" class="hidden rounded-2xl overflow-hidden border border-amber-500/40 p-4 bg-[#0A0E1A] space-y-3">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-amber-300 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse mr-2"></span>
                        Cámara activa - Apunte al código QR del carnet
                    </span>
                    <button type="button" onclick="stopCameraScanner()" class="text-slate-400 hover:text-white">Cerrar cámara</button>
                </div>
                <div id="reader" class="w-full max-w-sm mx-auto rounded-xl overflow-hidden"></div>
            </div>

            <!-- Input Principal de Búsqueda / Lector Barcode -->
            <form onsubmit="buscarSocioManual(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Código QR / Cédula / Número de Acción <span class="text-amber-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-icons-round absolute left-4 top-3.5 text-slate-400 text-lg">search</span>
                        <input type="text" id="inputBusquedaSocio" placeholder="Escanee con lector o escriba cédula / ACC-001..." autofocus
                               class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl pl-12 pr-28 py-3.5 text-sm text-white font-mono placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition">
                        <button type="submit" class="absolute right-2 top-2 px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs transition">
                            Validar
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1.5">
                        💡 Compatible con pistolas lectoras 2D/QR de garita, teclado o escaneo por webcam.
                    </p>
                </div>
            </form>

            <!-- Spinner de carga -->
            <div id="loaderSocio" class="hidden py-6 text-center text-slate-400 text-xs">
                <span class="material-icons-round animate-spin text-2xl text-amber-400 block mb-1">sync</span>
                <span>Consultando estado del socio en tiempo real...</span>
            </div>

            <!-- Tarjeta de Validación del Socio Encontrado -->
            <div id="socioCardValidation" class="hidden rounded-2xl p-6 border transition-all duration-300 space-y-5">
                <!-- Se llena dinámicamente con JS -->
            </div>

        </div>

        <!-- Columna Derecha: Resumen de Control Mensual & Calendario (5 Cols) -->
        <div class="lg:col-span-5 glass-panel rounded-3xl p-7 border border-[#1B253B] shadow-2xl space-y-6 flex flex-col justify-between">
            <div class="space-y-5">
                <div class="flex items-center justify-between border-b border-[#1F293D] pb-4">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center">
                            <span class="material-icons-round text-base">calendar_month</span>
                        </div>
                        <h3 class="text-sm font-bold text-white">Control Mensual de Asistencia</h3>
                    </div>

                    <!-- Selector de Mes Interactivo -->
                    <div class="flex items-center space-x-1">
                        <a href="{{ route('visitas.index', ['year' => $mesAnterior->year, 'month' => $mesAnterior->month]) }}" 
                           class="w-7 h-7 rounded-lg bg-[#141C30] hover:bg-[#1E293B] text-slate-300 flex items-center justify-center transition border border-[#243352]" title="Mes Anterior">
                            <span class="material-icons-round text-xs">chevron_left</span>
                        </a>

                        <span class="px-2.5 py-1 rounded-lg bg-[#0A0F1E] border border-[#243352] text-xs font-mono font-bold text-amber-300 capitalize">
                            {{ $fechaSeleccionada->translatedFormat('M Y') }}
                        </span>

                        <a href="{{ route('visitas.index', ['year' => $mesSiguiente->year, 'month' => $mesSiguiente->month]) }}" 
                           class="w-7 h-7 rounded-lg bg-[#141C30] hover:bg-[#1E293B] text-slate-300 flex items-center justify-center transition border border-[#243352]" title="Siguiente Mes">
                            <span class="material-icons-round text-xs">chevron_right</span>
                        </a>
                    </div>
                </div>

                <!-- Resumen Mensual de la Garita -->
                <div class="p-4 rounded-2xl bg-[#0A0F1E] border border-[#243352]/50 space-y-2 text-xs">
                    <div class="flex justify-between items-center text-slate-300">
                        <span class="text-slate-400">Total asistencias en {{ $fechaSeleccionada->translatedFormat('F') }}:</span>
                        <strong class="font-mono text-emerald-400 text-sm">{{ $totalVisitasMes }} visitas</strong>
                    </div>
                    <div class="flex justify-between items-center text-slate-300">
                        <span class="text-slate-400">Invitados / Acompañantes recibidos:</span>
                        <strong class="font-mono text-blue-400 text-sm">{{ $totalInvitadosMes }} personas</strong>
                    </div>
                    <div class="flex justify-between items-center text-slate-300">
                        <span class="text-slate-400">Promedio diario de asistencia:</span>
                        <strong class="font-mono text-purple-400">
                            {{ round($totalVisitasMes / max(1, $fechaSeleccionada->daysInMonth), 1) }} visitas/día
                        </strong>
                    </div>
                </div>

                <!-- Calendario Visual / Heatmap de Días con Asistencia -->
                <div class="space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">
                        Mapa Diario de Actividad ({{ $fechaSeleccionada->translatedFormat('F Y') }})
                    </span>

                    <div class="grid grid-cols-7 gap-1.5 p-3 rounded-2xl bg-[#0A0F1E] border border-[#243352]/40 text-center">
                        @php
                            $diasTotal = $fechaSeleccionada->daysInMonth;
                        @endphp
                        @for($d = 1; $d <= $diasTotal; $d++)
                            @php
                                $visitasDia = $diasConVisitas[$d] ?? 0;
                            @endphp
                            <div class="p-1.5 rounded-lg border text-[10px] font-mono transition-all {{ $visitasDia > 0 ? 'bg-emerald-500/20 border-emerald-500/40 text-emerald-300 font-bold' : 'bg-[#141C30]/40 border-[#243352]/30 text-slate-500' }}"
                                 title="Día {{ $d }}: {{ $visitasDia }} visitas">
                                <span>{{ $d }}</span>
                                @if($visitasDia > 0)
                                    <span class="block text-[8px] text-amber-300 leading-none mt-0.5">{{ $visitasDia }}</span>
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Información Operativa -->
            <div class="p-4 rounded-2xl bg-[#10172A] border border-[#1E293B] text-[11px] text-slate-400 space-y-1">
                <span class="font-bold text-slate-200 block">Oficial de Guardia en Garita:</span>
                <p>{{ Auth::user()->nombre_completo ?? 'Personal de Seguridad' }} ({{ strtoupper(Auth::user()->rol ?? 'Vigilante') }})</p>
            </div>
        </div>

    </div>

    <!-- TABLA DE HISTORIAL DE VISITAS DEL MES SELECCIONADO -->
    <div class="glass-panel rounded-3xl p-7 border border-[#1B253B] shadow-2xl space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#1F293D] pb-5">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                    <span class="material-icons-round text-lg">history</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white">Registro de Visitas - {{ $fechaSeleccionada->translatedFormat('F Y') }}</h3>
                    <p class="text-[11px] text-slate-400">Trazabilidad de accesos autorizados por garita principal</p>
                </div>
            </div>

            <!-- Selector de Mes Superior para la Tabla -->
            <div class="flex items-center space-x-2 text-xs">
                <span class="text-slate-400">Ver mes:</span>
                <a href="{{ route('visitas.index', ['year' => $mesAnterior->year, 'month' => $mesAnterior->month]) }}" class="px-3 py-1.5 rounded-xl bg-[#141C30] hover:bg-[#1E293B] border border-[#243352] text-slate-300 font-semibold transition">
                    &larr; {{ $mesAnterior->translatedFormat('M') }}
                </a>
                <span class="px-3 py-1.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-300 font-bold font-mono">
                    {{ $fechaSeleccionada->translatedFormat('F Y') }}
                </span>
                <a href="{{ route('visitas.index', ['year' => $mesSiguiente->year, 'month' => $mesSiguiente->month]) }}" class="px-3 py-1.5 rounded-xl bg-[#141C30] hover:bg-[#1E293B] border border-[#243352] text-slate-300 font-semibold transition">
                    {{ $mesSiguiente->translatedFormat('M') }} &rarr;
                </a>
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-[#1B253B]">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0B101D] text-slate-400 font-bold uppercase tracking-wider border-b border-[#1B253B]">
                    <tr>
                        <th class="px-6 py-4">Fecha & Hora</th>
                        <th class="px-6 py-4">Socio Titular</th>
                        <th class="px-6 py-4">Acción / Cédula</th>
                        <th class="px-6 py-4">Invitados</th>
                        <th class="px-6 py-4">Vigilante / Garita</th>
                        <th class="px-6 py-4">Observaciones</th>
                        <th class="px-6 py-4 text-right">Estatus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1B253B] bg-[#0E1526]/50">
                    @forelse($visitas as $v)
                    <tr class="hover:bg-[#162238]/60 transition-colors">
                        <td class="px-6 py-4 text-slate-300 whitespace-nowrap font-mono text-[11px]">
                            <span class="text-white font-semibold">{{ $v->fecha_entrada ? $v->fecha_entrada->format('d/m/Y') : '' }}</span>
                            <span class="text-amber-400 ml-1 font-bold">{{ $v->fecha_entrada ? $v->fecha_entrada->format('h:i A') : '' }}</span>
                        </td>

                        <td class="px-6 py-4">
                            @if($v->socio)
                            <div class="flex items-center space-x-2.5">
                                <img src="{{ $v->socio->avatar_url }}" alt="avatar" class="w-7 h-7 rounded-lg border border-slate-700 object-cover">
                                <a href="{{ route('socios.show', $v->socio) }}" class="font-bold text-slate-100 hover:text-amber-400 transition block truncate max-w-[170px]">
                                    {{ $v->socio->nombre_completo }}
                                </a>
                            </div>
                            @else
                                <span class="text-slate-500 italic">Socio no disponible</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 font-mono text-xs text-amber-300 whitespace-nowrap">
                            <span class="font-bold">{{ $v->socio->accion ?? 'N/A' }}</span>
                            <span class="text-slate-500 block text-[10px]">V-{{ $v->socio->cedula ?? '' }}</span>
                        </td>

                        <td class="px-6 py-4 font-mono text-xs text-slate-200">
                            @if($v->invitados > 0)
                                <span class="px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20 font-bold">
                                    +{{ $v->invitados }} pers.
                                </span>
                            @else
                                <span class="text-slate-500">Solo titular</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-slate-300 text-xs">
                            <span class="text-slate-200 font-medium">{{ $v->vigilante->nombre_completo ?? 'Personal de Seguridad' }}</span>
                        </td>

                        <td class="px-6 py-4 text-slate-400 text-xs truncate max-w-[200px]">
                            {{ $v->observaciones ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            <span class="inline-flex items-center space-x-1 text-[10px] text-emerald-400 font-mono font-semibold">
                                <span class="material-icons-round text-xs">check_circle</span>
                                <span>Ingreso Registrado</span>
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            <span class="material-icons-round text-4xl text-slate-600 block mb-2">no_meeting_room</span>
                            <span>No se registraron visitas ni ingresos en {{ $fechaSeleccionada->translatedFormat('F de Y') }}.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-2">
            {{ $visitas->links() }}
        </div>
    </div>

</div>

@push('scripts')
<!-- Scripts locales para QR y Escáner -->
<script src="{{ asset('js/qrcode.min.js') }}"></script>
<script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
<script>
    let html5QrcodeScanner = null;
    let cameraActive = false;

    // Búsqueda Manual / Lector de Barra
    function buscarSocioManual(e) {
        if (e) e.preventDefault();
        const query = document.getElementById('inputBusquedaSocio').value.trim();
        if (!query) return;

        consultarSocioGarita(query);
    }

    // Consulta AJAX al endpoint de garita
    function consultarSocioGarita(query) {
        const loader = document.getElementById('loaderSocio');
        const card = document.getElementById('socioCardValidation');
        loader.classList.remove('hidden');
        card.classList.add('hidden');

        fetch(`{{ route('visitas.buscar') }}?query=${encodeURIComponent(query)}`)
            .then(res => {
                if (!res.ok) throw new Error('Socio no encontrado');
                return res.json();
            })
            .then(data => {
                loader.classList.add('hidden');
                renderTarjetaValidacion(data);
            })
            .catch(err => {
                loader.classList.add('hidden');
                card.className = "rounded-2xl p-6 border border-rose-500/40 bg-rose-500/10 text-rose-300 space-y-2";
                card.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <span class="material-icons-round text-rose-400 text-xl">error_outline</span>
                        <h4 class="font-bold text-sm">Socio no encontrado o código inválido</h4>
                    </div>
                    <p class="text-xs text-slate-300">No se encontró ningún registro para el valor ingresado ("${query}"). Verifique la cédula o acción.</p>
                `;
                card.classList.remove('hidden');
            });
    }

    // Render de la tarjeta de validación de acceso en garita
    function renderTarjetaValidacion(s) {
        const card = document.getElementById('socioCardValidation');
        
        let borderClass = "border-emerald-500/40 bg-[#0E1526]";
        let badgeEstado = `<span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center space-x-1">
            <span class="w-2 h-2 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
            AUTORIZADO • ACTIVO
        </span>`;

        if (!s.activo) {
            borderClass = "border-rose-500/50 bg-[#160D14]";
            badgeEstado = `<span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-rose-500/10 text-rose-400 border border-rose-500/30 flex items-center space-x-1">
                <span class="w-2 h-2 rounded-full bg-rose-400 mr-1.5"></span>
                ACCESO DENEGADO • INACTIVO
            </span>`;
        } else if (s.es_moroso) {
            borderClass = "border-amber-500/50 bg-[#17140B]";
            badgeEstado = `<span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-amber-500/10 text-amber-300 border border-amber-500/30 flex items-center space-x-1">
                <span class="w-2 h-2 rounded-full bg-amber-400 mr-1.5"></span>
                ADVERTENCIA • EN MORA (${s.meses_deuda} mes${s.meses_deuda > 1 ? 'es' : ''})
            </span>`;
        }

        card.className = `rounded-3xl p-6 border ${borderClass} space-y-5 shadow-2xl`;
        card.innerHTML = `
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <img src="${s.avatar_url}" alt="Avatar" class="w-14 h-14 rounded-2xl border-2 border-amber-400/40 object-cover shadow-lg">
                    <div>
                        <h4 class="text-base font-bold text-white flex items-center space-x-2">
                            <span>${s.nombre_completo}</span>
                            <span class="px-2 py-0.5 rounded text-xs font-mono font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30">${s.accion}</span>
                        </h4>
                        <p class="text-xs text-slate-400 font-mono">Cédula: V-${s.cedula}</p>
                    </div>
                </div>
                <div>${badgeEstado}</div>
            </div>

            <!-- Métricas del socio para garita -->
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="p-3 rounded-xl bg-[#0A0F1E] border border-[#243352]/50">
                    <span class="text-slate-500 text-[10px] uppercase font-bold block">Visitas este mes</span>
                    <strong class="text-emerald-400 text-sm font-mono">${s.visitas_este_mes} visitas</strong>
                </div>
                <div class="p-3 rounded-xl bg-[#0A0F1E] border border-[#243352]/50">
                    <span class="text-slate-500 text-[10px] uppercase font-bold block">Último ingreso</span>
                    <strong class="text-slate-200 text-xs">${s.ultima_visita}</strong>
                </div>
            </div>

            <!-- Formulario de Confirmación de Ingreso -->
            <form onsubmit="registrarVisitaConfirmada(event, ${s.id})" class="space-y-4 pt-2 border-t border-[#1F293D]">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-300 text-xs font-semibold mb-1">Acompañantes / Invitados</label>
                        <input type="number" id="visitaInvitados" min="0" max="20" value="0"
                               class="w-full bg-[#0A0F1E] border border-[#243352] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-amber-400">
                    </div>
                    <div>
                        <label class="block text-slate-300 text-xs font-semibold mb-1">Observaciones / Placa vehículo</label>
                        <input type="text" id="visitaObservaciones" placeholder="Ej: Vehículo Ford Placa ABC-123"
                               class="w-full bg-[#0A0F1E] border border-[#243352] rounded-xl px-3 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400">
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-2">
                    <button type="button" onclick="document.getElementById('socioCardValidation').classList.add('hidden')" class="px-4 py-2.5 rounded-xl bg-[#141C30] hover:bg-[#1E293B] text-slate-300 text-xs font-semibold transition border border-[#243352]">
                        Cancelar
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 flex items-center space-x-1.5 transition active:scale-[0.98]">
                        <span class="material-icons-round text-base">check_circle</span>
                        <span>Autorizar e Ingresar al Club</span>
                    </button>
                </div>
            </form>
        `;
        card.classList.remove('hidden');
    }

    // Confirmación y guardado del ingreso
    function registrarVisitaConfirmada(e, socioId) {
        e.preventDefault();
        const invitados = document.getElementById('visitaInvitados').value;
        const observaciones = document.getElementById('visitaObservaciones').value;

        fetch(`{{ route('visitas.store') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                socios_id: socioId,
                invitados: parseInt(invitados) || 0,
                observaciones: observaciones
            })
        })
        .then(res => res.json())
        .then(data => {
            alert('✅ ' + data.mensaje);
            window.location.reload();
        })
        .catch(err => {
            alert('Error al registrar la visita.');
        });
    }

    // Control de Cámara QR con Html5Qrcode
    function toggleCameraScanner() {
        if (cameraActive) {
            stopCameraScanner();
        } else {
            startCameraScanner();
        }
    }

    function startCameraScanner() {
        const container = document.getElementById('cameraContainer');
        const btnText = document.getElementById('txtCameraBtn');
        container.classList.remove('hidden');
        btnText.textContent = "Detener Cámara";
        cameraActive = true;

        html5QrcodeScanner = new Html5Qrcode("reader");
        html5QrcodeScanner.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            (decodedText, decodedResult) => {
                // Código escaneado con éxito
                stopCameraScanner();
                document.getElementById('inputBusquedaSocio').value = decodedText;
                consultarSocioGarita(decodedText);
            },
            (errorMessage) => {
                // Frame sin QR (normal)
            }
        ).catch(err => {
            alert("No se pudo iniciar la cámara: " + err);
            stopCameraScanner();
        });
    }

    function stopCameraScanner() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().then(() => {
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
            }).catch(() => {});
        }
        document.getElementById('cameraContainer').classList.add('hidden');
        document.getElementById('txtCameraBtn').textContent = "Activar Cámara QR";
        cameraActive = false;
    }
</script>
@endpush
@endsection
