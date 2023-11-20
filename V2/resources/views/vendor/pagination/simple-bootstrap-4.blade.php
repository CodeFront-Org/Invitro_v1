@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">&lsaquo; Previous</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">&lsaquo; Previous</a>
            </li>
        @endif

        {{-- Number of Entries --}}
        <li class="page-item disabled" aria-disabled="true">
            <span class="page-link">{{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} of {{ $paginator->total() }} entries</span>
        </li>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">Next &rsaquo;</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">Next &rsaquo;</span>
            </li>
        @endif
    </ul>
@endif
