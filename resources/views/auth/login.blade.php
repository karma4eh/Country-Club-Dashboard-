<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Seguro | Country Club C.A. - Portal Directivo</title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Cinzel Luxury Serif (Non-blocking) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    
    <!-- Compiled Static Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Material Icons (Non-blocking) -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet" media="print" onload="this.media='all'">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 50% 18%, #0C1324 0%, #060912 60%, #03050A 100%);
            color: #F3F4F6;
        }

        /* EFECTO DE ORO METÁLICO SERIO & PRESTIGIOSO (SIN ESTRELLITAS NI EXCESOS) */
        .gold-prestige-title {
            font-family: 'Cinzel', serif;
            font-weight: 900;
            letter-spacing: 0.12em;
            background: linear-gradient(
                125deg,
                #7A5210 0%,
                #BF953F 18%,
                #FCF6BA 36%,
                #A87B22 50%,
                #FBF5B7 64%,
                #946416 82%,
                #7A5210 100%
            );
            background-size: 220% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 3px 5px rgba(0, 0, 0, 0.95)) 
                    drop-shadow(0 0 16px rgba(191, 149, 63, 0.35));
            animation: goldPrestigeLuster 6.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            display: inline-block;
            position: relative;
        }

        @keyframes goldPrestigeLuster {
            0%, 100% { background-position: 0% center; }
            50% { background-position: 100% center; }
        }

        /* Logotipo flotante puro sin recuadro ni marcos */
        .logo-floating {
            filter: drop-shadow(0 0 24px rgba(217, 119, 6, 0.4)) drop-shadow(0 8px 18px rgba(0, 0, 0, 0.9));
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), filter 0.35s ease;
        }

        .logo-floating:hover {
            transform: scale(1.05) translateY(-2px);
            filter: drop-shadow(0 0 34px rgba(245, 158, 11, 0.65)) drop-shadow(0 12px 24px rgba(0, 0, 0, 0.95));
        }

        .glass-login {
            background: rgba(11, 16, 29, 0.85);
            backdrop-filter: blur(28px);
            -webkit-backdrop-filter: blur(28px);
            border: 1px solid rgba(217, 119, 6, 0.25);
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.9), 
                        0 0 40px -10px rgba(191, 149, 63, 0.1), 
                        inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .gold-badge {
            background: linear-gradient(135deg, rgba(191, 149, 63, 0.18) 0%, rgba(120, 82, 16, 0.1) 100%);
            border: 1px solid rgba(191, 149, 63, 0.35);
        }

        /* =====================================================
           FONDO DINÁMICO ANIMADO
           ===================================================== */
        .bg-scene {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
            user-select: none;
        }

        /* Aurora dorada */
        .aurora-orb {
            position: absolute;
            border-radius: 50%;
            will-change: transform;
        }
        .orb-1 {
            width: 620px; height: 620px;
            top: -180px; left: -140px;
            background: radial-gradient(circle, rgba(217,119,6,.38) 0%, rgba(191,149,63,.12) 45%, transparent 70%);
            animation: drift1 22s ease-in-out infinite;
        }
        .orb-2 {
            width: 560px; height: 560px;
            bottom: -200px; right: -160px;
            background: radial-gradient(circle, rgba(245,158,11,.30) 0%, rgba(146,64,14,.12) 45%, transparent 70%);
            animation: drift2 26s ease-in-out infinite;
        }
        .orb-3 {
            width: 420px; height: 420px;
            top: 40%; left: 55%;
            background: radial-gradient(circle, rgba(252,211,77,.16) 0%, rgba(30,41,59,.25) 50%, transparent 72%);
            animation: drift3 30s ease-in-out infinite;
        }
        @keyframes drift1 {
            0%,100% { transform: translate(0,0) scale(1); }
            33%     { transform: translate(140px,90px) scale(1.15); }
            66%     { transform: translate(60px,200px) scale(.92); }
        }
        @keyframes drift2 {
            0%,100% { transform: translate(0,0) scale(1); }
            40%     { transform: translate(-180px,-120px) scale(1.12); }
            75%     { transform: translate(-60px,-220px) scale(.95); }
        }
        @keyframes drift3 {
            0%,100% { transform: translate(-50%,-50%) scale(1); opacity: .7; }
            50%     { transform: translate(-90%,-20%) scale(1.25); opacity: 1; }
        }

        /* Malla en perspectiva que avanza */
        .grid-floor {
            position: absolute;
            left: -50%; right: -50%;
            bottom: -10%;
            height: 60%;
            background-image:
                linear-gradient(rgba(191,149,63,.22) 1px, transparent 1px),
                linear-gradient(90deg, rgba(191,149,63,.22) 1px, transparent 1px);
            background-size: 60px 60px;
            transform: perspective(500px) rotateX(62deg);
            transform-origin: center top;
            -webkit-mask-image: linear-gradient(to top, rgba(0,0,0,.9) 0%, transparent 85%);
                    mask-image: linear-gradient(to top, rgba(0,0,0,.9) 0%, transparent 85%);
            animation: gridMove 6s linear infinite;
            opacity: .55;
        }
        @keyframes gridMove {
            from { background-position: 0 0; }
            to   { background-position: 0 60px; }
        }

        /* Mira telescópica que apunta y dispara */
        .scope {
            position: absolute;
            left: 0; top: 0;
            width: 240px; height: 240px;
            opacity: .45;
            transform: translate(-300px, -300px);
            transition: transform 1.7s cubic-bezier(.65, 0, .35, 1);
            will-change: transform;
            z-index: 2;
        }
        .scope svg { width: 100%; height: 100%; overflow: visible; }
        .spin-slow    { transform-origin: 200px 200px; animation: spin 60s linear infinite; }
        .spin-reverse { transform-origin: 200px 200px; animation: spin 38s linear infinite reverse; }
        .pulse-core   { transform-origin: 200px 200px; animation: corePulse 3.2s ease-in-out infinite; }
        .lock-ring    { transform-origin: 200px 200px; transform: scale(2.2); opacity: 0; transition: transform .45s cubic-bezier(.2,.9,.3,1.2), opacity .3s; }
        .aim-dot      { opacity: 0; transition: opacity .2s; }
        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes corePulse {
            0%,100% { transform: scale(1);   opacity: .6; }
            50%     { transform: scale(1.35); opacity: 1; }
        }

        /* Fijando blanco: el anillo se cierra y aparece el punto rojo */
        .scope.locking { opacity: .75; transition: transform 1.7s cubic-bezier(.65,0,.35,1), opacity .3s; }
        .scope.locking .lock-ring { transform: scale(1); opacity: 1; }
        .scope.locking .aim-dot   { opacity: 1; }

        /* Retroceso del disparo */
        .scope.firing svg { animation: recoil .38s cubic-bezier(.2,.8,.3,1); }
        @keyframes recoil {
            0%   { transform: translate(0,0) scale(1); }
            12%  { transform: translate(4px,-14px) scale(1.1) rotate(-2deg); }
            40%  { transform: translate(-2px,3px) scale(.97) rotate(.5deg); }
            100% { transform: translate(0,0) scale(1); }
        }

        /* Capa de impactos */
        .impact-layer { position: absolute; inset: 0; z-index: 1; }

        .muzzle-flash {
            position: absolute;
            width: 140px; height: 140px;
            margin: -70px 0 0 -70px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,.95) 0%, rgba(255,236,170,.8) 15%, rgba(245,158,11,.45) 35%, transparent 65%);
            animation: flash .28s ease-out forwards;
            mix-blend-mode: screen;
        }
        @keyframes flash {
            0%   { transform: scale(.2); opacity: 1; }
            100% { transform: scale(1.4); opacity: 0; }
        }

        .shock-ring {
            position: absolute;
            width: 160px; height: 160px;
            margin: -80px 0 0 -80px;
            border-radius: 50%;
            border: 2px solid rgba(252,211,77,.8);
            animation: shock .75s cubic-bezier(.1,.7,.3,1) forwards;
        }
        @keyframes shock {
            0%   { transform: scale(.1); opacity: 1; border-width: 4px; }
            100% { transform: scale(1.6); opacity: 0; border-width: 1px; }
        }

        .bullet-hole {
            position: absolute;
            width: 46px; height: 46px;
            margin: -23px 0 0 -23px;
            animation: holeLife 7s ease-in forwards;
        }
        .bullet-hole::before {
            content: '';
            position: absolute;
            inset: 15px;
            border-radius: 50%;
            background: radial-gradient(circle, #000 0%, #0a0703 45%, #3b2a0c 65%, rgba(191,149,63,.9) 80%, transparent 100%);
            box-shadow: 0 0 10px 2px rgba(245,158,11,.55), inset 0 0 4px #000;
        }
        .bullet-hole svg { position: absolute; inset: 0; width: 100%; height: 100%; }
        @keyframes holeLife {
            0%   { opacity: 0; transform: scale(1.6); }
            4%   { opacity: 1; transform: scale(1); }
            75%  { opacity: .85; }
            100% { opacity: 0; }
        }

        .spark {
            position: absolute;
            width: 3px; height: 3px;
            border-radius: 50%;
            background: #FFE9A8;
            box-shadow: 0 0 6px 2px rgba(245,158,11,.9);
            animation: sparkFly .55s ease-out forwards;
        }
        @keyframes sparkFly {
            0%   { transform: translate(0,0) scale(1); opacity: 1; }
            100% { transform: translate(var(--dx), var(--dy)) scale(.2); opacity: 0; }
        }

        /* Sacudida sutil de la escena con cada disparo */
        .bg-scene.shake { animation: sceneShake .25s ease-out; }
        @keyframes sceneShake {
            0%,100% { transform: translate(0,0); }
            25%     { transform: translate(-3px,2px); }
            50%     { transform: translate(2px,-2px); }
            75%     { transform: translate(-1px,1px); }
        }

        /* Radar con barrido */
        .radar {
            position: absolute;
            width: 460px; height: 460px;
            bottom: -120px; right: -100px;
            border-radius: 50%;
            opacity: .35;
        }
        .radar-rings { position: absolute; inset: 0; width: 100%; height: 100%; }
        .radar-sweep {
            position: absolute;
            inset: 2%;
            border-radius: 50%;
            background: conic-gradient(from 0deg, rgba(245,158,11,.55) 0deg, rgba(245,158,11,.12) 40deg, transparent 75deg, transparent 360deg);
            animation: spin 7s linear infinite;
        }
        .radar-blip {
            position: absolute;
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #FCD34D;
            box-shadow: 0 0 12px 3px rgba(252,211,77,.8);
            opacity: 0;
            animation: blip 7s ease-out infinite;
        }
        @keyframes blip {
            0%   { opacity: 0;  transform: scale(.4); }
            6%   { opacity: 1;  transform: scale(1.3); }
            40%  { opacity: .2; transform: scale(1); }
            100% { opacity: 0; }
        }

        /* Haces de luz diagonales */
        .light-beam {
            position: absolute;
            width: 160%;
            height: 120px;
            left: -30%;
            background: linear-gradient(90deg, transparent 0%, rgba(252,211,77,.07) 45%, rgba(255,250,230,.12) 50%, rgba(252,211,77,.07) 55%, transparent 100%);
            filter: blur(14px);
            transform: rotate(-24deg);
        }
        .beam-1 { top: 10%; animation: beamSweep 14s ease-in-out infinite; }
        .beam-2 { top: 60%; animation: beamSweep 18s ease-in-out infinite 5s; }
        @keyframes beamSweep {
            0%,100% { transform: rotate(-24deg) translateX(-40%); opacity: 0; }
            20%     { opacity: 1; }
            50%     { transform: rotate(-24deg) translateX(40%); opacity: .9; }
            80%     { opacity: 0; }
        }

        #goldDust { position: absolute; inset: 0; width: 100%; height: 100%; }

        .vignette {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at center, transparent 35%, rgba(3,5,10,.75) 100%);
        }

        @media (max-width: 640px) {
            .scope { width: 170px; height: 170px; }
            .radar { width: 300px; height: 300px; }
        }

        /* Accesibilidad: sin animaciones si el usuario lo prefiere */
        @media (prefers-reduced-motion: reduce) {
            .bg-scene *, .gold-prestige-title { animation: none !important; }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden selection:bg-amber-500 selection:text-black">

    <!-- =========================================================================
         FONDO DINÁMICO ANIMADO: AURORA DORADA, MIRAS DE POLÍGONO, RADAR Y PARTÍCULAS
         ========================================================================= -->
    <div class="bg-scene" aria-hidden="true">
        <!-- 1. Aurora: orbes de luz dorada que se desplazan lentamente -->
        <div class="aurora-orb orb-1"></div>
        <div class="aurora-orb orb-2"></div>
        <div class="aurora-orb orb-3"></div>

        <!-- 2. Malla en perspectiva que avanza (pista del polígono) -->
        <div class="grid-floor"></div>

        <!-- 3. Mira telescópica que apunta, fija el blanco y dispara -->
        <div class="scope" id="scope">
            <svg viewBox="0 0 400 400" fill="none">
                <g class="spin-slow">
                    <circle cx="200" cy="200" r="190" stroke="#BF953F" stroke-width="1.5" stroke-dasharray="3 9"/>
                    <circle cx="200" cy="200" r="150" stroke="#BF953F" stroke-width="1.8"/>
                </g>
                <g class="spin-reverse">
                    <circle cx="200" cy="200" r="110" stroke="#F59E0B" stroke-width="1.5" stroke-dasharray="40 14"/>
                    <circle cx="200" cy="200" r="70" stroke="#BF953F" stroke-width="1.5"/>
                </g>
                <circle cx="200" cy="200" r="46" stroke="#FCD34D" stroke-width="2.5" class="lock-ring"/>
                <circle cx="200" cy="200" r="22" stroke="#FCD34D" stroke-width="2" class="pulse-core"/>
                <path d="M200 10V170M200 230V390M10 200H170M230 200H390" stroke="#BF953F" stroke-width="1.4"/>
                <path d="M185 200H215M200 185V215" stroke="#FCD34D" stroke-width="1.6"/>
                <circle cx="200" cy="200" r="3" fill="#EF4444" class="aim-dot"/>
            </svg>
        </div>

        <!-- Capa de impactos (agujeros de bala, fogonazos, chispas) -->
        <div id="impactLayer" class="impact-layer"></div>

        <!-- 4. Radar con barrido (derecha inferior) -->
        <div class="radar">
            <svg viewBox="0 0 400 400" fill="none" class="radar-rings">
                <circle cx="200" cy="200" r="195" stroke="#BF953F" stroke-width="1"/>
                <circle cx="200" cy="200" r="140" stroke="#BF953F" stroke-width="0.8" stroke-dasharray="4 6"/>
                <circle cx="200" cy="200" r="85" stroke="#BF953F" stroke-width="0.8"/>
                <path d="M200 5V395M5 200H395" stroke="#BF953F" stroke-width="0.6"/>
            </svg>
            <div class="radar-sweep"></div>
            <span class="radar-blip" style="top:30%;left:62%;animation-delay:.6s"></span>
            <span class="radar-blip" style="top:66%;left:28%;animation-delay:2.4s"></span>
            <span class="radar-blip" style="top:48%;left:78%;animation-delay:3.8s"></span>
        </div>

        <!-- 5. Haces de luz diagonales que cruzan la escena -->
        <div class="light-beam beam-1"></div>
        <div class="light-beam beam-2"></div>

        <!-- 6. Partículas doradas (polvo de oro) -->
        <canvas id="goldDust"></canvas>

        <!-- 7. Viñeta para centrar la atención en la tarjeta -->
        <div class="vignette"></div>
    </div>

    <div class="w-full max-w-md relative z-10 space-y-6 my-6">
        
        <!-- CABECERA INSTITUCIONAL: LOGO FLOTANTE SIN RECUADRO & COUNTRY CLUB CON BRILLO DE ORO SERIO -->
        <div class="text-center space-y-4">
            
            <!-- Logotipo Oficial Libre y Flotante (100% SIN RECUADRO NI MARCOS) -->
            <div class="relative mx-auto inline-flex items-center justify-center select-none pt-1">
                <!-- Aura de Luz Dorada Suave en el Fondo del Logo -->
                <div class="absolute -inset-6 bg-gradient-to-tr from-amber-500/15 via-amber-400/10 to-transparent rounded-full blur-2xl pointer-events-none"></div>
                
                <img src="{{ asset('img/logo.png') }}" 
                     alt="SCOCC Polígono de Tiro - Country Club C.A." 
                     class="relative z-10 h-20 sm:h-24 w-auto object-contain logo-floating pointer-events-auto">
            </div>

            <!-- Título con Reflejo de Oro Sólido Serio (Sin estrellitas) -->
            <div class="space-y-1.5">
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight gold-prestige-title uppercase select-none">
                    COUNTRY CLUB
                </h1>

                <p class="text-xs font-bold uppercase tracking-[0.22em] text-amber-400/80 flex items-center justify-center space-x-2">
                    <span class="inline-block w-4 h-px bg-amber-400/30"></span>
                    <span>Polígono de Tiro &bull; Portal Directivo</span>
                    <span class="inline-block w-4 h-px bg-amber-400/30"></span>
                </p>
            </div>

            <!-- Badge Ejecutivo de la Consola -->
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full gold-badge shadow-sm">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-amber-200">
                    Consola de Control &amp; Gestión Administrativa
                </span>
            </div>
        </div>

        <!-- TARJETA DE LOGIN GLASSMORPHIC -->
        <div class="glass-login rounded-3xl p-7 sm:p-9 space-y-5">
            
            <div class="space-y-1 border-b border-amber-500/15 pb-4">
                <h2 class="text-base sm:text-lg font-bold text-white tracking-tight flex items-center space-x-2">
                    <span class="material-icons-round text-amber-400 text-xl">vpn_key</span>
                    <span>Acceso de Seguridad</span>
                </h2>
                <p class="text-xs text-slate-400">
                    Autenticación exclusiva para directivos, secretaría y garita de control.
                </p>
            </div>

            @if(session('error'))
            <div class="p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs flex items-start space-x-2.5">
                <span class="material-icons-round text-amber-400 text-base mt-0.5">warning</span>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
            @endif

            @if(isset($errors) && $errors->any())
            <div class="p-3.5 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-start space-x-2.5">
                <span class="material-icons-round text-rose-400 text-base mt-0.5">error_outline</span>
                <div class="space-y-0.5">
                    @foreach($errors->all() as $error)
                        <p class="font-medium">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            <div id="loginErrorBox" class="hidden p-3.5 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-start space-x-2.5">
                <span class="material-icons-round text-rose-400 text-base mt-0.5">error_outline</span>
                <p id="loginErrorText" class="font-medium"></p>
            </div>

            <form action="{{ route('login.post', [], false) }}" method="POST" id="loginForm" class="space-y-4">
                @csrf

                <!-- Correo Electrónico -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-semibold text-slate-300">
                        Correo Electrónico
                    </label>
                    <div class="relative">
                        <span class="material-icons-round absolute left-3.5 top-3.5 text-amber-400/70 text-lg">alternate_email</span>
                        <input type="email" name="email" id="email" value="{{ old('email', 'admin@countryclub.com') }}" required autofocus
                               class="w-full bg-[#080D1A] border border-[#22304A] rounded-2xl pl-11 pr-4 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition">
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-semibold text-slate-300">
                            Contraseña
                        </label>
                    </div>
                    <div class="relative">
                        <span class="material-icons-round absolute left-3.5 top-3.5 text-amber-400/70 text-lg">lock</span>
                        <input type="password" name="password" id="password" value="password" required
                               class="w-full bg-[#080D1A] border border-[#22304A] rounded-2xl pl-11 pr-11 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition">
                        <button type="button" onclick="togglePassword()" class="absolute right-3.5 top-3.5 text-slate-400 hover:text-amber-400 transition" title="Ver contraseña">
                            <span class="material-icons-round text-lg" id="eyeIcon">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- Recordar sesión & Switch de Roles Rápidos -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center space-x-2 cursor-pointer text-slate-400 hover:text-slate-300 transition">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-[#080D1A] border-[#22304A] text-amber-500 focus:ring-0">
                        <span>Recordar sesión</span>
                    </label>

                    <span class="text-[11px] text-amber-400/90 font-medium flex items-center space-x-1">
                        <span class="material-icons-round text-xs">bolt</span>
                        <span>Roles Demo:</span>
                    </span>
                </div>

                <!-- Botones de Autocompletado Rápido por Rol (Admin / Secretaría / Vigilancia) -->
                <div class="grid grid-cols-3 gap-2 pt-0.5">
                    <button type="button" onclick="setRole('admin@countryclub.com', 'password')" class="px-2 py-1.5 rounded-xl bg-[#0F172A] hover:bg-[#1A253E] border border-amber-500/25 text-[10px] font-bold text-amber-300 transition text-center active:scale-95">
                        Admin
                    </button>
                    <button type="button" onclick="setRole('secretaria@countryclub.com', 'password')" class="px-2 py-1.5 rounded-xl bg-[#0F172A] hover:bg-[#1A253E] border border-slate-700 text-[10px] font-bold text-slate-300 hover:text-white transition text-center active:scale-95">
                        Secretaría
                    </button>
                    <button type="button" onclick="setRole('vigilancia@countryclub.com', 'password')" class="px-2 py-1.5 rounded-xl bg-[#0F172A] hover:bg-[#1A253E] border border-slate-700 text-[10px] font-bold text-slate-300 hover:text-white transition text-center active:scale-95">
                        Garita
                    </button>
                </div>

                <!-- Botón de Acceso Principal Dorado -->
                <button type="submit" id="btnLoginSubmit" class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-amber-500 via-amber-400 to-amber-500 hover:from-amber-400 hover:to-amber-300 text-slate-950 font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-amber-500/25 active:scale-[0.98] transition flex items-center justify-center space-x-2 mt-2">
                    <span id="btnLoginText">Ingresar al Sistema</span>
                    <span class="material-icons-round text-base" id="btnLoginIcon">arrow_forward</span>
                </button>
            </form>

            <!-- Footer de Seguridad & Conexión Encriptada -->
            <div class="pt-4 border-t border-amber-500/15 flex items-center justify-center space-x-2 text-[10px] text-slate-400">
                <span class="material-icons-round text-xs text-emerald-400">verified_user</span>
                <span>Conexión Segura Encriptada • Country Club C.A.</span>
            </div>
        </div>

        <!-- Copyright Oficial -->
        <p class="text-center text-[11px] text-slate-500">
            &copy; {{ date('Y') }} Country Club C.A. &bull; Polígono de Tiro. Todos los derechos reservados.
        </p>

    </div>

    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                pwd.type = 'password';
                icon.textContent = 'visibility';
            }
        }

        function setRole(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }

        // Envío instantáneo por AJAX con auto-reintento anti-bloqueo de sockets Keep-Alive
        (function () {
            const form = document.getElementById('loginForm');
            const btn = document.getElementById('btnLoginSubmit');
            const text = document.getElementById('btnLoginText');
            const icon = document.getElementById('btnLoginIcon');
            const errBox = document.getElementById('loginErrorBox');
            const errText = document.getElementById('loginErrorText');
            if (!form || !btn) return;

            function resetButton() {
                window.__loginSubmitting = false;
                btn.disabled = false;
                btn.classList.remove('opacity-80', 'cursor-wait');
                if (text) text.textContent = 'Ingresar al Sistema';
                if (icon) {
                    icon.textContent = 'arrow_forward';
                    icon.classList.remove('animate-spin');
                }
            }

            async function sendLoginAttempt(formData, timeoutMs) {
                const controller = new AbortController();
                const timer = setTimeout(() => controller.abort(), timeoutMs);
                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        signal: controller.signal
                    });
                    clearTimeout(timer);
                    return res;
                } catch (err) {
                    clearTimeout(timer);
                    throw err;
                }
            }

            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                if (window.__loginSubmitting) return;

                window.__loginSubmitting = true;
                btn.disabled = true;
                btn.classList.add('opacity-80', 'cursor-wait');
                if (errBox) errBox.classList.add('hidden');
                if (text) text.textContent = 'Ingresando al Sistema...';
                if (icon) {
                    icon.textContent = 'sync';
                    icon.classList.add('animate-spin');
                }

                const formData = new FormData(form);

                try {
                    let res;
                    try {
                        // Primer intento rápido (2.5s máx para romper cualquier socket Keep-Alive colgado)
                        res = await sendLoginAttempt(formData, 2500);
                    } catch (firstErr) {
                        // Reintento inmediato en un socket TCP limpio
                        res = await sendLoginAttempt(formData, 6000);
                    }

                    if (res.redirected && res.url) {
                        window.location.replace(res.url);
                        return;
                    }

                    const data = await res.json().catch(() => null);

                    if (res.ok && data && data.redirect) {
                        window.location.replace(data.redirect);
                        return;
                    }

                    const msg = (data && (data.message || (data.errors && Object.values(data.errors)[0]?.[0])))
                        || 'Las credenciales proporcionadas no coinciden con nuestros registros.';
                    if (errBox && errText) {
                        errText.textContent = msg;
                        errBox.classList.remove('hidden');
                    }
                    resetButton();
                } catch (err) {
                    // Fallback directo si falla fetch
                    form.submit();
                }
            });

            window.addEventListener('pageshow', function (e) {
                if (e.persisted) resetButton();
            });
        })();

        // Polvo de oro: partículas doradas flotando con parallax suave al mover el ratón
        (function () {
            const canvas = document.getElementById('goldDust');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            let w, h, dpr, particles = [];
            let mx = 0, my = 0, tx = 0, ty = 0;

            function resize() {
                dpr = Math.min(window.devicePixelRatio || 1, 1.5);
                w = canvas.clientWidth; h = canvas.clientHeight;
                canvas.width = w * dpr; canvas.height = h * dpr;
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
                const count = Math.round(Math.min(45, (w * h) / 24000));
                particles = Array.from({ length: count }, () => spawn(true));
            }

            function spawn(initial) {
                const depth = Math.random() * 0.8 + 0.2; // 0.2 lejos — 1 cerca
                return {
                    x: Math.random() * w,
                    y: initial ? Math.random() * h : h + 10,
                    r: depth * 2.2 + 0.3,
                    vy: -(depth * 0.35 + 0.08),
                    vx: (Math.random() - 0.5) * 0.15,
                    a: Math.random() * 0.5 + 0.25,
                    tw: Math.random() * Math.PI * 2,
                    depth
                };
            }

            window.addEventListener('mousemove', e => {
                tx = (e.clientX / w - 0.5) * 30;
                ty = (e.clientY / h - 0.5) * 30;
            });
            window.addEventListener('resize', resize);
            resize();

            function frame() {
                if (window.__loginSubmitting) return;
                mx += (tx - mx) * 0.05; my += (ty - my) * 0.05;
                ctx.clearRect(0, 0, w, h);
                for (let i = 0; i < particles.length; i++) {
                    const p = particles[i];
                    p.y += p.vy; p.x += p.vx; p.tw += 0.03;
                    if (p.y < -10) particles[i] = spawn(false);
                    const alpha = p.a * (0.6 + 0.4 * Math.sin(p.tw));
                    const px = p.x + mx * p.depth, py = p.y + my * p.depth;
                    const g = ctx.createRadialGradient(px, py, 0, px, py, p.r * 4);
                    g.addColorStop(0, `rgba(255, 236, 170, ${alpha})`);
                    g.addColorStop(0.4, `rgba(245, 158, 11, ${alpha * 0.5})`);
                    g.addColorStop(1, 'rgba(245, 158, 11, 0)');
                    ctx.fillStyle = g;
                    ctx.beginPath();
                    ctx.arc(px, py, p.r * 4, 0, Math.PI * 2);
                    ctx.fill();
                }
                if (!reduce && !window.__loginSubmitting) requestAnimationFrame(frame);
            }
            frame();
        })();

        // Mira de polígono: apunta → fija blanco → dispara (retroceso, fogonazo, onda, chispas e impacto)
        (function () {
            const scope = document.getElementById('scope');
            const layer = document.getElementById('impactLayer');
            if (!scope || !layer) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { scope.style.display = 'none'; return; }

            const MAX_HOLES = 12;
            const rand = (a, b) => a + Math.random() * (b - a);
            const wait = ms => new Promise(r => setTimeout(r, ms));

            // Elige un blanco fuera de la columna central (donde está la tarjeta de login)
            function pickTarget() {
                const w = window.innerWidth, h = window.innerHeight;
                if (w < 768) {
                    return { x: rand(.12, .88) * w, y: Math.random() < .5 ? rand(.04, .12) * h : rand(.88, .96) * h };
                }
                const left = Math.random() < .5;
                return { x: (left ? rand(.06, .27) : rand(.73, .94)) * w, y: rand(.12, .88) * h };
            }

            function aimAt(p) {
                const s = scope.offsetWidth / 2;
                scope.style.transform = `translate(${p.x - s}px, ${p.y - s}px)`;
            }

            function spawn(cls, x, y, life) {
                const el = document.createElement('div');
                el.className = cls;
                el.style.left = x + 'px';
                el.style.top = y + 'px';
                layer.appendChild(el);
                if (life) setTimeout(() => el.remove(), life);
                return el;
            }

            function bulletHole(x, y) {
                const hole = spawn('bullet-hole', x, y, 7000);
                // Grietas radiales irregulares alrededor del impacto
                let cracks = '';
                const n = 5 + Math.floor(Math.random() * 4);
                for (let i = 0; i < n; i++) {
                    const a = (i / n) * Math.PI * 2 + rand(-.3, .3);
                    const r1 = 9, r2 = rand(15, 23);
                    cracks += `<line x1="${23 + Math.cos(a) * r1}" y1="${23 + Math.sin(a) * r1}" x2="${23 + Math.cos(a) * r2}" y2="${23 + Math.sin(a) * r2}" stroke="rgba(252,211,77,.55)" stroke-width="${rand(.6, 1.2).toFixed(2)}" stroke-linecap="round"/>`;
                }
                hole.innerHTML = `<svg viewBox="0 0 46 46">${cracks}</svg>`;
                const holes = layer.querySelectorAll('.bullet-hole');
                if (holes.length > MAX_HOLES) holes[0].remove();
            }

            function fire(x, y) {
                if (window.__loginSubmitting) return;
                scope.classList.remove('firing');
                void scope.offsetWidth; // reinicia la animación de retroceso
                scope.classList.add('firing');

                spawn('muzzle-flash', x, y, 300);
                spawn('shock-ring', x, y, 800);
                bulletHole(x, y);
                for (let i = 0; i < 8; i++) {
                    const s = spawn('spark', x, y, 600);
                    const a = Math.random() * Math.PI * 2, d = rand(25, 70);
                    s.style.setProperty('--dx', Math.cos(a) * d + 'px');
                    s.style.setProperty('--dy', Math.sin(a) * d + 'px');
                }
            }

            async function loop() {
                await wait(800);
                while (!window.__loginSubmitting) {
                    if (document.hidden) { await wait(1000); continue; }
                    const t = pickTarget();
                    aimAt(t);
                    await wait(1800);                 // desplazamiento de la mira
                    if (window.__loginSubmitting) break;
                    scope.classList.add('locking');   // fija el blanco
                    await wait(rand(450, 750));
                    if (window.__loginSubmitting) break;
                    fire(t.x, t.y);
                    // A veces doble disparo (double tap) con leve dispersión
                    if (Math.random() < .35) {
                        await wait(220);
                        fire(t.x + rand(-9, 9), t.y + rand(-9, 9));
                    }
                    await wait(450);
                    scope.classList.remove('locking', 'firing');
                    await wait(rand(500, 1100));
                }
            }
            loop();
        })();
    </script>
</body>
</html>
