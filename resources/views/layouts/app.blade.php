<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inicio') | Country Club - Sistema Ejecutivo</title>
    
    <!-- Google Fonts: Plus Jakarta Sans (Carga Asíncrona No-Bloqueante) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    
    <!-- Compiled Static Tailwind CSS (Ultra-Fast) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Google Material Icons (Carga Asíncrona No-Bloqueante) -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet" media="print" onload="this.media='all'">

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
            background: rgba(11, 16, 29, 0.78);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(191, 149, 63, 0.16);
            box-shadow: 0 20px 40px -16px rgba(0, 0, 0, 0.8), inset 0 1px 0 rgba(255, 255, 255, 0.05);
        }

        /* ===== Identidad de lujo compartida con el login ===== */
        .gold-prestige-title {
            font-family: 'Cinzel', serif;
            font-weight: 900;
            letter-spacing: 0.1em;
            background: linear-gradient(125deg, #7A5210 0%, #BF953F 18%, #FCF6BA 36%, #A87B22 50%, #FBF5B7 64%, #946416 82%, #7A5210 100%);
            background-size: 220% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,.9));
            animation: goldLuster 7s ease-in-out infinite;
            display: inline-block;
        }
        @keyframes goldLuster { 0%,100% { background-position: 0% center; } 50% { background-position: 100% center; } }

        .gold-border {
            position: relative;
        }
        .gold-border::before {
            content: '';
            position: absolute; inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, rgba(252,246,186,.55), rgba(191,149,63,.12) 35%, rgba(191,149,63,.05) 65%, rgba(252,246,186,.4));
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
                    mask-composite: exclude;
            pointer-events: none;
        }

        .kpi-card {
            transition: transform .3s cubic-bezier(.16,1,.3,1), box-shadow .3s ease;
            animation: riseIn .6s cubic-bezier(.16,1,.3,1) both;
        }
        .kpi-card:hover { transform: translateY(-4px); box-shadow: 0 24px 44px -14px rgba(0,0,0,.9), 0 0 36px -10px rgba(191,149,63,.35); }
        @keyframes riseIn { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }

        .shine-sweep { position: relative; overflow: hidden; }
        .shine-sweep::after {
            content: '';
            position: absolute; top: 0; bottom: 0; width: 40%;
            left: -60%;
            background: linear-gradient(100deg, transparent, rgba(252,246,186,.12), transparent);
            transform: skewX(-18deg);
            animation: sweep 7s ease-in-out infinite;
            pointer-events: none;
        }
        @keyframes sweep { 0%, 55% { left: -60%; } 100% { left: 130%; } }

        /* ===== Fondo vivo ligero (solo transform/opacity: sin blur costoso) ===== */
        .app-scene { position: fixed; inset: 0; z-index: 0; overflow: hidden; pointer-events: none; }
        .app-orb { position: absolute; border-radius: 50%; will-change: transform; }
        .app-orb.o1 { width: 760px; height: 760px; top: -260px; left: 120px;
            background: radial-gradient(circle, rgba(217,119,6,.20) 0%, rgba(191,149,63,.07) 45%, transparent 70%);
            animation: appDrift1 26s ease-in-out infinite; }
        .app-orb.o2 { width: 700px; height: 700px; bottom: -280px; right: -160px;
            background: radial-gradient(circle, rgba(245,158,11,.15) 0%, rgba(16,185,129,.05) 45%, transparent 70%);
            animation: appDrift2 30s ease-in-out infinite; }
        @keyframes appDrift1 { 0%,100% { transform: translate(0,0); } 50% { transform: translate(160px,110px); } }
        @keyframes appDrift2 { 0%,100% { transform: translate(0,0); } 50% { transform: translate(-170px,-90px); } }
        .app-grid { position: absolute; inset: 0;
            background-image: linear-gradient(rgba(191,149,63,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(191,149,63,.05) 1px, transparent 1px);
            background-size: 56px 56px;
            -webkit-mask-image: radial-gradient(ellipse at 50% 30%, #000 0%, transparent 75%);
                    mask-image: radial-gradient(ellipse at 50% 30%, #000 0%, transparent 75%); }
        .app-reticle { position: absolute; right: 4%; top: 18%; width: 360px; height: 360px; opacity: .07; animation: appSpin 90s linear infinite; }
        @keyframes appSpin { to { transform: rotate(360deg); } }
        @media (prefers-reduced-motion: reduce) { .app-orb, .app-reticle, .shine-sweep::after, .gold-prestige-title { animation: none; } }
    </style>
    @stack('styles')
</head>
<body class="flex h-screen overflow-hidden antialiased selection:bg-amber-500 selection:text-black">

    <!-- Fondo vivo dorado (ligero) -->
    <div class="app-scene" aria-hidden="true">
        <div class="app-orb o1"></div>
        <div class="app-orb o2"></div>
        <div class="app-grid"></div>
        <svg class="app-reticle" viewBox="0 0 400 400" fill="none" stroke="#BF953F" stroke-width="1.5">
            <circle cx="200" cy="200" r="190"/><circle cx="200" cy="200" r="130"/><circle cx="200" cy="200" r="70"/><circle cx="200" cy="200" r="14"/>
            <path d="M200 0v120M200 280v120M0 200h120M280 200h120" stroke-linecap="round"/>
        </svg>
    </div>

    <!-- SIDEBAR LATERAL DE LUJO -->
    <aside class="w-72 bg-[#0E1526]/90 backdrop-blur-2xl flex flex-col justify-between border-r border-[#1B253B] z-20 shadow-2xl transition-all duration-300">
        <div>
            <!-- Marca y Monograma -->
            <div class="h-20 flex items-center px-6 border-b border-[#1B253B]">
                <div class="flex items-center space-x-3.5">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <img src="{{ asset('img/logo.png') }}" alt="SCOCC" class="h-11 w-auto object-contain" style="filter: drop-shadow(0 0 12px rgba(217,119,6,.45)) drop-shadow(0 4px 8px rgba(0,0,0,.8));">
                    </div>
                    <div>
                        <h1 class="text-[17px] gold-prestige-title uppercase flex items-center">
                            Country Club
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
                                <div class="flex-1 flex items-center justify-between">
                                    <span>Directorio & Carnets</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-mono bg-amber-500/10 text-amber-300 border border-amber-500/20 font-bold">QR</span>
                                </div>
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
            @if(session('success') && !request()->routeIs('pagos.receipt'))
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

            @if(session('error') && !request()->routeIs('pagos.receipt'))
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

        let notificacionesInterval = null;

        function consultarNotificaciones() {
            if (document.hidden) return;
            fetch("{{ route('notificaciones.index', [], false) }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => {
                    if (res.status === 401 || res.redirected) {
                        if (notificacionesInterval) clearInterval(notificacionesInterval);
                        return null;
                    }
                    return res.ok ? res.json() : null;
                })
                .then(data => {
                    if (!data || !data.success) return;

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
            notificacionesInterval = setInterval(consultarNotificaciones, 15000);
        });
    </script>

    <!-- Contenedor Flotante de Toast Emergentes con Máxima Elevación -->
    <div id="toastNotificacionesContainer" class="fixed top-24 right-8 z-[10000] space-y-3 pointer-events-none max-w-sm w-full"></div>

    @stack('scripts')
</body>
</html>
