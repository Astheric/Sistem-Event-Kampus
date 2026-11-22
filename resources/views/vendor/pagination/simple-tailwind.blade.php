@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex items-center justify-between gap-3 py-3">
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-line bg-surface-soft text-slate-300 text-xs font-semibold cursor-not-allowed select-none">
                <x-icon name="chevron-left" class="size-4" /> Sebelumnya
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-line bg-surface text-slate-600 text-xs font-semibold hover:bg-surface-soft hover:text-ink transition-colors shadow-xs">
                <x-icon name="chevron-left" class="size-4" /> Sebelumnya
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-line bg-surface text-slate-600 text-xs font-semibold hover:bg-surface-soft hover:text-ink transition-colors shadow-xs">
                Selanjutnya <x-icon name="chevron-right" class="size-4" />
            </a>
        @else
            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-line bg-surface-soft text-slate-300 text-xs font-semibold cursor-not-allowed select-none">
                Selanjutnya <x-icon name="chevron-right" class="size-4" />
            </span>
        @endif
    </nav>
@endif
