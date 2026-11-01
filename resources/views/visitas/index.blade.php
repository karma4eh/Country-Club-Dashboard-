@extends('layouts.app')

@section('title', 'Garita & Control de Visitas')
@section('header_title', 'Garita de Vigilancia & Control de Acceso')
@section('header_subtitle', 'Control biométrico y digital de acceso, aforo en tiempo real y libro de novedades')

@section('content')
<div class="space-y-7">

    <!-- BANNER DE TURNO DEL OFICIAL DE GUARDIA -->
    <div class="glass-panel rounded-3xl p-6 border border-[#243352] shadow-2xl relative overflow-hidden bg-gradient-to-r from-[#0C1424] via-[#101B30] to-[#0D1527]">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-5 relative z-10">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400/20 to-amber-600/30 border border-amber-400/40 text-amber-300 flex items-center justify-center shadow-lg shadow-amber-500/10">
                        <span class="material-icons-round text-3xl">security</span>
                    </div>
                    <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-[#0B101D] flex items-center justify-center" title="Oficial en línea">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                    </span>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-[10px] font-extrabold uppercase tracking-wider flex items-center space-x-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1 animate-pulse"></span>
                            Guardia Activa • Garita Principal
                        </span>
                        <span class="text-slate-500 text-xs">•</span>
                        <span class="text-slate-400 text-xs font-mono">{{ Carbon\Carbon::now()->translatedFormat('l, d \d\e F Y') }}</span>
                    </div>
                    <h2 class="text-xl font-extrabold text-white mt-1">
                        Oficial Responsable: <span class="text-amber-300">{{ Auth::user()->nombre_completo ?? 'Personal de Seguridad' }}</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Identificador de Puesto: <span class="font-mono text-slate-300">PUESTO-GARITA-01</span> | Todas las lecturas e ingresos quedan auditados con su firma electrónica.
                    </p>
                </div>
            </div>

            <!-- Botones de Acción de Guardia -->
            <div class="flex items-center space-x-3 w-full md:w-auto">
                <button type="button" onclick="abrirModalNovedad()" class="flex-1 md:flex-none px-4 py-2.5 rounded-2xl bg-[#141F36] hover:bg-[#1B2A4A] border border-[#2A3F66] text-amber-300 text-xs font-bold flex items-center justify-center space-x-2 transition shadow-lg shadow-black/30">
                    <span class="material-icons-round text-base">edit_note</span>
                    <span>Libro de Novedades</span>
                </button>
                <a href="#seccionAforo" class="flex-1 md:flex-none px-4 py-2.5 rounded-2xl bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-500/30 text-emerald-300 text-xs font-bold flex items-center justify-center space-x-2 transition">
                    <span class="material-icons-round text-base">groups</span>
                    <span>Aforo: {{ $totalAforoHoy }} pers.</span>
                </a>
            </div>
        </div>
    </div>

    <!-- TARJETAS DE MÉTRICAS OPERATIVAS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Aforo en Tiempo Real -->
        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">En el Club Ahora (Aforo)</span>
                <h4 class="text-3xl font-extrabold text-emerald-400 mt-0.5 font-mono">{{ $totalAforoHoy }}</h4>
                <p class="text-[10px] text-slate-400 mt-1">
                    <span class="text-emerald-300 font-bold font-mono">{{ $totalSociosDentro }}</span> socios + <span class="text-blue-300 font-bold font-mono">{{ max(0, $totalAforoHoy - $totalSociosDentro) }}</span> invit.
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center">
                <span class="material-icons-round text-2xl">sensor_door</span>
            </div>
        </div>

        <!-- Ingresos Registrados Hoy -->
        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total Ingresos Hoy</span>
                <h4 class="text-3xl font-extrabold text-amber-300 mt-0.5 font-mono">{{ $visitasHoy }}</h4>
                <p class="text-[10px] text-slate-400 mt-1">Entradas validadas en garita</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center">
                <span class="material-icons-round text-2xl">how_to_reg</span>
            </div>
        </div>

        <!-- Visitas del Mes Seleccionado -->
        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Mes {{ $fechaSeleccionada->translatedFormat('F Y') }}</span>
                <h4 class="text-3xl font-extrabold text-blue-400 mt-0.5 font-mono">{{ $totalVisitasMes }}</h4>
                <p class="text-[10px] text-slate-400 mt-1">{{ $totalInvitadosMes }} invitados registrados</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center">
                <span class="material-icons-round text-2xl">event_available</span>
            </div>
        </div>

        <!-- Socios Únicos en el Mes -->
        <div class="glass-panel rounded-2xl p-5 border border-[#1B253B] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Socios Titulares Activos</span>
                <h4 class="text-3xl font-extrabold text-purple-400 mt-0.5 font-mono">{{ $sociosUnicosMes }}</h4>
                <p class="text-[10px] text-slate-400 mt-1">Socios distintos este mes</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center">
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
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center border border-amber-500/20">
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
                        Cámara activa - Apunte al código QR del carnet del socio
                    </span>
                    <button type="button" onclick="stopCameraScanner()" class="text-slate-400 hover:text-white flex items-center space-x-1">
                        <span class="material-icons-round text-sm">close</span>
                        <span>Cerrar</span>
                    </button>
                </div>
                <div id="reader" class="w-full max-w-sm mx-auto rounded-xl overflow-hidden"></div>
            </div>

            <!-- Input Principal de Búsqueda / Lector Barcode -->
            <form onsubmit="buscarSocioManual(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Código QR / Cédula de Identidad / Número de Acción <span class="text-amber-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-icons-round absolute left-4 top-3.5 text-slate-400 text-lg">search</span>
                        <input type="text" id="inputBusquedaSocio" placeholder="Escanee carnet QR o escriba cédula / N° de Acción (ej: 9972) / Nombre..." autofocus
                               class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl pl-12 pr-28 py-3.5 text-sm text-white font-mono placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition">
                        <button type="submit" class="absolute right-2 top-2 px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs transition shadow-md">
                            Validar
                        </button>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400 mt-2">
                        <span>💡 Compatible con pistolas lectoras 2D/QR de garita, teclado o escaneo por webcam.</span>
                        <span class="text-amber-300/80 font-mono">Oficial: {{ Auth::user()->nombre_completo ?? 'José Gregorio Colmenares' }}</span>
                    </div>
                </div>
            </form>

            <!-- Spinner de carga -->
            <div id="loaderSocio" class="hidden py-6 text-center text-slate-400 text-xs">
                <span class="material-icons-round animate-spin text-2xl text-amber-400 block mb-1">sync</span>
                <span>Consultando estado del socio y solvencia en tiempo real...</span>
            </div>

            <!-- Tarjeta Dinámica de Validación del Socio Encontrado -->
            <div id="socioCardValidation" class="hidden rounded-3xl p-6 border transition-all duration-300 space-y-5">
                <!-- Se llena dinámicamente con JS -->
            </div>

        </div>

        <!-- Columna Derecha: Resumen de Control Mensual & Calendario (5 Cols) -->
        <div class="lg:col-span-5 glass-panel rounded-3xl p-7 border border-[#1B253B] shadow-2xl space-y-6 flex flex-col justify-between">
            <div class="space-y-5">
                <div class="flex items-center justify-between border-b border-[#1F293D] pb-4">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center border border-purple-500/20">
                            <span class="material-icons-round text-base">calendar_month</span>
                        </div>
                        <h3 class="text-sm font-bold text-white">Control Mensual de Visitas</h3>
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

            <!-- Ficha del Vigilante Responsable -->
            <div class="p-4 rounded-2xl bg-[#10172A] border border-[#1E293B] text-xs text-slate-300 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-300 flex items-center justify-center">
                        <span class="material-icons-round text-base">badge</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Oficial de Garita</span>
                        <p class="font-extrabold text-white text-xs">{{ Auth::user()->nombre_completo ?? 'Personal de Seguridad' }}</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    TURNO ACTIVO
                </span>
            </div>
        </div>

    </div>

    <!-- SECCIÓN DE AFORO EN VIVO: PERSONAS ACTUALMENTE DENTRO DEL CLUB -->
    <div id="seccionAforo" class="glass-panel rounded-3xl p-7 border border-[#1B253B] shadow-2xl space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#1F293D] pb-5">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                    <span class="material-icons-round text-xl">meeting_room</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white flex items-center space-x-2">
                        <span>Aforo en Tiempo Real (Permanencia en Instalaciones)</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            {{ $totalAforoHoy }} personas dentro
                        </span>
                    </h3>
                    <p class="text-[11px] text-slate-400">Socios e invitados que ingresaron hoy y no han marcado salida</p>
                </div>
            </div>

            <span class="text-xs text-slate-400 flex items-center space-x-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Actualización automática en garita</span>
            </span>
        </div>

        @if($visitasActivasHoy->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($visitasActivasHoy as $act)
            <div class="p-4 rounded-2xl bg-[#0B101D] border border-[#243352] hover:border-amber-400/50 transition-all space-y-3 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $act->socio->avatar_url }}" alt="avatar" class="w-10 h-10 rounded-xl border border-slate-700 object-cover">
                        <div>
                            <h4 class="font-bold text-white text-xs truncate max-w-[150px]">{{ $act->socio->nombre_completo }}</h4>
                            <span class="text-[10px] font-mono text-amber-300 font-bold">{{ $act->socio->accion }}</span>
                            <span class="text-[10px] text-slate-500 font-mono ml-1">V-{{ $act->socio->cedula }}</span>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-mono font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                        DENTRO
                    </span>
                </div>

                <div class="p-2.5 rounded-xl bg-[#070B14] border border-[#1E293B] space-y-1 text-[11px]">
                    <div class="flex justify-between text-slate-400">
                        <span>Ingreso hoy:</span>
                        <strong class="text-slate-200 font-mono">{{ $act->fecha_entrada ? $act->fecha_entrada->format('h:i A') : '' }}</strong>
                    </div>
                    <div class="flex justify-between text-slate-400">
                        <span>Permanencia:</span>
                        <strong class="text-amber-300 font-mono">{{ $act->tiempo_permanencia }}</strong>
                    </div>
                    <div class="flex justify-between text-slate-400">
                        <span>Acompañantes:</span>
                        <strong class="text-blue-400 font-mono">+{{ $act->invitados }} pers.</strong>
                    </div>
                    @if($act->observaciones)
                    <div class="text-[10px] text-slate-400 pt-1 border-t border-[#162238] truncate" title="{{ $act->observaciones }}">
                        🚗 {{ $act->observaciones }}
                    </div>
                    @endif
                    <div class="text-[10px] text-slate-500 pt-1 border-t border-[#162238]">
                        🛡️ Ingresado por: <span class="text-slate-300 font-medium">{{ $act->vigilante->nombre_completo ?? 'Personal de Seguridad' }}</span>
                    </div>
                </div>

                <!-- Botón de Salida Rápida -->
                <form action="{{ route('visitas.salida', $act) }}" method="POST" onsubmit="return confirm('¿Registrar la salida para {{ $act->socio->nombre_completo }}?');">
                    @csrf
                    <button type="submit" class="w-full py-2 px-3 rounded-xl bg-[#141C30] hover:bg-rose-500/20 border border-[#243352] hover:border-rose-500/40 text-slate-300 hover:text-rose-300 text-xs font-bold transition flex items-center justify-center space-x-1.5 active:scale-95">
                        <span class="material-icons-round text-sm text-rose-400">logout</span>
                        <span>Marcar Salida</span>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-8 text-center rounded-2xl bg-[#0A0F1E] border border-[#243352]/40 text-slate-500 text-xs">
            <span class="material-icons-round text-3xl text-slate-600 block mb-1">night_shelter</span>
            <span>No hay socios registrados dentro del club en este momento. Todos los accesos previos han marcado salida.</span>
        </div>
        @endif
    </div>

    <!-- LIBRO DE NOVEDADES DE GUARDIA (MINUTA DE GARITA) -->
    <div class="glass-panel rounded-3xl p-7 border border-[#1B253B] shadow-2xl space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#1F293D] pb-5">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center border border-blue-500/20">
                    <span class="material-icons-round text-xl">menu_book</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white">Libro Digital de Novedades de Garita (Minuta)</h3>
                    <p class="text-[11px] text-slate-400">Bitácora oficial de incidentes, rondas perimetrales y novedades de seguridad</p>
                </div>
            </div>

            <button type="button" onclick="abrirModalNovedad()" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs transition flex items-center space-x-1.5 shadow-md">
                <span class="material-icons-round text-sm">add_circle</span>
                <span>Asentar Nueva Novedad</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($novedadesRecientes as $nov)
            <div class="p-4 rounded-2xl bg-[#0B101D] border border-[#243352] space-y-2.5">
                <div class="flex items-center justify-between text-xs">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold font-mono uppercase bg-amber-500/15 text-amber-300 border border-amber-500/30">
                        {{ $nov->tipo }}
                    </span>
                    <span class="text-[10px] font-mono text-slate-400">
                        {{ $nov->fecha ? $nov->fecha->format('d/m/Y h:i A') : '' }}
                    </span>
                </div>

                <h4 class="font-bold text-white text-xs">{{ $nov->titulo }}</h4>
                <p class="text-[11px] text-slate-300 leading-relaxed bg-[#070B14] p-3 rounded-xl border border-[#162238]">
                    {{ $nov->descripcion }}
                </p>

                <div class="flex items-center justify-between text-[10px] text-slate-500 pt-1">
                    <span class="flex items-center space-x-1">
                        <span class="material-icons-round text-xs text-amber-400">shield</span>
                        <span>Oficial: <strong class="text-slate-300 font-semibold">{{ $nov->vigilante->nombre_completo ?? 'Personal de Seguridad' }}</strong></span>
                    </span>
                </div>
            </div>
            @empty
            <div class="col-span-full p-8 text-center text-slate-500 text-xs">
                <span class="material-icons-round text-3xl text-slate-600 block mb-1">assignment</span>
                <span>No hay novedades registradas recientemente. Todas las novedades asentadas por el oficial aparecerán aquí con su firma digital.</span>
            </div>
            @endforelse
        </div>
    </div>

    <!-- TABLA DE HISTORIAL DE VISITAS DEL MES SELECCIONADO -->
    <div class="glass-panel rounded-3xl p-7 border border-[#1B253B] shadow-2xl space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#1F293D] pb-5">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center border border-amber-500/20">
                    <span class="material-icons-round text-lg">history</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white">Registro de Visitas - {{ $fechaSeleccionada->translatedFormat('F Y') }}</h3>
                    <p class="text-[11px] text-slate-400">Auditoría completa de ingresos con vigilante responsable y hora de salida</p>
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
                        <th class="px-6 py-4">Fecha & Entrada</th>
                        <th class="px-6 py-4">Salida / Permanencia</th>
                        <th class="px-6 py-4">Socio Titular</th>
                        <th class="px-6 py-4">Acción / Cédula</th>
                        <th class="px-6 py-4">Invitados</th>
                        <th class="px-6 py-4">Oficial Vigilante</th>
                        <th class="px-6 py-4">Observaciones</th>
                        <th class="px-6 py-4 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1B253B] bg-[#0E1526]/50">
                    @forelse($visitas as $v)
                    <tr class="hover:bg-[#162238]/60 transition-colors">
                        <td class="px-6 py-4 text-slate-300 whitespace-nowrap font-mono text-[11px]">
                            <span class="text-white font-semibold">{{ $v->fecha_entrada ? $v->fecha_entrada->format('d/m/Y') : '' }}</span>
                            <span class="text-emerald-400 ml-1 font-bold">{{ $v->fecha_entrada ? $v->fecha_entrada->format('h:i A') : '' }}</span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap font-mono text-[11px]">
                            @if($v->fecha_salida)
                                <span class="text-slate-300">{{ $v->fecha_salida->format('h:i A') }}</span>
                                <span class="text-[10px] text-amber-400 block font-bold">({{ $v->tiempo_permanencia }})</span>
                            @elseif($v->en_club)
                                <span class="px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 text-[9px] font-bold">
                                    EN CLUB ({{ $v->tiempo_permanencia }})
                                </span>
                            @else
                                <span class="text-slate-500 italic text-[10px]">Sin registro salida</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @if($v->socio)
                            <div class="flex items-center space-x-2.5">
                                <img src="{{ $v->socio->avatar_url }}" alt="avatar" class="w-7 h-7 rounded-lg border border-slate-700 object-cover">
                                @if(!Auth::user()->esVigilante())
                                <a href="{{ route('socios.show', $v->socio) }}" class="font-bold text-slate-100 hover:text-amber-400 transition block truncate max-w-[170px]">
                                    {{ $v->socio->nombre_completo }}
                                </a>
                                @else
                                <span class="font-bold text-slate-100 block truncate max-w-[170px]">
                                    {{ $v->socio->nombre_completo }}
                                </span>
                                @endif
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

                        <td class="px-6 py-4 text-xs whitespace-nowrap">
                            <div class="flex items-center space-x-1.5">
                                <span class="material-icons-round text-xs text-amber-400">shield</span>
                                <span class="text-slate-200 font-semibold">{{ $v->vigilante->nombre_completo ?? 'Personal de Seguridad' }}</span>
                            </div>
                            <span class="text-[10px] text-slate-500 block font-mono pl-4">Garita Principal</span>
                        </td>

                        <td class="px-6 py-4 text-slate-400 text-xs truncate max-w-[180px]" title="{{ $v->observaciones }}">
                            {{ $v->observaciones ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            @if(!$v->fecha_salida && $v->en_club)
                            <form action="{{ route('visitas.salida', $v) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Registrar salida?');">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-500/15 hover:bg-rose-500/25 border border-rose-500/30 text-rose-300 text-[10px] font-bold transition">
                                    Marcar Salida
                                </button>
                            </form>
                            @else
                            <span class="inline-flex items-center space-x-1 text-[10px] text-emerald-400 font-mono font-semibold">
                                <span class="material-icons-round text-xs">verified</span>
                                <span>Verificado</span>
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500">
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

<!-- MODAL: ASENTAR NOVEDAD EN LIBRO DE GARITA -->
<div id="modalNovedad" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-lg rounded-3xl p-7 border border-[#243352] shadow-2xl space-y-5 bg-[#0C1222]">
        <div class="flex items-center justify-between border-b border-[#1F293D] pb-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center">
                    <span class="material-icons-round text-xl">menu_book</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white">Asentar Novedad de Garita</h3>
                    <p class="text-[11px] text-slate-400">Oficial: <span class="text-amber-300 font-semibold">{{ Auth::user()->nombre_completo ?? 'José Gregorio Colmenares' }}</span></p>
                </div>
            </div>
            <button type="button" onclick="cerrarModalNovedad()" class="text-slate-400 hover:text-white">
                <span class="material-icons-round">close</span>
            </button>
        </div>

        <form onsubmit="guardarNovedadMinuta(event)" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                    Tipo de Novedad <span class="text-amber-400">*</span>
                </label>
                <select name="tipo" required class="w-full bg-[#0A0F1E] border border-[#243352] rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-amber-400">
                    <option value="Ronda Perimetral">🛡️ Ronda Perimetral</option>
                    <option value="Acceso Vehicular">🚗 Acceso Vehicular Especial</option>
                    <option value="Entrega / Correspondencia">📦 Entrega / Correspondencia para Socio</option>
                    <option value="Incidente">⚠️ Incidente / Anomalía</option>
                    <option value="Mantenimiento">🔧 Mantenimiento / Servicios Básicos</option>
                    <option value="Cambio de Guardia">🔄 Cambio de Guardia / Relevo</option>
                    <option value="General">📝 Novedad General</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                    Título / Resumen Corto <span class="text-amber-400">*</span>
                </label>
                <input type="text" name="titulo" required placeholder="Ej: Ronda nocturna sin novedades / Paquete para Acción 104"
                       class="w-full bg-[#0A0F1E] border border-[#243352] rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                    Descripción Detallada de la Novedad <span class="text-amber-400">*</span>
                </label>
                <textarea name="descripcion" rows="4" required placeholder="Describa claramente lo ocurrido, personas involucradas, vehículos, horas o acuerdos..."
                          class="w-full bg-[#0A0F1E] border border-[#243352] rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400"></textarea>
            </div>

            <div class="p-3 rounded-xl bg-[#080D1A] border border-[#1E293B] text-[11px] text-slate-400 flex items-center space-x-2">
                <span class="material-icons-round text-amber-400 text-sm">verified_user</span>
                <span>Quedará registrado a nombre del oficial en servicio con sello de tiempo inmutable.</span>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-[#1F293D]">
                <button type="button" onclick="cerrarModalNovedad()" class="px-4 py-2.5 rounded-xl bg-[#141C30] hover:bg-[#1E293B] text-slate-300 text-xs font-semibold transition border border-[#243352]">
                    Cancelar
                </button>
                <button type="submit" id="btnGuardarMinuta" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-amber-500/20 transition active:scale-95 flex items-center space-x-1.5">
                    <span class="material-icons-round text-sm">save</span>
                    <span>Guardar en Minuta</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<!-- Scripts locales para QR y Escáner -->
<script src="{{ asset('js/qrcode.min.js') }}"></script>
<script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
<script>
    let html5QrcodeScanner = null;
    let cameraActive = false;
    const OFICIAL_ACTUAL = "{{ Auth::user()->nombre_completo ?? 'Personal de Seguridad' }}";

    function abrirModalNovedad() {
        document.getElementById('modalNovedad').classList.remove('hidden');
    }

    function cerrarModalNovedad() {
        document.getElementById('modalNovedad').classList.add('hidden');
    }

    // Guardar novedad por AJAX con protección de doble clic
    function guardarNovedadMinuta(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('btnGuardarMinuta');
        if (btn.disabled) return;

        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = `
            <span class="material-icons-round text-sm animate-spin mr-1">sync</span>
            <span>Guardando en Minuta...</span>
        `;

        const formData = new FormData(form);

        fetch("{{ route('visitas.novedades.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            cerrarModalNovedad();
            form.reset();
            alert(data.mensaje || 'Novedad registrada exitosamente.');
            window.location.reload();
        })
        .catch(err => {
            alert('Error al registrar la novedad. Por favor verifique.');
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-not-allowed');
            btn.innerHTML = originalHtml;
        });
    }

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
                card.className = "rounded-3xl p-6 border border-rose-500/40 bg-rose-500/10 text-rose-300 space-y-2";
                card.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <span class="material-icons-round text-rose-400 text-xl">error_outline</span>
                        <h4 class="font-bold text-sm">Socio no encontrado o código QR inválido</h4>
                    </div>
                    <p class="text-xs text-slate-300">No se encontró ningún socio activo registrado con ("${query}"). Verifique el número de acción o cédula.</p>
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

        if (s.esta_en_club) {
            borderClass = "border-rose-500/50 bg-[#160D14]";
            badgeEstado = `<span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-rose-500/15 text-rose-400 border border-rose-500/30 flex items-center space-x-1">
                <span class="w-2 h-2 rounded-full bg-rose-400 mr-1.5 animate-ping"></span>
                ⛔ DENTRO DEL CLUB • SALIDA PENDIENTE
            </span>`;
        } else if (!s.activo) {
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

        let panelAccion = '';
        if (s.esta_en_club) {
            panelAccion = `
                <div class="space-y-4 pt-2 border-t border-[#1F293D]">
                    <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-200 text-xs space-y-2">
                        <div class="flex items-center space-x-2">
                            <span class="material-icons-round text-rose-400 text-lg">no_meeting_room</span>
                            <strong class="text-sm font-bold text-white">⛔ ACCESO DENEGADO: EL SOCIO YA ESTÁ DENTRO</strong>
                        </div>
                        <p class="text-slate-300 leading-relaxed">
                            Este socio ingresó el día de hoy a las <span class="font-bold text-amber-300">${s.hora_ingreso_hoy}</span> y aún no ha registrado su salida.
                            Por estricta política de control y aforo, <span class="text-rose-300 font-semibold">no se puede autorizar un segundo ingreso</span> sin antes haber marcado la salida correspondiente de las instalaciones.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                        <button type="button" onclick="document.getElementById('socioCardValidation').classList.add('hidden')" class="px-4 py-2.5 rounded-xl bg-[#141C30] hover:bg-[#1E293B] text-slate-300 text-xs font-semibold transition border border-[#243352]">
                            Cerrar
                        </button>
                        <form action="/visitas/${s.visita_activa_id}/salida" method="POST" class="inline" onsubmit="return confirm('¿Confirmar registro de salida para ' + ${JSON.stringify(s.nombre_completo)} + '?');">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-400 hover:to-rose-500 text-white font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-rose-500/25 flex items-center space-x-1.5 transition active:scale-95">
                                <span class="material-icons-round text-base">logout</span>
                                <span>🚪 Registrar Salida del Club Ahora</span>
                            </button>
                        </form>
                    </div>
                </div>
            `;
        } else {
            panelAccion = `
                <!-- Formulario de Confirmación de Ingreso -->
                <form onsubmit="registrarVisitaConfirmada(event, ${s.id})" class="space-y-4 pt-2 border-t border-[#1F293D]">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-300 text-xs font-semibold mb-1">Acompañantes / Invitados</label>
                            <input type="number" id="visitaInvitados" min="0" max="20" value="0"
                                   class="w-full bg-[#0A0F1E] border border-[#243352] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-amber-400 font-mono">
                        </div>
                        <div>
                            <label class="block text-slate-300 text-xs font-semibold mb-1">Observaciones / Placa vehículo</label>
                            <input type="text" id="visitaObservaciones" placeholder="Ej: Toyota Fortuner Gris Placa ABC-123"
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

            <!-- Badge del Vigilante Responsable -->
            <div class="p-3 rounded-xl bg-[#090E1A] border border-[#1E293B] text-[11px] text-slate-300 flex items-center justify-between">
                <span class="flex items-center space-x-1.5">
                    <span class="material-icons-round text-amber-400 text-sm">shield</span>
                    <span>Oficial en servicio:</span>
                </span>
                <strong class="text-amber-300 font-bold">${OFICIAL_ACTUAL}</strong>
            </div>

            ${panelAccion}
        `;
        card.classList.remove('hidden');
    }

    // Confirmación y guardado del ingreso con protección contra doble clic
    function registrarVisitaConfirmada(e, socioId) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');

        if (submitBtn && submitBtn.disabled) return;
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            submitBtn.innerHTML = `
                <span class="material-icons-round text-base animate-spin">sync</span>
                <span>Autorizando Acceso...</span>
            `;
        }

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
        .then(async res => {
            const data = await res.json();
            if (!res.ok) {
                throw new Error(data.mensaje || data.message || 'No se pudo autorizar el ingreso.');
            }
            return data;
        })
        .then(data => {
            alert(data.mensaje);
            window.location.reload();
        })
        .catch(err => {
            alert(err.message || 'Error al registrar la visita.');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                submitBtn.innerHTML = `
                    <span class="material-icons-round text-base">check_circle</span>
                    <span>Autorizar e Ingresar al Club</span>
                `;
            }
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
