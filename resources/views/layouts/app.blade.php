<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inicio') | Country Club - Sistema Ejecutivo</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Compiled Static Tailwind CSS (Ultra-Fast) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0B0F19;
            color: #F3F4F6;
        }

        /* Ambient Glows */
        .ambient-glow-amber {
            position: absolute;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.08) 0%, rgba(245, 158, 11, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        .ambient-glow-emerald {
            position: absolute;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.06) 0%, rgba(16, 185, 129, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0B0F19; }
        ::-webkit-scrollbar-thumb { background: #1F293D; border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: #374151; }

        .glass-panel {
            background: rgba(17, 24, 39, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(31, 41, 61, 0.8);
        }
    </style>
    @stack('styles')
</head>
<body class="flex h-screen overflow-hidden antialiased selection:bg-amber-500 selection:text-black">

    <!-- Luces de ambiente sutiles -->
    <div class="ambient-glow-amber top-[-100px] left-[200px]"></div>
    <div class="ambient-glow-emerald bottom-[-100px] right-[-100px]"></div>

    <!-- SIDEBAR LATERAL DE LUJO -->
    <aside class="w-72 bg-[#0E1526]/90 backdrop-blur-2xl flex flex-col justify-between border-r border-[#1B253B] z-20 shadow-2xl transition-all duration-300">
        <div>
            <!-- Marca y Monograma -->
            <div class="h-20 flex items-center px-6 border-b border-[#1B253B]">
                <div class="flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-400 via-amber-500 to-amber-700 p-[1px] shadow-lg shadow-amber-500/10">
                        <div class="w-full h-full bg-[#0B0F19] rounded-[11px] flex items-center justify-center">
                            <img src="{{ asset('img/logo.png') }}" alt="CC" class="h-6 w-auto object-contain">
                        </div>
                    </div>
                    <div>
                        <h1 class="text-base font-bold text-white tracking-wide flex items-center">
                            Country Club
                            <span class="ml-2 w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        </h1>
                        @if(Auth::check() && Auth::user()->esVigilante())
                            <p class="text-[11px] font-medium text-emerald-400/90 tracking-wider uppercase">Puesto de Vigilancia</p>
                        @else
                            <p class="text-[11px] font-medium text-amber-400/90 tracking-wider uppercase">Portal Administrativo</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Navegación -->
            <div class="py-6 px-4 space-y-6">
                @if(Auth::check() && Auth::user()->esVigilante())
                    <!-- MENÚ EXCLUSIVO DE VIGILANCIA & SEGURIDAD -->
                    <div>
                        <p class="px-3 text-[10px] font-bold tracking-widest uppercase text-emerald-400 mb-2.5 flex items-center space-x-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse mr-1"></span>
                            Seguridad & Garita
                        </p>
                        <nav class="space-y-1.5">
                            <a href="{{ route('visitas.index') }}" 
                               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('visitas.index') ? 'bg-gradient-to-r from-emerald-500/15 to-transparent text-emerald-400 border-l-2 border-emerald-400 shadow-sm' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50' }}">
                                <span class="material-icons-round text-lg mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('visitas.index') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-300' }}">security</span>
                                <span>Control de Acceso / Garita</span>
                            </a>

                            <a href="{{ route('visitas.index') }}#seccionAforo" 
                               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group text-slate-400 hover:text-slate-100 hover:bg-slate-800/50">
                                <span class="material-icons-round text-lg mr-3 text-slate-500 group-hover:text-slate-300">groups</span>
                                <span>Aforo en Tiempo Real</span>
                            </a>

                            <a href="{{ route('visitas.index') }}#modalNovedad" onclick="if(typeof abrirModalNovedad === 'function'){ abrirModalNovedad(); return false; } else { window.location.href='{{ route('visitas.index') }}'; }"
                               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group text-slate-400 hover:text-slate-100 hover:bg-slate-800/50">
                                <span class="material-icons-round text-lg mr-3 text-slate-500 group-hover:text-slate-300">menu_book</span>
                                <span>Libro de Novedades</span>
                            </a>
                        </nav>
                    </div>

                    <!-- CONSULTA DE PADRÓN (SOLO LECTURA PARA VIGILANCIA) -->
                    <div>
                        <p class="px-3 text-[10px] font-bold tracking-widest uppercase text-slate-500 mb-2.5">Padrón de Seguridad</p>
                        <nav class="space-y-1.5">
                            <a href="{{ route('socios.index') }}" 
                               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('socios.index', 'socios.show') ? 'bg-gradient-to-r from-amber-500/15 to-transparent text-amber-400 border-l-2 border-amber-400 shadow-sm' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50' }}">
                                <span class="material-icons-round text-lg mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('socios.index', 'socios.show') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300' }}">badge</span>
                                <span>Directorio & Carnets</span>
                            </a>
                        </nav>
                    </div>
                @else
                    <!-- MENÚ COMPLETO ADMINISTRATIVO (ADMIN & SECRETARIA) -->
                    <div>
                        <p class="px-3 text-[10px] font-bold tracking-widest uppercase text-slate-500 mb-2.5">Módulos Principales</p>
                        <nav class="space-y-1.5">
                            <a href="{{ route('dashboard') }}" 
                               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-amber-500/15 to-transparent text-amber-400 border-l-2 border-amber-400 shadow-sm' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50' }}">
                                <span class="material-icons-round text-lg mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('dashboard') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300' }}">dashboard</span>
                                <span>Dashboard General</span>
                            </a>

                            <a href="{{ route('socios.index') }}" 
                               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('socios.index', 'socios.show') ? 'bg-gradient-to-r from-amber-500/15 to-transparent text-amber-400 border-l-2 border-amber-400 shadow-sm' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50' }}">
                                <span class="material-icons-round text-lg mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('socios.index', 'socios.show') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300' }}">badge</span>
                                <span>Directorio de Socios</span>
                            </a>

                            <a href="{{ route('socios.create') }}" 
                               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('socios.create') ? 'bg-gradient-to-r from-amber-500/15 to-transparent text-amber-400 border-l-2 border-amber-400 shadow-sm' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50' }}">
                                <span class="material-icons-round text-lg mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('socios.create') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300' }}">person_add</span>
                                <span>Nueva Afiliación</span>
                            </a>
                        </nav>
                    </div>

                    <!-- Sección Financiera -->
                    <div>
                        <p class="px-3 text-[10px] font-bold tracking-widest uppercase text-slate-500 mb-2.5">Caja & Cobranzas</p>
                        <nav class="space-y-1.5">
                            <a href="{{ route('pagos.create') }}" 
                               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('pagos.create') ? 'bg-gradient-to-r from-emerald-500/15 to-transparent text-emerald-400 border-l-2 border-emerald-400 shadow-sm' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50' }}">
                                <span class="material-icons-round text-lg mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('pagos.create') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-300' }}">point_of_sale</span>
                                <span>Caja y Cobro de Cuota</span>
                            </a>

                            <a href="{{ route('pagos.index') }}" 
                               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('pagos.index') ? 'bg-gradient-to-r from-emerald-500/15 to-transparent text-emerald-400 border-l-2 border-emerald-400 shadow-sm' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50' }}">
                                <span class="material-icons-round text-lg mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('pagos.index') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-300' }}">receipt_long</span>
                                <span>Historial de Cobros</span>
                            </a>
                        </nav>
                    </div>

                    <!-- Sección Control de Acceso & Garita -->
                    <div>
                        <p class="px-3 text-[10px] font-bold tracking-widest uppercase text-slate-500 mb-2.5">Acceso & Garita</p>
                        <nav class="space-y-1.5">
                            <a href="{{ route('visitas.index') }}" 
                               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('visitas.index') ? 'bg-gradient-to-r from-blue-500/15 to-transparent text-blue-400 border-l-2 border-blue-400 shadow-sm' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50' }}">
                                <span class="material-icons-round text-lg mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('visitas.index') ? 'text-blue-400' : 'text-slate-500 group-hover:text-slate-300' }}">qr_code_scanner</span>
                                <span>Garita y Control de Visitas</span>
                            </a>
                        </nav>
                    </div>

                    <!-- Sección Auditoría -->
                    <div>
                        <p class="px-3 text-[10px] font-bold tracking-widest uppercase text-slate-500 mb-2.5">Seguridad & Control</p>
                        <nav class="space-y-1.5">
                            <a href="{{ route('movimientos.index') }}" 
                               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('movimientos.index') ? 'bg-gradient-to-r from-amber-500/15 to-transparent text-amber-400 border-l-2 border-amber-400 shadow-sm' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50' }}">
                                <span class="material-icons-round text-lg mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('movimientos.index') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300' }}">verified_user</span>
                                <span>Bitácora de Auditoría</span>
                            </a>
                        </nav>
                    </div>
                @endif
            </div>
        </div>

        <!-- Usuario Operador & Salir -->
        <div class="p-4 border-t border-[#1B253B] bg-[#0A0E1A]/80">
            <div class="flex items-center justify-between p-2 rounded-xl bg-slate-800/30 border border-slate-800/60">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <div class="w-8 h-8 rounded-lg {{ Auth::user()->esVigilante() ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400' : 'bg-amber-500/10 border-amber-500/30 text-amber-400' }} border font-bold text-xs flex items-center justify-center flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->nombre_completo ?? 'A', 0, 1)) }}
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-bold text-slate-200 truncate">{{ Auth::user()->nombre_completo ?? 'Usuario' }}</p>
                        <p class="text-[10px] {{ Auth::user()->esVigilante() ? 'text-emerald-400' : 'text-amber-400' }} font-mono tracking-wider font-semibold uppercase">{{ Auth::user()->rol ?? 'Admin' }}</p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Cerrar Sesión" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 flex items-center justify-center transition-colors">
                        <span class="material-icons-round text-base">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- ÁREA DE CONTENIDO CENTRAL -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden z-10">
        
        <!-- NAVBAR SUPERIOR PREMIUM -->
        <header class="h-20 bg-[#0E1526]/95 backdrop-blur-xl border-b border-[#1B253B] flex items-center justify-between px-8 relative z-40">
            <div>
                <h2 class="text-xl font-extrabold text-white tracking-tight flex items-center">
                    @yield('header_title', 'Dashboard')
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">@yield('header_subtitle', 'Club Social & Deportivo - Sistema Central')</p>
            </div>

            <!-- Widget Tasa Oficial BCV Interactivo -->
            <div class="flex items-center space-x-4">
                <div class="flex items-center bg-[#111A2E] border border-amber-500/30 rounded-2xl p-1.5 shadow-lg shadow-amber-500/5">
                    <div class="flex items-center px-3 py-1 space-x-2.5">
                        <img src="{{ asset('img/Banco_Central_de_Venezuela_logo.svg') }}" alt="BCV" class="h-5 w-auto">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block leading-none">Dólar Oficial BCV</span>
                            <span class="text-sm font-extrabold text-amber-400 font-mono tracking-tight" id="bcv-rate-display">
                                {{ number_format($tasaBcv ?? \App\Models\TasaDolar::tasaActual(), 2, ',', '.') }} Bs / USD
                            </span>
                        </div>
                    </div>

                    <!-- Botón para Refrescar Tasa en Vivo -->
                    <button id="btn-sync-bcv" onclick="sincronizarTasaBcv()" title="Consultar tasa oficial en vivo" 
                            class="p-2 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 hover:text-amber-300 transition-all border border-amber-500/20 group">
                        <span id="sync-icon" class="material-icons-round text-base block group-hover:rotate-180 transition-transform duration-500">sync</span>
                    </button>
                </div>

                <!-- Campana de Notificaciones en Vivo -->
                <div class="relative z-50" id="dropdownNotificacionesContainer">
                    <button type="button" id="btnToggleNotificaciones" onclick="toggleDropdownNotificaciones()"
                            class="relative p-2.5 rounded-2xl bg-[#111A2E] hover:bg-[#18233C] border border-[#243352] hover:border-amber-400/50 text-slate-300 hover:text-white transition shadow-lg shadow-black/20 flex items-center justify-center">
                        <span class="material-icons-round text-xl text-amber-400">notifications</span>
                        
                        <!-- Badge Contador de No Leídas -->
                        <span id="badgeNotificacionesCount" class="hidden absolute -top-1.5 -right-1.5 px-1.5 py-0.5 min-w-[20px] h-5 rounded-full bg-rose-500 text-white font-mono font-black text-[10px] flex items-center justify-center border-2 border-[#0E1526] shadow-md shadow-rose-500/50 animate-pulse">
                            0
                        </span>
                    </button>

                    <!-- Dropdown Flotante de Notificaciones con Máxima Elevación -->
                    <div id="dropdownNotificaciones" class="hidden absolute right-0 top-full mt-2.5 w-80 sm:w-96 rounded-3xl bg-[#0B101D] border-2 border-amber-500/40 shadow-[0_25px_70px_rgba(0,0,0,0.95)] z-[9999] overflow-hidden">
                        <div class="p-4 border-b border-[#1E293B] flex items-center justify-between bg-[#0F172A]">
                            <div class="flex items-center space-x-2">
                                <span class="material-icons-round text-amber-400 text-lg">notifications_active</span>
                                <h4 class="text-xs font-bold text-white uppercase tracking-wider">Centro de Notificaciones</h4>
                            </div>
                            <button type="button" onclick="marcarTodasNotificacionesLeidas()" class="text-[10px] font-semibold text-amber-400 hover:text-amber-300 transition px-2 py-1 rounded-lg hover:bg-amber-500/10">
                                Marcar todas leídas
                            </button>
                        </div>

                        <!-- Lista Scrolleable de Notificaciones -->
                        <div id="listaNotificaciones" class="max-h-96 overflow-y-auto divide-y divide-[#1A2438] bg-[#0B101D]">
                            <div class="p-8 text-center text-slate-500 text-xs">
                                <span class="material-icons-round animate-spin text-2xl text-amber-400 block mb-2">sync</span>
                                <span>Cargando notificaciones...</span>
                            </div>
                        </div>

                        <!-- Pie de Dropdown -->
                        <div class="p-3 bg-[#080D18] border-t border-[#1E293B] text-center">
                            <span class="text-[10px] text-slate-400">Sincronizado en tiempo real con Garita y Administración</span>
                        </div>
                    </div>
                </div>

                <!-- Fecha Actual -->
                <div class="hidden md:flex flex-col text-right">
                    <span class="text-[11px] font-bold text-slate-300 capitalize">{{ now()->translatedFormat('l, d F Y') }}</span>
                    <span class="text-[10px] text-emerald-400 font-medium flex items-center justify-end">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                        Servicios Operativos
                    </span>
                </div>
            </div>
        </header>

        <!-- CONTENIDO SCROLLEABLE -->
        <main class="flex-1 overflow-y-auto p-8 space-y-6">
            
            <!-- Notificaciones Flash -->
            @if(session('success'))
            <div class="bg-emerald-950/40 border border-emerald-500/40 text-emerald-200 px-5 py-4 rounded-2xl flex items-center justify-between shadow-lg backdrop-blur-md">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0">
                        <span class="material-icons-round text-base">check</span>
                    </div>
                    <p class="text-xs font-semibold">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white">&times;</button>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-rose-950/40 border border-rose-500/40 text-rose-200 px-5 py-4 rounded-2xl flex items-center justify-between shadow-lg backdrop-blur-md">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center flex-shrink-0">
                        <span class="material-icons-round text-base">warning</span>
                    </div>
                    <p class="text-xs font-semibold">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white">&times;</button>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Script Global de Sincronización BCV -->
    <script>
        function sincronizarTasaBcv() {
            const btn = document.getElementById('btn-sync-bcv');
            const icon = document.getElementById('sync-icon');
            const display = document.getElementById('bcv-rate-display');

            icon.classList.add('animate-spin');
            btn.disabled = true;

            fetch("{{ route('bcv.sync') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    display.textContent = data.formatted + ' / USD';
                    // Animación visual de éxito
                    display.classList.add('text-emerald-400');
                    setTimeout(() => display.classList.remove('text-emerald-400'), 1500);

                    // Si estamos en la página de pagos, recalcular montos en vivo
                    if (typeof window.actualizarTasaEnPagos === 'function') {
                        window.actualizarTasaEnPagos(data.tasa);
                    }
                }
            })
            .catch(err => {
                console.error("Error al sincronizar con BCV:", err);
            })
            .finally(() => {
                setTimeout(() => {
                    icon.classList.remove('animate-spin');
                    btn.disabled = false;
                }, 600);
            });
        }

        // Prevención global de envíos múltiples / doble clic
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!form || form.tagName !== 'FORM') return;

            if (form.dataset.submitting === 'true') {
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            }

            form.dataset.submitting = 'true';

            const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                const textSpan = submitBtn.querySelector('span:last-child');
                if (textSpan) {
                    textSpan.dataset.origText = textSpan.textContent;
                    textSpan.textContent = 'Procesando...';
                }
            }

            setTimeout(() => {
                form.dataset.submitting = 'false';
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                    const textSpan = submitBtn.querySelector('span:last-child');
                    if (textSpan && textSpan.dataset.origText) {
                        textSpan.textContent = textSpan.dataset.origText;
                    }
                }
            }, 6000);
        }, false);

        // ==========================================
        // SISTEMA DE NOTIFICACIONES EN TIEMPO REAL
        // ==========================================
        let notificacionesConocidasIds = new Set();
        let esPrimeraCargaNotificaciones = true;

        function toggleDropdownNotificaciones() {
            const dropdown = document.getElementById('dropdownNotificaciones');
            dropdown.classList.toggle('hidden');
            if (!dropdown.classList.contains('hidden')) {
                consultarNotificaciones();
            }
        }

        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', function (e) {
            const container = document.getElementById('dropdownNotificacionesContainer');
            const dropdown = document.getElementById('dropdownNotificaciones');
            if (container && dropdown && !container.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        function consultarNotificaciones() {
            fetch("{{ route('notificaciones.index') }}")
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return;

                    const badge = document.getElementById('badgeNotificacionesCount');
                    const lista = document.getElementById('listaNotificaciones');

                    // Actualizar contador del badge
                    if (data.no_leidas_count > 0) {
                        badge.textContent = data.no_leidas_count > 99 ? '99+' : data.no_leidas_count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }

                    if (!data.notificaciones || data.notificaciones.length === 0) {
                        lista.innerHTML = `
                            <div class="p-8 text-center text-slate-500 text-xs">
                                <span class="material-icons-round text-3xl text-slate-600 block mb-1">notifications_off</span>
                                <span>No tienes notificaciones pendientes.</span>
                            </div>
                        `;
                        esPrimeraCargaNotificaciones = false;
                        return;
                    }

                    let html = '';
                    data.notificaciones.forEach(n => {
                        // Si hay una notificación no leída nueva que no teníamos registrada, lanzar Toast flotante
                        if (!esPrimeraCargaNotificaciones && !n.leida && !notificacionesConocidasIds.has(n.id)) {
                            lanzarToastNotificacion(n);
                        }
                        notificacionesConocidasIds.add(n.id);

                        let bgClass = n.leida ? 'bg-transparent' : 'bg-[#131D33]/70';
                        let iconColor = 'text-amber-400 bg-amber-500/10 border-amber-500/20';
                        if (n.color === 'rose') iconColor = 'text-rose-400 bg-rose-500/10 border-rose-500/20';
                        if (n.color === 'emerald') iconColor = 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20';
                        if (n.color === 'blue') iconColor = 'text-blue-400 bg-blue-500/10 border-blue-500/20';
                        if (n.color === 'purple') iconColor = 'text-purple-400 bg-purple-500/10 border-purple-500/20';

                        html += `
                            <div class="p-3.5 hover:bg-[#16233B] transition flex items-start space-x-3 cursor-pointer ${bgClass}" onclick="abrirNotificacion(${n.id}, '${n.enlace || ''}')">
                                <div class="w-9 h-9 rounded-xl ${iconColor} border flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-icons-round text-base">${n.icono || 'notifications'}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h5 class="text-xs font-bold text-white truncate">${n.titulo}</h5>
                                        ${!n.leida ? '<span class="w-2 h-2 rounded-full bg-rose-500 flex-shrink-0 ml-1"></span>' : ''}
                                    </div>
                                    <p class="text-[11px] text-slate-300 mt-0.5 line-clamp-2 leading-relaxed">${n.mensaje}</p>
                                    <span class="text-[9px] font-mono text-slate-500 block mt-1">${n.hace_tiempo}</span>
                                </div>
                            </div>
                        `;
                    });

                    lista.innerHTML = html;
                    esPrimeraCargaNotificaciones = false;
                })
                .catch(err => {
                    console.error("Error al consultar notificaciones:", err);
                });
        }

        function abrirNotificacion(id, enlace) {
            fetch(`/api/notificaciones/${id}/leer`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).finally(() => {
                if (enlace) {
                    window.location.href = enlace;
                } else {
                    consultarNotificaciones();
                }
            });
        }

        function marcarTodasNotificacionesLeidas() {
            fetch("{{ route('notificaciones.marcar-todas') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(() => {
                consultarNotificaciones();
            });
        }

        function lanzarToastNotificacion(n) {
            const container = document.getElementById('toastNotificacionesContainer');
            if (!container) return;

            let iconColor = 'text-amber-400 bg-amber-500/20 border-amber-500/30';
            if (n.color === 'rose') iconColor = 'text-rose-400 bg-rose-500/20 border-rose-500/30';
            if (n.color === 'emerald') iconColor = 'text-emerald-400 bg-emerald-500/20 border-emerald-500/30';
            if (n.color === 'blue') iconColor = 'text-blue-400 bg-blue-500/20 border-blue-500/30';

            const toast = document.createElement('div');
            toast.className = "pointer-events-auto p-4 rounded-2xl bg-[#0D1424] border border-[#2A3B5C] shadow-2xl shadow-black flex items-start space-x-3.5 transform transition-all duration-300 translate-y-3 opacity-0 cursor-pointer";
            toast.innerHTML = `
                <div class="w-10 h-10 rounded-xl ${iconColor} border flex items-center justify-center flex-shrink-0">
                    <span class="material-icons-round text-lg">${n.icono || 'notifications'}</span>
                </div>
                <div class="flex-1 min-w-0" onclick="abrirNotificacion(${n.id}, '${n.enlace || ''}')">
                    <h5 class="text-xs font-bold text-white">${n.titulo}</h5>
                    <p class="text-[11px] text-slate-300 mt-0.5 line-clamp-2 leading-relaxed">${n.mensaje}</p>
                    <span class="text-[9px] font-mono text-slate-400 block mt-1">${n.hace_tiempo || 'Ahora'}</span>
                </div>
                <button type="button" onclick="event.stopPropagation(); this.closest('div.pointer-events-auto').remove()" class="text-slate-400 hover:text-white">
                    <span class="material-icons-round text-sm">close</span>
                </button>
            `;

            container.appendChild(toast);
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-3', 'opacity-0');
            });

            setTimeout(() => {
                toast.classList.add('translate-y-3', 'opacity-0');
                setTimeout(() => toast.remove(), 400);
            }, 7000);
        }

        // Consultar al cargar la página y hacer sondeo periódico cada 15 segundos
        document.addEventListener('DOMContentLoaded', function () {
            consultarNotificaciones();
            setInterval(consultarNotificaciones, 15000);
        });
    </script>

    <!-- Contenedor Flotante de Toast Emergentes con Máxima Elevación -->
    <div id="toastNotificacionesContainer" class="fixed top-24 right-8 z-[10000] space-y-3 pointer-events-none max-w-sm w-full"></div>

    @stack('scripts')
</body>
</html>
