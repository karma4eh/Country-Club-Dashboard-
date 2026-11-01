@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navegación de Páginas" class="flex items-center justify-between py-3 text-xs">
        @if ($paginator->onFirstPage())
            <span class="px-3.5 py-1.5 rounded-xl bg-[#0A0F1E] border border-[#1E293B] text-slate-600 cursor-not-allowed flex items-center space-x-1">
                <span class="material-icons-round text-sm">chevron_left</span>
                <span>Anterior</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3.5 py-1.5 rounded-xl bg-[#141C30] hover:bg-[#1E293B] border border-[#243352] text-slate-300 hover:text-white font-semibold transition flex items-center space-x-1">
                <span class="material-icons-round text-sm">chevron_left</span>
                <span>Anterior</span>
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3.5 py-1.5 rounded-xl bg-[#141C30] hover:bg-[#1E293B] border border-[#243352] text-slate-300 hover:text-white font-semibold transition flex items-center space-x-1">
                <span>Siguiente</span>
                <span class="material-icons-round text-sm">chevron_right</span>
            </a>
        @else
            <span class="px-3.5 py-1.5 rounded-xl bg-[#0A0F1E] border border-[#1E293B] text-slate-600 cursor-not-allowed flex items-center space-x-1">
                <span>Siguiente</span>
                <span class="material-icons-round text-sm">chevron_right</span>
            </span>
        @endif
    </nav>
@endif
