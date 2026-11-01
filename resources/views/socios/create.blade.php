@extends('layouts.app')

@section('title', 'Afiliar Nuevo Socio')
@section('header_title', 'Registro y Afiliación de Socio')
@section('header_subtitle', 'Emisión de nueva acción y alta en el padrón oficial del Club')

@section('content')
<div class="max-w-6xl mx-auto space-y-7">

    <!-- Navegación y Cabecera de Acción -->
    <div class="flex items-center justify-between">
        <a href="{{ route('socios.index') }}" class="inline-flex items-center space-x-2 text-xs font-semibold text-slate-400 hover:text-white transition">
            <span class="material-icons-round text-base">arrow_back</span>
            <span>Volver al Directorio de Socios</span>
        </a>

        <div class="inline-flex items-center space-x-2 text-xs px-3 py-1.5 rounded-full bg-[#111A2E] border border-amber-500/20 text-amber-300">
            <span class="material-icons-round text-sm">stars</span>
            <span>Nueva Membresía Titular</span>
        </div>
    </div>

    @if(isset($errors) && $errors->any())
    <div class="p-5 rounded-3xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-start space-x-3.5 shadow-xl shadow-rose-500/5">
        <div class="w-8 h-8 rounded-xl bg-rose-500/20 border border-rose-500/30 flex items-center justify-center flex-shrink-0 mt-0.5">
            <span class="material-icons-round text-rose-400 text-base">warning</span>
        </div>
        <div class="space-y-2 flex-1">
            <div>
                <h4 class="font-bold text-white text-sm">No se pudo procesar la afiliación</h4>
                <p class="text-[11px] text-slate-300 mt-0.5">Por favor revise y corrija los siguientes datos para poder continuar:</p>
            </div>
            <ul class="space-y-1.5 pt-1">
                @foreach($errors->all() as $error)
                    <li class="flex items-start space-x-2 bg-[#0C1222]/80 px-3.5 py-2 rounded-xl border border-rose-500/20 text-rose-200">
                        <span class="material-icons-round text-rose-400 text-sm mt-0.5">cancel</span>
                        <span class="font-medium text-xs">{{ $error }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-7">

        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            <form action="{{ route('socios.store') }}" method="POST" class="space-y-6" id="socioForm">
                @csrf

                <!-- Sección 1: Datos Personales -->
                <div class="glass-panel rounded-3xl p-7 border border-[#1F293D] shadow-2xl space-y-5">
                    <div class="flex items-center space-x-3 border-b border-[#1F293D] pb-4">
                        <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                            <span class="material-icons-round text-lg">badge</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white">1. Datos Personales del Titular</h3>
                            <p class="text-[11px] text-slate-400">Identificación oficial y nombres para el registro</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nombre(s) <span class="text-amber-400">*</span></label>
                            <input type="text" name="nombre" id="inputNombre" value="{{ old('nombre') }}" required placeholder="Ej: Carlos Eduardo"
                                   class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition">
                            @error('nombre') <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Apellido(s) <span class="text-amber-400">*</span></label>
                            <input type="text" name="apellido" id="inputApellido" value="{{ old('apellido') }}" required placeholder="Ej: Mendoza Salazar"
                                   class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition">
                            @error('apellido') <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Cédula de Identidad <span class="text-amber-400">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-2.5 text-slate-400 text-xs font-bold font-mono">V-</span>
                                <input type="text" name="cedula" id="inputCedula" value="{{ old('cedula') }}" required placeholder="12345678"
                                       class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl pl-9 pr-4 py-2.5 text-xs text-white font-mono placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition">
                            </div>
                            @error('cedula') <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                                   class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition">
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Membresía & Acción -->
                <div class="glass-panel rounded-3xl p-7 border border-[#1F293D] shadow-2xl space-y-5">
                    <div class="flex items-center space-x-3 border-b border-[#1F293D] pb-4">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                            <span class="material-icons-round text-lg">card_membership</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white">2. Datos de Membresía & Acción</h3>
                            <p class="text-[11px] text-slate-400">Asignación del código de acción y estado operativo</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Número de Acción <span class="text-amber-400">*</span></label>
                            <div class="relative">
                                <span class="material-icons-round absolute left-3.5 top-2.5 text-slate-400 text-sm">confirmation_number</span>
                                <input type="text" name="accion" id="inputAccion" value="{{ old('accion') }}" required placeholder="Ej: 9972 o 105"
                                       class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl pl-10 pr-4 py-2.5 text-xs text-white font-mono uppercase tracking-wider placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Ingrese solo el número. La condición de acción se sobreentiende en el club.</p>
                            @error('accion') <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Estado Inicial de Afiliación <span class="text-amber-400">*</span></label>
                            <select name="estado" class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition">
                                <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Socio Activo (Habilitado)</option>
                                <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Socio Inactivo (Suspendido)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Contacto & Residencia -->
                <div class="glass-panel rounded-3xl p-7 border border-[#1F293D] shadow-2xl space-y-5">
                    <div class="flex items-center space-x-3 border-b border-[#1F293D] pb-4">
                        <div class="w-9 h-9 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center">
                            <span class="material-icons-round text-lg">contact_mail</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white">3. Contacto y Residencia</h3>
                            <p class="text-[11px] text-slate-400">Canales de notificación y dirección de facturación</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Correo Electrónico <span class="text-amber-400">*</span></label>
                            <div class="relative">
                                <span class="material-icons-round absolute left-3.5 top-2.5 text-slate-400 text-sm">alternate_email</span>
                                <input type="email" name="correo" id="inputCorreo" value="{{ old('correo') }}" required placeholder="socio@email.com"
                                       class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl pl-10 pr-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition">
                            </div>
                            @error('correo') <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Teléfono Celular</label>
                            <div class="relative">
                                <span class="material-icons-round absolute left-3.5 top-2.5 text-slate-400 text-sm">smartphone</span>
                                <input type="text" name="numero" value="{{ old('numero') }}" placeholder="0414-1234567"
                                       class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl pl-10 pr-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition">
                            </div>
                            @error('numero') <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Dirección de Domicilio</label>
                        <textarea name="direccion" rows="2" placeholder="Urbanización, Calle, Edificio / Quinta, Ciudad..."
                                  class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition">{{ old('direccion') }}</textarea>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex items-center justify-end space-x-4 pt-2">
                    <a href="{{ route('socios.index') }}" class="px-6 py-3 rounded-2xl bg-[#141C30] hover:bg-[#1C2742] text-slate-300 hover:text-white font-semibold text-xs transition border border-[#243352]">
                        Cancelar
                    </a>

                    <button type="submit" class="px-7 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 flex items-center space-x-2 transition active:scale-[0.98]">
                        <span class="material-icons-round text-base">person_add_alt_1</span>
                        <span>Guardar y Emitir Membresía</span>
                    </button>
                </div>

            </form>
        </div>

        <!-- Columna Lateral: Previsualización de Carnet Digital de Socio -->
        <div class="space-y-6">
            <div class="glass-panel rounded-3xl p-6 border border-[#1F293D] shadow-2xl space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Previsualización</span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-amber-500/10 text-amber-400 border border-amber-500/20">
                        Carnet Oficial
                    </span>
                </div>

                <!-- Tarjeta / Carnet de Socio Digital -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#1E293B] via-[#0F172A] to-[#0B0F19] p-6 border border-amber-500/30 shadow-xl space-y-5">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-6 w-auto object-contain">
                            <span class="text-xs font-extrabold tracking-widest uppercase font-serif text-amber-200">COUNTRY CLUB</span>
                        </div>
                        <span id="previewAccion" class="px-2.5 py-0.5 rounded text-[10px] font-mono font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                            Acción #---
                        </span>
                    </div>

                    <div class="flex items-center space-x-4 pt-2">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500/30 to-amber-200/10 border border-amber-500/40 flex items-center justify-center shadow-inner">
                            <span class="material-icons-round text-amber-300 text-2xl">person</span>
                        </div>
                        <div>
                            <h4 id="previewNombre" class="text-sm font-bold text-white leading-tight">Nombre del Titular</h4>
                            <p id="previewCedula" class="text-[11px] font-mono text-slate-400 mt-0.5">V-00.000.000</p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                Titular Activo
                            </span>
                        </div>
                    </div>

                    <!-- Código QR en el Carnet (Tema Oscuro Integrado sin Fondo Blanco) -->
                    <div class="p-4 rounded-2xl bg-[#060911] border border-amber-500/30 flex flex-col items-center justify-center space-y-2 shadow-inner">
                        <div id="previewQrContainer" class="flex items-center justify-center p-1.5 rounded-xl bg-[#060911]"></div>
                        <span class="text-[9px] font-mono text-amber-300/90 font-bold uppercase tracking-wider">
                            Código QR Oficial para Garita
                        </span>
                    </div>

                    <div class="pt-3 border-t border-white/10 flex items-center justify-between text-[10px] text-slate-400">
                        <span>Emisión: {{ date('m/Y') }}</span>
                        <span class="font-mono text-amber-400 font-bold">CONTROL DE VISITAS</span>
                    </div>
                </div>

                <!-- Notificación automática de bienvenida -->
                <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 space-y-1.5 text-xs text-amber-300">
                    <span class="font-bold flex items-center text-amber-400">
                        <span class="material-icons-round text-sm mr-1">mark_email_read</span>
                        Notificación Automática de Bienvenida
                    </span>
                    <p class="text-[11px] text-slate-300">
                        Al afiliar al socio, el sistema le enviará un correo formal con su <strong>Número de Acción asignado</strong>, constancia de membresía e instrucciones para la garita de vigilancia.
                    </p>
                </div>

                <!-- Beneficios automáticos -->
                <div class="p-4 rounded-2xl bg-[#0A0F1E] border border-[#243352]/50 space-y-2 text-xs">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Beneficios del Socio</span>
                    <ul class="text-[11px] text-slate-300 space-y-1">
                        <li class="flex items-center space-x-1.5">
                            <span class="material-icons-round text-emerald-400 text-sm">check</span>
                            <span>Acceso ilimitado a instalaciones y campos</span>
                        </li>
                        <li class="flex items-center space-x-1.5">
                            <span class="material-icons-round text-emerald-400 text-sm">check</span>
                            <span>Facturación y caja en Bolívares o Dólares</span>
                        </li>
                        <li class="flex items-center space-x-1.5">
                            <span class="material-icons-round text-emerald-400 text-sm">check</span>
                            <span>Registro de invitados y familiares</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

    </div>

</div>

@push('scripts')
<script src="{{ asset('js/qrcode.min.js') }}"></script>
<script>
    const inputNombre = document.getElementById('inputNombre');
    const inputApellido = document.getElementById('inputApellido');
    const inputCedula = document.getElementById('inputCedula');
    const inputAccion = document.getElementById('inputAccion');

    const previewNombre = document.getElementById('previewNombre');
    const previewCedula = document.getElementById('previewCedula');
    const previewAccion = document.getElementById('previewAccion');
    const qrContainer = document.getElementById('previewQrContainer');
    let qrInstance = null;

    function updatePreview() {
        const nom = inputNombre.value.trim();
        const ape = inputApellido.value.trim();
        const ced = inputCedula.value.trim();
        const accRaw = inputAccion.value.trim();
        const acc = accRaw.replace(/^acc[-_\s]*/i, '');

        previewNombre.textContent = (nom || ape) ? `${nom} ${ape}`.trim() : 'Nombre del Titular';
        previewCedula.textContent = ced ? `V-${ced}` : 'V-00.000.000';
        previewAccion.textContent = acc ? `Acción #${acc}` : 'Acción #---';

        // Actualizar QR en vivo con tema oscuro (fondo oscuro #060911 y módulos dorados #FBBF24 sin fondo blanco)
        qrContainer.innerHTML = '';
        qrInstance = new QRCode(qrContainer, {
            text: JSON.stringify({
                club: "COUNTRY_CLUB",
                cedula: ced || "00000000",
                accion: acc || "---"
            }),
            width: 95,
            height: 95,
            colorDark: "#FBBF24",
            colorLight: "#060911",
            correctLevel: QRCode.CorrectLevel.M
        });
    }

    inputNombre.addEventListener('input', updatePreview);
    inputApellido.addEventListener('input', updatePreview);
    inputCedula.addEventListener('input', updatePreview);
    inputAccion.addEventListener('input', updatePreview);

    window.addEventListener('DOMContentLoaded', updatePreview);
</script>
@endpush
@endsection
