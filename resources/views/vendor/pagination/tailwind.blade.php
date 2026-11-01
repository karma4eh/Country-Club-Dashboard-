@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navegación de Páginas" class="flex flex-wrap items-center justify-between gap-4 py-3 text-xs">
        
        <!-- Vista Móvil: Botones Simples -->
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 rounded-xl bg-[#0A0F1E] border border-[#243352]/40 text-slate-600 font-semibold cursor-not-allowed">
                    &larr; Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 rounded-xl bg-[#141C30] hover:bg-[#1E293B] border border-[#243352] text-slate-200 font-semibold transition active:scale-95">
                    &larr; Anterior
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 rounded-xl bg-[#141C30] hover:bg-[#1E293B] border border-[#243352] text-slate-200 font-semibold transition active:scale-95">
                    Siguiente &rarr;
                </a>
            @else
                <span class="px-4 py-2 rounded-xl bg-[#0A0F1E] border border-[#243352]/40 text-slate-600 font-semibold cursor-not-allowed">
                    Siguiente &rarr;
                </span>
            @endif
        </div>

        <!-- Vista Desktop: Completa con Numeración y Resumen -->
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs text-slate-400 font-medium">
                    Mostrando del <span class="font-bold text-amber-300 font-mono">{{ $paginator->firstItem() ?? 0 }}</span> al <span class="font-bold text-amber-300 font-mono">{{ $paginator->lastItem() ?? 0 }}</span> de <span class="font-bold text-white font-mono">{{ $paginator->total() }}</span> registros
                </p>
            </div>

            <div class="flex items-center space-x-1">
                {{-- Botón Anterior --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" class="px-3 py-1.5 rounded-xl bg-[#0A0F1E] border border-[#1E293B] text-slate-600 cursor-not-allowed flex items-center space-x-1">
                        <span class="material-icons-round text-sm">chevron_left</span>
                        <span>Anterior</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-1.5 rounded-xl bg-[#141C30] hover:bg-[#1E293B] border border-[#243352] text-slate-300 hover:text-white font-semibold transition flex items-center space-x-1 active:scale-95">
                        <span class="material-icons-round text-sm">chevron_left</span>
                        <span>Anterior</span>
                    </a>
                @endif

                {{-- Números de Página --}}
                @foreach ($elements as $element)
                    {{-- Separador de Puntos --}}
                    @if (is_string($element))
                        <span class="px-3 py-1.5 text-slate-600 font-bold">&hellip;</span>
                    @endif

                    {{-- Array de Enlaces --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-black font-mono shadow-md shadow-amber-500/20 border border-amber-400/40">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="px-3.5 py-1.5 rounded-xl bg-[#141C30] hover:bg-[#1E293B] border border-[#243352] text-slate-300 hover:text-amber-300 font-bold font-mono transition active:scale-95">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Botón Siguiente --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-1.5 rounded-xl bg-[#141C30] hover:bg-[#1E293B] border border-[#243352] text-slate-300 hover:text-white font-semibold transition flex items-center space-x-1 active:scale-95">
                        <span>Siguiente</span>
                        <span class="material-icons-round text-sm">chevron_right</span>
                    </a>
                @else
                    <span aria-disabled="true" class="px-3 py-1.5 rounded-xl bg-[#0A0F1E] border border-[#1E293B] text-slate-600 cursor-not-allowed flex items-center space-x-1">
                        <span>Siguiente</span>
                        <span class="material-icons-round text-sm">chevron_right</span>
                    </span>
                @endif
            </div>
        </div>

    </nav>
@endif
