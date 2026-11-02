<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Seguro | Country Club C.A. - Portal Directivo</title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Cinzel Luxury Serif -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Compiled Static Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #060911;
            color: #F3F4F6;
        }

        .ambient-glow-amber {
            position: absolute;
            width: 650px;
            height: 650px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.14) 0%, rgba(217, 119, 6, 0.05) 45%, rgba(0, 0, 0, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .ambient-glow-gold {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(251, 191, 36, 0.12) 0%, rgba(180, 83, 9, 0.04) 50%, rgba(0, 0, 0, 0) 75%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .glass-login {
            background: rgba(11, 16, 29, 0.82);
            backdrop-filter: blur(28px);
            -webkit-backdrop-filter: blur(28px);
            border: 1px solid rgba(245, 158, 11, 0.22);
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.85), 
                        0 0 35px -5px rgba(245, 158, 11, 0.08), 
                        inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        /* EFECTO DE BRILLO DORADO METÁLICO (SHIMMER & GLEAM) */
        .gold-shimmer-title {
            font-family: 'Cinzel', serif;
            background: linear-gradient(
                110deg,
                #92400E 0%,
                #D97706 18%,
                #FBBF24 35%,
                #FFFDF0 48%,
                #FFFFFF 50%,
                #FFFDF0 52%,
                #FBBF24 65%,
                #D97706 82%,
                #92400E 100%
            );
            background-size: 260% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: goldShimmerSweep 4s linear infinite, goldAuraGlow 4s ease-in-out infinite;
            letter-spacing: 0.07em;
            display: inline-block;
            position: relative;
        }

        @keyframes goldShimmerSweep {
            0% {
                background-position: 130% center;
            }
            100% {
                background-position: -130% center;
            }
        }

        @keyframes goldAuraGlow {
            0%, 100% {
                filter: drop-shadow(0 0 12px rgba(245, 158, 11, 0.35));
            }
            50% {
                filter: drop-shadow(0 0 28px rgba(251, 191, 36, 0.75)) drop-shadow(0 0 8px rgba(255, 255, 255, 0.6));
            }
        }

        /* Destello / Sparkle brillante en la esquina del título */
        .sparkle-flare {
            position: absolute;
            top: -6px;
            right: -15px;
            width: 18px;
            height: 18px;
            pointer-events: none;
            opacity: 0.85;
            animation: sparklePulse 3.5s ease-in-out infinite;
        }

        @keyframes sparklePulse {
            0%, 100% {
                transform: scale(0.6) rotate(0deg);
                opacity: 0.35;
            }
            50% {
                transform: scale(1.25) rotate(45deg);
                opacity: 1;
                filter: drop-shadow(0 0 8px #FDE68A);
            }
        }

        /* Logotipo flotante puro sin recuadro ni marcos */
        .logo-floating {
            filter: drop-shadow(0 0 22px rgba(245, 158, 11, 0.45)) drop-shadow(0 8px 16px rgba(0, 0, 0, 0.85));
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), filter 0.35s ease;
        }

        .logo-floating:hover {
            transform: scale(1.06) translateY(-3px);
            filter: drop-shadow(0 0 34px rgba(245, 158, 11, 0.75)) drop-shadow(0 12px 24px rgba(0, 0, 0, 0.95));
        }

        .gold-badge {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.18) 0%, rgba(180, 83, 9, 0.08) 100%);
            border: 1px solid rgba(245, 158, 11, 0.35);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden selection:bg-amber-500 selection:text-black">

    <!-- Ambient Golden Lighting Effects -->
    <div class="ambient-glow-amber -top-44 -left-44"></div>
    <div class="ambient-glow-gold -bottom-44 -right-44"></div>
    <div class="absolute inset-0 bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:32px_32px] opacity-25 pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10 space-y-6 my-6">
        
        <!-- CABECERA INSTITUCIONAL: LOGO FLOTANTE SIN RECUADRO & COUNTRY CLUB CON BRILLO DORADO -->
        <div class="text-center space-y-4">
            
            <!-- Logotipo Oficial Libre y Flotante (100% SIN RECUADRO NI MARCOS) -->
            <div class="relative mx-auto inline-flex items-center justify-center select-none pt-1">
                <!-- Aura de Luz Dorada Atmosférica Difuminada -->
                <div class="absolute -inset-6 bg-gradient-to-tr from-amber-500/20 via-amber-400/15 to-transparent rounded-full blur-2xl pointer-events-none"></div>
                
                <img src="{{ asset('img/logo.png') }}" 
                     alt="SCOCC Polígono de Tiro - Country Club C.A." 
                     class="relative z-10 h-20 sm:h-24 w-auto object-contain logo-floating pointer-events-auto">
            </div>

            <!-- Título con Efecto de Brillo / Destello Dorado Metálico Vivo -->
            <div class="space-y-1.5">
                <div class="relative inline-block">
                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight gold-shimmer-title uppercase select-none">
                        COUNTRY CLUB
                    </h1>
                    <!-- Destello brillante en la esquina del título -->
                    <svg class="sparkle-flare" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 0L14.5 9.5L24 12L14.5 14.5L12 24L9.5 14.5L0 12L9.5 9.5L12 0Z" fill="url(#goldSparkleGrad)" />
                        <defs>
                            <linearGradient id="goldSparkleGrad" x1="0" y1="0" x2="24" y2="24" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FFFFFF" />
                                <stop offset="0.5" stop-color="#FCD34D" />
                                <stop offset="1" stop-color="#F59E0B" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>

                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-amber-400/90 flex items-center justify-center space-x-2">
                    <span class="inline-block w-4 h-px bg-amber-400/40"></span>
                    <span>Polígono de Tiro &bull; Portal Directivo</span>
                    <span class="inline-block w-4 h-px bg-amber-400/40"></span>
                </p>
            </div>

            <!-- Badge Ejecutivo de la Consola (Sin tasa BCV como solicitó el usuario) -->
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full gold-badge shadow-sm">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-200">
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

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
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
                <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-amber-500 via-amber-400 to-amber-500 hover:from-amber-400 hover:to-amber-300 text-slate-950 font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-amber-500/25 active:scale-[0.98] transition flex items-center justify-center space-x-2 mt-2">
                    <span>Ingresar al Sistema</span>
                    <span class="material-icons-round text-base">arrow_forward</span>
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
    </script>
</body>
</html>
