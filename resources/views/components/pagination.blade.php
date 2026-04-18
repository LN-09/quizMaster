@if ($paginator->hasPages())
<nav class="pagination">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="page-item disabled">← Trước</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="page-item">← Trước</a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="page-item disabled">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="page-item active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-item">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="page-item">Sau →</a>
    @else
        <span class="page-item disabled">Sau →</span>
    @endif
</nav>

<style>
.pagination { display:flex; gap:.35rem; align-items:center; flex-wrap:wrap; }
.page-item {
    padding:.35rem .7rem; border-radius:6px; font-size:.82rem;
    border:1px solid var(--gray-200); color:var(--gray-700);
    text-decoration:none; transition:background .15s;
}
.page-item:hover:not(.disabled):not(.active) { background:var(--gray-100); }
.page-item.active   { background:var(--primary); color:#fff; border-color:var(--primary); }
.page-item.disabled { opacity:.45; cursor:default; }
</style>
@endif
