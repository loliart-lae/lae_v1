@extends('layouts.app')

@section('title', '今日')

@section('content')
    @guest
        <div class="mdui-typo-headline">夜空，因为谁而亮起。</div>
        <br />
        <div class="mdui-typo-title-opacity">散落的雨滴，何尝不是一种美丽。</div>

    @else
        <div class="mdui-typo mdui-p-t-4">
            <div class="mdui-text-center mdui-typo-display-1-opacity">
                <span class="material-icons">north_west</span>打开抽屉以继续您的工作。
            </div>

            <div class="mdui-text-center mdui-typo-caption-opacity">
                我们意识到了LAE目前存在的问题，并且我们正在编码新的平台。
                <br />
                这需要一段时间给我们打磨，以给大家营造出最好的体验。
            </div>

        </div>

        <script>
            document.title = window.util.text.c('接下来想要干什么？')
        </script>
    @endguest

@endsection
