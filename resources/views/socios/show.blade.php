@extends('layouts.app')

@section('title', 'Expediente | ' . $socio->nombre_completo)
@section('header_title', 'Expediente Oficial del Socio')
@section('header_subtitle', 'Historial contable, datos de afiliación y estado de solvencia')

@section('content')
<div class="max-w-6xl mx-auto space-y-7">

    <!-- Navegación Superior -->
    <div class="flex items-center justify-between">
        <a href="{{ route('socios.index') }}" class="inline-flex items-center space-x-2 text-xs font-semibold text-slate-400 hover:text-white transition">
            <span class="material-icons-round text-base">arrow_back</span>
            <span>Volver al Directorio de Socios</span>
        </a>

        <div class="flex items-center space-x-3">
            <button onclick="document.getElementById('modalEditar').classList.remove('hidden')" class="px-4 py-2 rounded-2xl bg-[#141C30] hover:bg-[#1C2742] text-slate-200 text-xs font-semibold border border-[#243352] flex items-center space-x-1.5 transition">
                <span class="material-icons-round text-sm text-amber-400">edit</span>
                <span>Editar Expediente</span>
            </button>

            <a href="{{ route('pagos.create', ['cedula' => $socio->cedula]) }}" class="px-5 py-2 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white text-xs font-extrabold uppercase tracking-wider shadow-lg shadow-emerald-500/20 flex items-center space-x-1.5 transition active:scale-[0.98]">
                <span class="material-icons-round text-base">point_of_sale</span>
                <span>Registrar Pago / Abono</span>
            </a>
        </div>
    </div>

    <!-- Banner Ejecutivo del Perfil -->
    <div class="glass-panel rounded-3xl p-7 border border-[#1F293D] shadow-2xl relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-56 h-56 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center space-x-5">
                <div class="relative">
                    <img src="{{ $socio->avatar_url }}" alt="avatar" class="w-20 h-20 rounded-2xl border-2 border-amber-400/40 shadow-xl object-cover bg-slate-800">
                    <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full flex items-center justify-center text-[10px] {{ $socio->estado === 'activo' ? 'bg-emerald-500 text-black' : 'bg-rose-500 text-white' }} font-bold shadow">
                        <span class="material-icons-round text-xs">{{ $socio->estado === 'activo' ? 'check' : 'close' }}</span>
                    </span>
                </div>

                <div class="space-y-1">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h2 class="text-2xl font-extrabold text-white tracking-tight">{{ $socio->nombre_completo }}</h2>
                        <span class="px-3 py-0.5 rounded-full text-xs font-mono font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30">
                            {{ $socio->accion }}
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 text-xs text-slate-400">
                        <span class="flex items-center space-x-1">
                            <span class="material-icons-round text-sm text-slate-500">badge</span>
                            <span class="font-mono">Cédula: V-{{ $socio->cedula }}</span>
                        </span>
                        <span class="flex items-center space-x-1">
                            <span class="material-icons-round text-sm text-slate-500">event</span>
                            <span>Afiliado: {{ $socio->socio_desde ? $socio->socio_desde->format('d/m/Y') : 'Fecha no reg.' }}</span>
                        </span>
                        <span class="flex items-center space-x-1">
                            <span class="material-icons-round text-sm text-slate-500">verified</span>
                            <span class="uppercase font-semibold {{ $socio->estado === 'activo' ? 'text-emerald-400' : 'text-rose-400' }}">
                                Socio {{ $socio->estado }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Badge de Condición Financiera -->
            <div class="flex items-center md:flex-col md:items-end justify-between border-t md:border-t-0 border-[#1F293D] pt-4 md:pt-0">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Condición Actual</span>
                @if($socio->saldo < 0)
                    <span class="inline-flex items-center space-x-1.5 px-4 py-1.5 rounded-2xl text-xs font-extrabold uppercase bg-rose-500/10 text-rose-400 border border-rose-500/30 shadow-lg shadow-rose-500/10">
                        <span class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span>
                        <span>CUENTA CON MORA</span>
                    </span>
                @else
                    <span class="inline-flex items-center space-x-1.5 px-4 py-1.5 rounded-2xl text-xs font-extrabold uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-lg shadow-emerald-500/10">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>SOLVENTE AL DÍA</span>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Ficha de Datos Personales & Tarjeta Financiera -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-7">

        <!-- Columna 1 y 2: Información Personal & de Contacto -->
        <div class="lg:col-span-2 glass-panel rounded-3xl p-7 border border-[#1F293D] shadow-2xl space-y-6">
            <div class="flex items-center space-x-3 border-b border-[#1F293D] pb-4">
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                    <span class="material-icons-round text-lg">person</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white">Datos de Contacto y Residencia</h3>
                    <p class="text-[11px] text-slate-400">Ficha censal del socio titular y canales de correspondencia</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
                <div class="p-4 rounded-2xl bg-[#0A0F1E] border border-[#243352]/50 space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Correo Electrónico</span>
                    <p class="font-semibold text-slate-200 truncate">{{ $socio->correo }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-[#0A0F1E] border border-[#243352]/50 space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Teléfono de Contacto</span>
                    <p class="font-semibold text-slate-200">{{ $socio->numero ?? 'No registrado' }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-[#0A0F1E] border border-[#243352]/50 space-y-1 sm:col-span-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Dirección de Domicilio</span>
                    <p class="font-semibold text-slate-200 leading-relaxed">{{ $socio->direccion ?? 'Sin dirección registrada en el sistema.' }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-[#0A0F1E] border border-[#243352]/50 space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Fecha de Nacimiento</span>
                    <p class="font-semibold text-slate-200">{{ $socio->fecha_nacimiento ? $socio->fecha_nacimiento->format('d/m/Y') : 'No registrada' }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-[#0A0F1E] border border-[#243352]/50 space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Código de Acción</span>
                    <p class="font-mono font-bold text-amber-300">{{ $socio->accion }}</p>
                </div>
            </div>
        </div>

        <!-- Columna 3: Billetera y Estado Contable -->
        @php
            $tasaBcv = \App\Models\TasaDolar::tasaActual();
            $saldoAbsoluto = abs($socio->saldo);
            $saldoEnBs = $saldoAbsoluto * $tasaBcv;
        @endphp
        <div class="glass-panel rounded-3xl p-7 border border-[#1F293D] shadow-2xl flex flex-col justify-between space-y-6">
            <div class="space-y-5">
                <div class="flex items-center space-x-3 border-b border-[#1F293D] pb-4">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                        <span class="material-icons-round text-lg">account_balance_wallet</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white">Estado Financiero</h3>
                        <p class="text-[11px] text-slate-400">Balance contable al día</p>
                    </div>
                </div>

                <!-- Monto Destacado -->
                <div class="p-5 rounded-2xl bg-[#0A0F1E] border border-[#243352] text-center space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Saldo Contable</span>
                    
                    <div class="text-3xl font-extrabold font-mono {{ $socio->saldo < 0 ? 'text-rose-400' : 'text-emerald-400' }}">
                        ${{ number_format($socio->saldo, 2) }} <span class="text-sm">USD</span>
                    </div>

                    <!-- Conversión en Bolívares usando tasa real -->
                    <div class="pt-2 border-t border-[#1F293D] text-xs font-mono text-slate-300">
                        <span class="text-slate-500 text-[10px] uppercase block">Equivalente BCV Oficial:</span>
                        <span class="font-bold text-amber-300">
                            {{ $socio->saldo < 0 ? '-' : '' }}Bs. {{ number_format($saldoEnBs, 2, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Indicadores de Deuda -->
                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-[#0A0F1E]/50 border border-[#243352]/40">
                        <span class="text-slate-400">Meses en mora:</span>
                        <strong class="{{ $socio->meses_deuda > 0 ? 'text-rose-400' : 'text-emerald-400' }} font-bold font-mono">
                            {{ $socio->meses_deuda }} {{ $socio->meses_deuda == 1 ? 'mes' : 'meses' }}
                        </strong>
                    </div>

                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-[#0A0F1E]/50 border border-[#243352]/40">
                        <span class="text-slate-400">Último pago registrado:</span>
                        <span class="font-semibold text-slate-200">
                            {{ $socio->ultimo_mes_pagado ? $socio->ultimo_mes_pagado->format('d/m/Y') : 'Sin registros' }}
                        </span>
                    </div>
                </div>
            </div>

            <a href="{{ route('pagos.create', ['cedula' => $socio->cedula]) }}" class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white font-extrabold text-xs uppercase tracking-wider text-center shadow-lg shadow-emerald-500/20 transition active:scale-[0.98]">
                Cobrar / Abonar en Caja
            </a>
        </div>

    </div>

    <!-- Historial de Pagos y Transacciones del Socio -->
    <div class="glass-panel rounded-3xl p-7 border border-[#1F293D] shadow-2xl space-y-5">
        <div class="flex items-center justify-between border-b border-[#1F293D] pb-4">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                    <span class="material-icons-round text-lg">receipt_long</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white">Historial de Pagos y Comprobantes</h3>
                    <p class="text-[11px] text-slate-400">Registro cronológico de aportes, cuotas y abonos realizados</p>
                </div>
            </div>

            <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-[#141C30] text-slate-300 border border-[#243352]">
                Total: {{ $socio->pagos->count() }} pagos
            </span>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-[#1B253B]">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0B101D] text-slate-400 font-bold uppercase tracking-wider border-b border-[#1B253B]">
                    <tr>
                        <th class="px-6 py-4">Fecha & Hora</th>
                        <th class="px-6 py-4">Concepto / Motivo</th>
                        <th class="px-6 py-4">Monto USD</th>
                        <th class="px-6 py-4">Equivalente BCV</th>
                        <th class="px-6 py-4 text-right">Comprobante</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1B253B] bg-[#0E1526]/50">
                    @forelse($socio->pagos as $pago)
                    <tr class="hover:bg-[#162238]/60 transition-colors">
                        <td class="px-6 py-4 text-slate-300 whitespace-nowrap font-mono text-[11px]">
                            {{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y h:i A') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-white">
                            {{ $pago->descripcion }}
                        </td>
                        <td class="px-6 py-4 font-extrabold text-emerald-400 font-mono text-sm whitespace-nowrap">
                            ${{ number_format($pago->monto, 2) }}
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-amber-300 whitespace-nowrap">
                            Bs. {{ number_format($pago->monto * $tasaBcv, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('pagos.receipt', $pago) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl bg-[#141C30] hover:bg-[#1E293B] text-slate-200 text-xs font-semibold border border-[#243352] transition">
                                <span class="material-icons-round text-xs text-amber-400">print</span>
                                <span>Imprimir</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                            <span class="material-icons-round text-3xl text-slate-600 block mb-1">receipt</span>
                            <span>No existen comprobantes ni transacciones registradas para este socio.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal para Editar Datos del Socio -->
<div id="modalEditar" class="fixed inset-0 bg-black/80 backdrop-blur-md flex items-center justify-center p-4 z-50 hidden">
    <div class="glass-panel bg-[#0E1526] border border-[#23314D] rounded-3xl p-7 w-full max-w-lg shadow-2xl space-y-6 relative">
        
        <div class="flex items-center justify-between pb-4 border-b border-[#1F293D]">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                    <span class="material-icons-round text-base">edit</span>
                </div>
                <h3 class="text-sm font-bold text-white">Editar Expediente del Socio</h3>
            </div>
            <button onclick="document.getElementById('modalEditar').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-[#141C30] text-slate-400 hover:text-white flex items-center justify-center transition">
                <span class="material-icons-round text-base">close</span>
            </button>
        </div>

        <form action="{{ route('socios.update', $socio) }}" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Nombre</label>
                    <input type="text" name="nombre" value="{{ $socio->nombre }}" required
                           class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-amber-400">
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Apellido</label>
                    <input type="text" name="apellido" value="{{ $socio->apellido }}" required
                           class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-amber-400">
                </div>
            </div>

            <div>
                <label class="block text-slate-300 font-semibold mb-1">Correo Electrónico</label>
                <input type="email" name="correo" value="{{ $socio->correo }}" required
                       class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-amber-400">
            </div>

            <div class="grid grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Teléfono</label>
                    <input type="text" name="numero" value="{{ $socio->numero }}"
                           class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-amber-400">
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Estado de Afiliación</label>
                    <select name="estado" class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-amber-400">
                        <option value="activo" {{ $socio->estado === 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ $socio->estado === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-slate-300 font-semibold mb-1">Dirección de Domicilio</label>
                <textarea name="direccion" rows="2" class="w-full bg-[#0A0F1E] border border-[#243352] rounded-2xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-amber-400">{{ $socio->direccion }}</textarea>
            </div>

            <div class="pt-4 border-t border-[#1F293D] flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('modalEditar').classList.add('hidden')" class="px-5 py-2.5 rounded-2xl bg-[#141C30] text-slate-300 hover:text-white font-semibold transition border border-[#243352]">
                    Cancelar
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold transition shadow-lg shadow-amber-500/10">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
