@extends('layouts.app')

@section('title', '时光长河')

@section('content')
    <div class="mdui-typo">
        <span class="mdui-typo-headline">嗨, {{ Auth::user()->name }}。</span>
        <br />
        <span class="mdui-typo-headline-opacity hitokoto_text"></span>
        <br /><br />

        <form method="POST" action="{{ route('status.store') }}">
            @csrf
            <div class="mdui-textfield">
                <textarea class="mdui-textfield-input hitokoto_placeholder" name="content" maxlength="340" rows="4" required
                    placeholder="我存在你的存在。"></textarea>
            </div>
            <button class="mdui-btn mdui-color-theme mdui-ripple">发布</button>
        </form>
        <div class="mdui-m-l-1 mdui-m-r-1">
            <h4>我的时间河&nbsp;|&nbsp;<a class="global-time-river" href="{{ route('global') }}">全站时间河</a>&nbsp;|&nbsp;<a
                    href="{{ route('articles') }}">博文</a></h4>
            @include('include._feed')
        </div>

    </div>

    <script>
        window.util.text.putLyric(function(data) {
            $('.hitokoto_text').text(data.content)
        })
    </script>

@endsection
