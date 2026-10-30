@extends('layouts.app')

@section('title', 'Caja y Cobranzas')
@section('header_title', 'Recepción de Pagos y Liquidación')
@section('header_subtitle', 'Cálculo de cuotas con tasa oficial del Banco Central de Venezuela')

@section('content')
<div class="max-w-5xl mx-auto space-y-7">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-7">

        <!-- Formulario de Cobro (Columna Izquierda: 7 cols) -->
        <div class="lg:col-span-7 glass-panel rounded-3xl p-8 border border-[#1B253B] shadow-2xl space-y-6">
            <div class="border-b border-[#1B253B] pb-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 mb-2">
                    Módulo de Caja
                </span>
                <h3 class="text-xl font-extrabold text-white tracking-tight flex items-center">
                    <span class="material-icons-round mr-2 text-emerald-400">payments</span>
                    Registrar Cobro de Membresía
                </h3>
                <p class="text-xs text-slate-400">Ingresa la cédula del socio titular para calcular automáticamente su estado de cuenta.</p>
            </div>

            <form action="{{ route('pagos.store') }}" method="POST" id="pagoForm" class="space-y-5">
                @csrf

                <!-- Campo Cédula -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2 uppercase tracking-wider">
                        Cédula de Identidad del Socio <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-slate-400 font-bold text-xs">V-</span>
                        <input type="text" name="cedula" id="cedulaInput" value="{{ request('cedula') }}" required placeholder="Ej: 12345678" autofocus
                               class="w-full bg-[#0A0E1A] border border-[#23314D] rounded-2xl pl-10 pr-4 py-3 text-sm font-mono text-white placeholder-slate-500 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 transition-all">
                        <span id="loading-socio" class="material-icons-round absolute right-4 top-3 text-slate-500 text-lg hidden animate-spin">refresh</span>
                    </div>
                    @error('cedula')
                        <p class="text-rose-400 text-xs mt-1.5 flex items-center">
                            <span class="material-icons-round text-xs mr-1">error</span>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Monto a Pagar con Botones Rápidos -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">
                            Monto a Cobrar ($ USD) <span class="text-rose-400">*</span>
                        </label>
                        <span class="text-[11px] text-slate-400">Cuota base: $10.00 / mes</span>
                    </div>

                    <div class="relative">
                        <span class="absolute left-4 top-3 text-emerald-400 font-bold text-base font-mono">$</span>
                        <input type="number" step="0.01" min="0.01" name="monto" id="montoInput" value="10.00" required placeholder="10.00"
                               class="w-full bg-[#0A0E1A] border border-[#23314D] rounded-2xl pl-10 pr-4 py-3 text-base font-mono font-bold text-emerald-400 placeholder-slate-500 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 transition-all">
                    </div>

                    <!-- Botones de Acceso Rápido -->
                    <div class="flex flex-wrap gap-2 mt-2.5">
                        <button type="button" onclick="setMonto(10)" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs font-mono font-semibold text-slate-300 transition">$10 (1 mes)</button>
                        <button type="button" onclick="setMonto(20)" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs font-mono font-semibold text-slate-300 transition">$20 (2 meses)</button>
                        <button type="button" onclick="setMonto(30)" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs font-mono font-semibold text-slate-300 transition">$30 (3 meses)</button>
                        <button type="button" onclick="setMonto(50)" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs font-mono font-semibold text-slate-300 transition">$50</button>
                        <button type="button" onclick="setMonto(100)" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs font-mono font-semibold text-slate-300 transition">$100</button>
                    </div>

                    @error('monto')
                        <p class="text-rose-400 text-xs mt-1.5 flex items-center">
                            <span class="material-icons-round text-xs mr-1">error</span>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Concepto / Descripción -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2 uppercase tracking-wider">
                        Concepto / Detalle <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="descripcion" value="Cuota Mensual Mantenimiento" required
                           class="w-full bg-[#0A0E1A] border border-[#23314D] rounded-2xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 transition-all">
                    @error('descripcion')
                        <p class="text-rose-400 text-xs mt-1.5 flex items-center">
                            <span class="material-icons-round text-xs mr-1">error</span>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Botón de Confirmación -->
                <button type="submit" class="w-full py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white font-extrabold text-sm shadow-xl shadow-emerald-500/25 flex items-center justify-center transition-all duration-200 transform hover:-translate-y-0.5">
                    <span class="material-icons-round mr-2 text-lg">check_circle</span>
                    Procesar y Emitir Recibo Oficial
                </button>
            </form>
        </div>

        <!-- Panel de Liquidación y Conversión en Bolívares (Columna Derecha: 5 cols) -->
        <div class="lg:col-span-5 glass-panel rounded-3xl p-8 border border-[#1B253B] shadow-2xl space-y-6 flex flex-col justify-between">
            <div class="space-y-6">
                
                <!-- Encabezado Ficha -->
                <div class="flex items-center justify-between border-b border-[#1B253B] pb-4">
                    <div>
                        <h4 class="text-sm font-bold text-white uppercase tracking-wider">Liquidación Cambiaria</h4>
                        <p class="text-[11px] text-slate-400">Conversión a la tasa oficial del día</p>
                    </div>
                    <img src="{{ asset('img/Banco_Central_de_Venezuela_logo.svg') }}" alt="BCV" class="h-6 w-auto opacity-75">
                </div>

                <!-- Tarjeta de Dólares vs Bolívares -->
                <div class="rounded-2xl bg-[#090D17] border border-[#1B253B] p-5 space-y-4">
                    <!-- Monto USD -->
                    <div class="flex justify-between items-baseline">
                        <span class="text-xs text-slate-400 font-medium">Monto a cobrar en Divisas:</span>
                        <span class="text-lg font-extrabold font-mono text-emerald-400" id="card-usd">$10.00 USD</span>
                    </div>

                    <!-- Tasa Aplicada -->
                    <div class="flex justify-between items-center text-xs border-t border-[#162035] pt-3">
                        <span class="text-slate-400 flex items-center">
                            <span class="material-icons-round text-xs mr-1 text-amber-400">currency_exchange</span>
                            Tasa de Cambio BCV:
                        </span>
                        <strong class="text-amber-400 font-mono text-xs" id="card-tasa">
                            {{ number_format($tasaBcv, 2, ',', '.') }} Bs / USD
                        </strong>
                    </div>

                    <!-- Total en Bolívares Grandes -->
                    <div class="border-t border-[#162035] pt-4 bg-[#0F172A]/70 rounded-xl p-4 text-center">
                        <span class="text-[10px] uppercase tracking-widest font-extrabold text-slate-400 block mb-1">
                            Total a Recibir en Bolívares (Bs.)
                        </span>
                        <h2 class="text-2xl font-black text-amber-400 font-mono tracking-tight" id="totalBsDisplay">
                            Bs. {{ number_format(10 * $tasaBcv, 2, ',', '.') }}
                        </h2>
                    </div>
                </div>

                <!-- Información del Socio Detectado -->
                <div class="space-y-3">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">Datos del Socio Titular</span>
                    
                    <div class="rounded-2xl bg-[#0A0E1A] border border-[#1E293B] p-4 space-y-2.5 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Titular:</span>
                            <strong id="socioNombre" class="text-slate-200">Ingresa la cédula...</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Membresía / Acción:</span>
                            <span id="socioAccion" class="text-amber-400 font-bold font-mono">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Saldo Actual:</span>
                            <strong id="socioSaldo" class="text-slate-200 font-mono">$0.00 USD</strong>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Condición:</span>
                            <span id="socioCondicionBadge" class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-800 text-slate-400 border border-slate-700">
                                Sin consultar
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Meses en Mora:</span>
                            <strong id="socioMeses" class="text-slate-400">0 meses</strong>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Aviso Legal / Cumplimiento -->
            <div class="pt-4 border-t border-[#1B253B] text-[10px] text-slate-500 flex items-center justify-between">
                <span>Operaciones en Bolívares convertidas a tasa legal BCV</span>
                <span class="material-icons-round text-sm text-slate-600">verified</span>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    let tasaBCV = {{ $tasaBcv }};
    const cedulaInput = document.getElementById('cedulaInput');
    const montoInput = document.getElementById('montoInput');
    const totalBsDisplay = document.getElementById('totalBsDisplay');
    const cardUsd = document.getElementById('card-usd');
    const cardTasa = document.getElementById('card-tasa');

    // Función llamada cuando el botón global de sincronización actualiza la tasa
    window.actualizarTasaEnPagos = function(nuevaTasa) {
        tasaBCV = parseFloat(nuevaTasa);
        cardTasa.textContent = tasaBCV.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Bs / USD';
        calcularConversion();
    };

    function setMonto(val) {
        montoInput.value = Number(val).toFixed(2);
        calcularConversion();
    }

    function calcularConversion() {
        const monto = parseFloat(montoInput.value) || 0;
        const totalBs = monto * tasaBCV;
        cardUsd.textContent = '$' + monto.toFixed(2) + ' USD';
        totalBsDisplay.textContent = 'Bs. ' + totalBs.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    montoInput.addEventListener('input', calcularConversion);

    let debounceTimer = null;
    function consultarSocio() {
        clearTimeout(debounceTimer);
        const cedula = cedulaInput.value.trim();

        if (cedula.length >= 6) {
            document.getElementById('loading-socio').classList.remove('hidden');

            debounceTimer = setTimeout(() => {
                fetch(`{{ route('pagos.calcular') }}?cedula=${encodeURIComponent(cedula)}`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('loading-socio').classList.add('hidden');
                        if (data.error) {
                            document.getElementById('socioNombre').textContent = 'No registrado';
                            document.getElementById('socioAccion').textContent = '-';
                            document.getElementById('socioSaldo').textContent = '$0.00 USD';
                            document.getElementById('socioCondicionBadge').className = 'px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-800 text-slate-400 border border-slate-700';
                            document.getElementById('socioCondicionBadge').textContent = 'No encontrado';
                            document.getElementById('socioMeses').textContent = '0 meses';
                        } else {
                            document.getElementById('socioNombre').textContent = data.nombre;
                            document.getElementById('socioAccion').textContent = data.accion;
                            document.getElementById('socioSaldo').textContent = '$' + Number(data.saldo).toFixed(2) + ' USD';
                            
                            const badge = document.getElementById('socioCondicionBadge');
                            if (data.condicion === 'SOLVENTE') {
                                badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-sm';
                                badge.textContent = 'Solvente';
                            } else {
                                badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-500/10 text-rose-400 border border-rose-500/30 shadow-sm';
                                badge.textContent = 'Moroso (' + data.meses_deuda + ' meses)';
                            }

                            document.getElementById('socioMeses').textContent = data.meses_deuda + ' meses adeudados';

                            // Sugerir monto de deuda si es moroso
                            if (data.saldo < 0) {
                                montoInput.value = Math.abs(data.saldo).toFixed(2);
                                calcularConversion();
                            }
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        document.getElementById('loading-socio').classList.add('hidden');
                    });
            }, 300);
        }
    }

    cedulaInput.addEventListener('input', consultarSocio);

    // Inicializar cálculo
    calcularConversion();
    if (cedulaInput.value.trim()) {
        consultarSocio();
    }
</script>
@endpush
