@if ($paginator->hasPages())
<nav class="cms-pagination" role="navigation" aria-label="Pagination">
    <div class="cms-pagination-summary">Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}</div>
    <div class="cms-pagination-links">
        @if ($paginator->onFirstPage())<span class="is-disabled" aria-disabled="true">Previous</span>@else<a href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>@endif
        @foreach ($elements as $element)
            @if (is_string($element))<span class="cms-pagination-ellipsis">{{ $element }}</span>@endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())<span class="is-current" aria-current="page">{{ $page }}</span>@else<a href="{{ $url }}" aria-label="Go to page {{ $page }}">{{ $page }}</a>@endif
                @endforeach
            @endif
        @endforeach
        @if ($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>@else<span class="is-disabled" aria-disabled="true">Next</span>@endif
    </div>
</nav>
@endif