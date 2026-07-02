@php
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $pages = collect([1, 2, 3, $currentPage - 1, $currentPage, $currentPage + 1, $lastPage - 1, $lastPage])
        ->filter(fn ($page) => $page >= 1 && $page <= $lastPage)
        ->unique()
        ->sort()
        ->values();
    $previousPage = 0;
@endphp

@if ($paginator->hasPages())
    <div class="pagination-links" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="page-link muted">First</span>
            <span class="page-link muted">Prev</span>
        @else
            <a class="page-link" href="{{ $paginator->url(1) }}">First</a>
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}">Prev</a>
        @endif

        @foreach ($pages as $page)
            @if ($previousPage && $page > $previousPage + 1)
                <span class="page-link muted" aria-hidden="true">...</span>
            @endif

            @if ($page === $currentPage)
                <span class="page-current" aria-current="page">{{ $page }}</span>
            @else
                <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
            @endif

            @php $previousPage = $page; @endphp
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}">Next</a>
            <a class="page-link" href="{{ $paginator->url($lastPage) }}">Last</a>
        @else
            <span class="page-link muted">Next</span>
            <span class="page-link muted">Last</span>
        @endif
    </div>
@endif
