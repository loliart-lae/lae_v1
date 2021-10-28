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
                <textarea class="mdui-textfield-input hitokoto_placeholder" name="content" maxlength="140" rows="4" required
                    placeholder="@if (Auth::user()->balance > 10) {{ Auth::user()->name }}，分享是快乐的，在这里留下一句话就可以快速分享了。 @else 不用担心你的积分来源，它不光能充值获取，为社区做出贡献依旧能获取！去 “全站时间河”看看吧！ @endif "></textarea>
            </div>
            <button class="mdui-btn mdui-color-theme mdui-ripple">发布</button>
        </form>

        <div class="mdui-m-l-1 mdui-m-r-1">
            <h4>我的时间河&nbsp;|&nbsp;<a href="{{ route('global') }}">全站时间河</a>&nbsp;|&nbsp;<a
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
