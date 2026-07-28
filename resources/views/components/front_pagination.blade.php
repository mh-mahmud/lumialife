@if ($paginator->total() > 10)
<ul class="pagination mt-5">
    <!-- Previous Page Link -->
    @if ($paginator->onFirstPage())
        <li class="page-item previous disabled"><span class="page-link" aria-disabled="true" aria-label="Previous page"><svg class="pagination-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 12H5M11 18l-6-6 6-6"/></svg></span></li>
    @else
        <li class="page-item previous"><a href="{{ $paginator->previousPageUrl() }}" class="page-link" aria-label="Previous page"><svg class="pagination-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 12H5M11 18l-6-6 6-6"/></svg></a></li>
    @endif

    <!-- Pagination Elements -->
    @for ($page = 1; $page <= $paginator->lastPage(); $page++)
        @if ($page == $paginator->currentPage())
            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
        @else
            <li class="page-item"><a href="{{ $paginator->url($page) }}" class="page-link">{{ $page }}</a></li>
        @endif
    @endfor

    <!-- Next Page Link -->
    @if ($paginator->hasMorePages())
        <li class="page-item next"><a href="{{ $paginator->nextPageUrl() }}" class="page-link" aria-label="Next page"><svg class="pagination-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a></li>
    @else
        <li class="page-item next disabled"><span class="page-link" aria-disabled="true" aria-label="Next page"><svg class="pagination-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span></li>
    @endif
</ul>
@endif
