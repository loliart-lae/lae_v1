@if ($paginator->hasPages())
    @if ($paginator->onFirstPage())
        <button disabled class="mdui-btn mdui-btn-icon">
            <i class="mdui-icon material-icons">chevron_left</i>
        </button>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="mdui-btn mdui-btn-icon">
            <i class="mdui-icon material-icons">chevron_right</i>
        </a>
    @endif

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="mdui-btn mdui-btn-icon">
            <i class="mdui-icon material-icons">chevron_right</i>
        </a>
    @else
        <button disabled class="mdui-btn mdui-btn-icon">
            <i class="mdui-icon material-icons">chevron_right</i>
        </button>
    @endif
@endif
