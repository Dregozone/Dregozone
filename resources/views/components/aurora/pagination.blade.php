@php
    $pageName = $paginator->getPageName();
    $scrollToTop = "document.querySelector('#posts')?.scrollIntoView({ behavior: 'smooth' })";
    $pageButton = 'inline-flex h-9 min-w-9 items-center justify-center rounded-lg border px-3 font-mono text-xs transition-colors duration-300';
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-between gap-4">
        <p class="hidden font-mono text-xs text-dim sm:block">
            {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </p>

        <div class="flex items-center gap-1.5">
            @if ($paginator->onFirstPage())
                <span class="{{ $pageButton }} cursor-default border-edge text-dim/50" aria-disabled="true">← prev</span>
            @else
                <button type="button" wire:click="previousPage('{{ $pageName }}')" x-on:click="{{ $scrollToTop }}" class="{{ $pageButton }} border-edge text-mute hover:border-glow-teal/40 hover:text-ink">← prev</button>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="{{ $pageButton }} border-transparent text-dim">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="{{ $pageButton }} border-glow-teal/40 bg-glow-teal/10 text-glow-teal">{{ $page }}</span>
                        @else
                            <button type="button" wire:click="gotoPage({{ $page }}, '{{ $pageName }}')" x-on:click="{{ $scrollToTop }}" class="{{ $pageButton }} border-edge text-mute hover:border-glow-teal/40 hover:text-ink" aria-label="Go to page {{ $page }}">{{ $page }}</button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <button type="button" wire:click="nextPage('{{ $pageName }}')" x-on:click="{{ $scrollToTop }}" class="{{ $pageButton }} border-edge text-mute hover:border-glow-teal/40 hover:text-ink">next →</button>
            @else
                <span class="{{ $pageButton }} cursor-default border-edge text-dim/50" aria-disabled="true">next →</span>
            @endif
        </div>
    </nav>
@endif
