@if ($paginator->hasPages())
<nav class="flex items-center justify-center gap-2" role="navigation" aria-label="Pagination">
    @if ($paginator->onFirstPage())
        <span class="rounded-md border border-gold-700/30 px-4 py-2 text-ink-400">{{ __('site.pagination.previous') }}</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="rounded-md border border-gold-700/60 px-4 py-2 text-gold-500 hover:border-gold-500">{{ __('site.pagination.previous') }}</a>
    @endif
    @foreach ($elements as $element)
        @if (is_string($element))<span class="px-2 text-ink-400">{{ $element }}</span>@endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span aria-current="page" class="rounded-md bg-gold-500 px-4 py-2 font-bold text-navy-950">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="rounded-md border border-gold-700/40 px-4 py-2 hover:border-gold-500">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="rounded-md border border-gold-700/60 px-4 py-2 text-gold-500 hover:border-gold-500">{{ __('site.pagination.next') }}</a>
    @else
        <span class="rounded-md border border-gold-700/30 px-4 py-2 text-ink-400">{{ __('site.pagination.next') }}</span>
    @endif
</nav>
@endif
