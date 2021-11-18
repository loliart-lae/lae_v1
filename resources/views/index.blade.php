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
        </div>

        <script>
            document.title = window.util.text.c('接下来想要干什么？')
        </script>
    @endguest

@endsection
