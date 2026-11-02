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
            0%, 100% {
                background-position: 0% center;
                filter: drop-shadow(0 3px 5px rgba(0, 0, 0, 0.95)) 
                        drop-shadow(0 0 14px rgba(191, 149, 63, 0.3));
            }
            50% {
                background-position: 100% center;
                filter: drop-shadow(0 3px 5px rgba(0, 0, 0, 0.95)) 
                        drop-shadow(0 0 24px rgba(251, 245, 183, 0.65));
            }
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
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden selection:bg-amber-500 selection:text-black">

    <!-- =========================================================================
         FONDO AMBIENTAL DE LUJO: CELOSÍA GEOMÉTRICA & ONDAS ARQUITECTÓNICAS DORADAS
         ========================================================================= -->
    <div class="fixed inset-0 pointer-events-none select-none overflow-hidden z-0">
        <!-- Haz de Luz Cenital (Spotlight Club Directivo) -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-5xl h-[550px] bg-[radial-gradient(ellipse_at_top,rgba(217,119,6,0.18)_0%,rgba(191,149,63,0.06)_40%,transparent_70%)] pointer-events-none"></div>
        
        <!-- Foco ambiental inferior derecho -->
        <div class="absolute -bottom-24 -right-24 w-[600px] h-[600px] bg-[radial-gradient(circle,rgba(245,158,11,0.08)_0%,rgba(16,185,129,0.03)_40%,transparent_70%)] pointer-events-none"></div>

        <!-- Malla de Micro-Celosía Geométrica Texturizada -->
        <svg class="absolute inset-0 w-full h-full opacity-[0.045]" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
            <defs>
                <pattern id="luxury-geometric-grid" width="48" height="48" patternUnits="userSpaceOnUse">
                    <path d="M 48 0 L 0 48 M 0 0 L 48 48" fill="none" stroke="#F59E0B" stroke-width="0.8"/>
                    <circle cx="24" cy="24" r="1.5" fill="#FBBF24" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#luxury-geometric-grid)"/>
        </svg>

        <!-- Ondas Curvas Fluidas y Círculos Concéntricos (Arquitectura de Prestigio) -->
        <svg class="absolute -top-16 -left-20 w-[680px] h-[680px] opacity-[0.14] text-amber-500" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="250" cy="250" r="235" stroke="currentColor" stroke-width="1.2" stroke-dasharray="4 8" />
            <circle cx="250" cy="250" r="185" stroke="currentColor" stroke-width="1" />
            <circle cx="250" cy="250" r="135" stroke="currentColor" stroke-width="0.8" stroke-dasharray="2 6" />
            <path d="M40 250 C120 170 210 140 360 190 C430 220 480 310 500 400" stroke="currentColor" stroke-width="2" />
            <path d="M0 320 C140 250 280 270 420 380" stroke="currentColor" stroke-width="1.5" />
        </svg>

        <svg class="absolute -bottom-24 -right-16 w-[720px] h-[720px] opacity-[0.12] text-amber-400" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="250" cy="250" r="225" stroke="currentColor" stroke-width="1.5" />
            <circle cx="250" cy="250" r="165" stroke="currentColor" stroke-width="1" stroke-dasharray="6 6" />
            <path d="M0 140 C160 210 310 190 500 310" stroke="currentColor" stroke-width="2" />
            <path d="M60 40 C190 130 310 170 460 290" stroke="currentColor" stroke-width="1.5" />
        </svg>

        <!-- Resplandor central difuminado que realza la tarjeta -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[650px] h-[650px] bg-gradient-to-b from-amber-500/10 via-transparent to-transparent rounded-full blur-3xl pointer-events-none"></div>
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
