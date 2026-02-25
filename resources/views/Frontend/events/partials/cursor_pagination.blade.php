{{-- Cursor Pagination Component --}}
@if(isset($events) && $events->hasPages())
    <div class="cursor-pagination" style="display: flex; justify-content: center; margin: 20px;">
        <nav>
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if($events->previousCursor())
                    <li class="page-item">
                        <a class="page-link cursor-pagination-link" 
                           href="{{ request()->fullUrlWithQuery(['cursor' => $events->previousCursor()->encode()]) }}" 
                           aria-label="Previous"
                           data-cursor="{{ $events->previousCursor()->encode() }}">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>
                @endif

                {{-- Current Page Info --}}
                <li class="page-item disabled">
                    <span class="page-link">
                        @if($events->previousCursor() && $events->nextCursor())
                            Page {{ $events->count() > 0 ? '2+' : '1' }}
                        @elseif($events->previousCursor())
                            Last Page
                        @else
                            Page 1
                        @endif
                    </span>
                </li>

                {{-- Next Page Link --}}
                @if($events->nextCursor())
                    <li class="page-item">
                        <a class="page-link cursor-pagination-link" 
                           href="{{ request()->fullUrlWithQuery(['cursor' => $events->nextCursor()->encode()]) }}" 
                           aria-label="Next"
                           data-cursor="{{ $events->nextCursor()->encode() }}">
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>

    {{-- Load More Button for AJAX --}}
    @if(request()->ajax())
        <div class="load-more-container text-center mt-4" style="display: none;">
            <button class="btn btn-primary load-more-btn" 
                    data-next-cursor="{{ $events->nextCursor()?->encode() }}"
                    data-has-more="{{ $events->hasMorePages() ? 'true' : 'false' }}">
                <i class="fa fa-spinner fa-spin d-none"></i>
                <span class="btn-text">Load More Events</span>
            </button>
        </div>
    @endif
@endif

{{-- Infinite Scroll Container --}}
<div class="infinite-scroll-trigger" style="height: 20px;"></div>
