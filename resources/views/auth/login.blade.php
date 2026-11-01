<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Seguro | Country Club - Portal Ejecutivo</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Cinzel:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Compiled Static Tailwind CSS (Ultra-Fast) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #070A12;
            color: #F3F4F6;
        }

        .ambient-glow-amber {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.12) 0%, rgba(245, 158, 11, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .ambient-glow-emerald {
            position: absolute;
            width: 550px;
            height: 550px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, rgba(16, 185, 129, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .glass-login {
            background: rgba(14, 20, 36, 0.78);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .gold-gradient-text {
            background: linear-gradient(135deg, #FFFBEB 0%, #FBBF24 50%, #D97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden selection:bg-amber-500 selection:text-black">

    <!-- Ambient Lighting Effects -->
    <div class="ambient-glow-amber -top-40 -left-40"></div>
    <div class="ambient-glow-emerald -bottom-40 -right-40"></div>
    <div class="absolute inset-0 bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:28px_28px] opacity-20 pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10 space-y-6">
        
        <!-- Logo y Cabecera de Prestigio -->
        <div class="text-center space-y-3">
            <div class="inline-flex p-3 rounded-2xl bg-gradient-to-br from-amber-500/20 via-[#18233D] to-transparent border border-amber-500/30 shadow-xl shadow-amber-500/5">
                <span class="material-icons-round text-amber-400 text-4xl">shield</span>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight font-serif gold-gradient-text">
                    COUNTRY CLUB
                </h1>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400 mt-1">
                    Portal Administrativo & Gestión de Miembros
                </p>
            </div>

            <!-- Tasa BCV Oficial en Tiempo Real -->
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-[#101827] border border-amber-500/30 shadow-inner">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Tasa BCV Oficial:</span>
                <span class="text-xs font-mono font-bold text-amber-300">
                    {{ number_format($tasa ?? 889.26, 2, ',', '.') }} Bs / USD
                </span>
            </div>
        </div>

        <!-- Tarjeta de Login Glassmorphic -->
        <div class="glass-login rounded-3xl p-8 sm:p-9 space-y-6">
            
            <div class="space-y-1">
                <h2 class="text-lg font-bold text-white tracking-tight">Iniciar Sesión</h2>
                <p class="text-xs text-slate-400">Ingrese sus credenciales autorizadas para acceder a la consola directiva.</p>
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
                        <span class="material-icons-round absolute left-3.5 top-3 text-slate-400 text-lg">alternate_email</span>
                        <input type="email" name="email" id="email" value="{{ old('email', 'admin@countryclub.com') }}" required autofocus
                               class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl pl-11 pr-4 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition">
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
                        <span class="material-icons-round absolute left-3.5 top-3 text-slate-400 text-lg">lock</span>
                        <input type="password" name="password" id="password" value="password" required
                               class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl pl-11 pr-11 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition">
                        <button type="button" onclick="togglePassword()" class="absolute right-3.5 top-3 text-slate-400 hover:text-white transition">
                            <span class="material-icons-round text-lg" id="eyeIcon">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- Recordar sesión & Demo Fill -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center space-x-2 cursor-pointer text-slate-400 hover:text-slate-300 transition">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-[#0A0F1E] border-[#243352] text-amber-500 focus:ring-0">
                        <span>Recordar dispositivo</span>
                    </label>

                    <button type="button" onclick="fillDemo()" class="text-amber-400 hover:text-amber-300 font-semibold transition text-[11px] flex items-center space-x-1">
                        <span class="material-icons-round text-xs">bolt</span>
                        <span>Autocompletar Demo</span>
                    </button>
                </div>

                <!-- Botón de Acceso -->
                <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-amber-500 via-amber-400 to-amber-500 hover:from-amber-400 hover:to-amber-300 text-slate-950 font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-amber-500/20 active:scale-[0.98] transition flex items-center justify-center space-x-2">
                    <span>Acceder a la Consola</span>
                    <span class="material-icons-round text-base">arrow_forward</span>
                </button>
            </form>

            <!-- Credenciales sugeridas & Footer de Seguridad -->
            <div class="pt-4 border-t border-white/5 space-y-3">
                <div class="p-3 rounded-2xl bg-[#0A0F1E]/60 border border-[#243352]/50 text-[11px] text-slate-400 flex items-center justify-between">
                    <div>
                        <span class="text-slate-500 block text-[9px] uppercase tracking-wider">Acceso Rápido Administrador</span>
                        <span class="font-mono text-slate-200">admin@countryclub.com</span>
                    </div>
                    <span class="px-2 py-1 rounded-lg bg-amber-500/10 text-amber-400 font-mono text-[10px] font-bold border border-amber-500/20">password</span>
                </div>

                <div class="flex items-center justify-center space-x-2 text-[10px] text-slate-500">
                    <span class="material-icons-round text-xs text-emerald-400">verified_user</span>
                    <span>Conexión Encriptada • Registro de Auditoría Activo</span>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <p class="text-center text-[11px] text-slate-600">
            &copy; {{ date('Y') }} Country Club C.A. Todos los derechos reservados.
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

        function fillDemo() {
            document.getElementById('email').value = 'admin@countryclub.com';
            document.getElementById('password').value = 'password';
        }
    </script>
</body>
</html>
