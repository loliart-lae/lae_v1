@extends('layouts.app')

@section('title', '全站时光长河')

@section('content')
    <div class="mdui-typo">
        <span class="mdui-typo-headline">嗨, {{ Auth::user()->name }}。</span>
        <br />
        <span class="mdui-typo-headline-opacity hitokoto_text"></span>

        <form method="POST" action="{{ route('status.store') }}">
            @csrf
            <div class="mdui-textfield">
                <textarea class="mdui-textfield-input hitokoto_placeholder umami--input--status" name="content"
                    maxlength="340" rows="4" required placeholder="I am because you are."></textarea>
            </div>
            <button class="mdui-btn mdui-color-theme mdui-ripple umami--click--publish-status">发布</button>
        </form>

        <div class="mdui-m-l-1 mdui-m-r-1">
            <h4><a href="{{ route('main') }}"
                    class="umami--click--user-toggle-timeriver">我的时间河</a>&nbsp;|&nbsp;全站时间河&nbsp;|&nbsp;<a
                    href="{{ route('articles') }}">博文</a></h4>
            @include('include._feed')
        </div>

    </div>

    <script>
        fetch('https://v1.hitokoto.cn?c=k')
            .then(response => response.json())
            .then(data => {
                // 还是 JQ 来的方便
                $('.hitokoto_text').html(data.hitokoto)
                // $('.hitokoto_placeholder').attr('placeholder', data.hitokoto)
            })
            .catch(console.error)
    </script>
@endsection
