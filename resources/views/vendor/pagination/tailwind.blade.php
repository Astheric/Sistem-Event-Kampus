@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 py-3">
        {{-- Total & Info --}}
        <div class="text-xs sm:text-sm text-muted">
            Menampilkan
            <span class="font-semibold text-ink">{{ $paginator->firstItem() ?? 0 }}</span>
            sampai
            <span class="font-semibold text-ink">{{ $paginator->lastItem() ?? 0 }}</span>
            dari
            <span class="font-semibold text-ink">{{ $paginator->total() }}</span>
            data
        </div>

        {{-- Page Buttons --}}
        <div class="inline-flex items-center gap-1.5 flex-wrap">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center size-9 rounded-lg border border-line bg-surface-soft text-slate-300 cursor-not-allowed select-none">
                    <x-icon name="chevron-left" class="size-4" />
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="inline-flex items-center justify-center size-9 rounded-lg border border-line bg-surface text-slate-600 hover:bg-surface-soft hover:text-ink transition-colors shadow-xs">
                    <x-icon name="chevron-left" class="size-4" />
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="inline-flex items-center justify-center size-9 text-xs font-semibold text-muted select-none">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                  class="inline-flex items-center justify-center size-9 rounded-lg bg-brand-700 text-white text-xs font-bold shadow-xs select-none">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="inline-flex items-center justify-center size-9 rounded-lg border border-line bg-surface text-slate-600 text-xs font-semibold hover:bg-surface-soft hover:text-ink transition-colors shadow-xs">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="inline-flex items-center justify-center size-9 rounded-lg border border-line bg-surface text-slate-600 hover:bg-surface-soft hover:text-ink transition-colors shadow-xs">
                    <x-icon name="chevron-right" class="size-4" />
                </a>
            @else
                <span class="inline-flex items-center justify-center size-9 rounded-lg border border-line bg-surface-soft text-slate-300 cursor-not-allowed select-none">
                    <x-icon name="chevron-right" class="size-4" />
                </span>
            @endif
        </div>
    </nav>
@endif
