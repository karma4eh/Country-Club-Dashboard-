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

        .gold-gradient-text {
            background: linear-gradient(135deg, #FFFDF5 0%, #FCD34D 35%, #F59E0B 70%, #B45309 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gold-logo-glow {
            filter: drop-shadow(0 0 16px rgba(245, 158, 11, 0.5)) drop-shadow(0 6px 14px rgba(0, 0, 0, 0.7));
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), filter 0.3s ease;
        }

        .gold-logo-glow:hover {
            transform: scale(1.04);
            filter: drop-shadow(0 0 24px rgba(245, 158, 11, 0.75)) drop-shadow(0 8px 18px rgba(0, 0, 0, 0.8));
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
        
        <!-- CABECERA INSTITUCIONAL CON LOGO EN DORADO (COUNTRY CLUB • POLÍGONO DE TIRO) -->
        <div class="text-center space-y-3.5">
            
            <!-- Emblema de Prestigio con Logotipo Oficial en Aureola Dorada -->
            <div class="relative mx-auto inline-flex items-center justify-center">
                <div class="relative p-3.5 sm:p-4 rounded-3xl bg-gradient-to-b from-[#1C253B]/90 via-[#101728]/95 to-[#0A0E1A] border border-amber-500/40 shadow-2xl shadow-amber-500/15 group">
                    <!-- Resplandor Dorado de Fondo -->
                    <div class="absolute inset-0 rounded-3xl bg-amber-400/15 blur-lg group-hover:bg-amber-400/25 transition duration-500 pointer-events-none"></div>
                    
                    <!-- Logotipo Oficial del Country Club / Polígono de Tiro -->
                    <img src="{{ asset('img/logo.png') }}" 
                         alt="Country Club C.A. - Polígono de Tiro" 
                         class="relative z-10 h-16 sm:h-20 w-auto object-contain gold-logo-glow select-none">
                </div>
            </div>

            <!-- Título Institucional y Subtítulo -->
            <div class="space-y-1">
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight font-serif gold-gradient-text uppercase">
                    COUNTRY CLUB
                </h1>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-amber-400/90">
                    Polígono de Tiro &bull; Portal Directivo
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
