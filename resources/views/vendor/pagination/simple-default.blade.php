@if ($paginator->hasPages())
    @if ($paginator->onFirstPage())
        <button disabled class="mdui-float-left mdui-btn mdui-color-theme-accent mdui-ripple">上一页</button>
    @else
        <a href="{{ $paginator->previousPageUrl() }}"
            class="mdui-float-left mdui-btn mdui-color-theme-accent mdui-ripple">上一页</a>
    @endif

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
            class="mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple">下一页</a>
    @else
        <button disabled class="mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple">下一页</button>
    @endif

@endif
