@if ($paginator->hasPages())
    @if ($paginator->onFirstPage())
        <button disabled class="mdui-btn mdui-btn-icon">
            <i class="mdui-icon material-icons-outlined">chevron_left</i>
        </button>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="mdui-btn mdui-btn-icon">
            <i class="mdui-icon material-icons-outlined">chevron_left</i>
        </a>
    @endif

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="mdui-btn mdui-btn-icon">
            <i class="mdui-icon material-icons-outlined">chevron_right</i>
        </a>
    @else
        <button disabled class="mdui-btn mdui-btn-icon">
            <i class="mdui-icon material-icons-outlined">chevron_right</i>
        </button>
    @endif
@endif
