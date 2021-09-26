@extends('layouts.app')

@section('title', '全站时光长河')

@section('content')
    <div class="mdui-typo">
        <span class="mdui-typo-headline">嗨, {{ Auth::user()->name }}。</span>
        <br />
        <span class="mdui-typo-headline-opacity hitokoto_text"></span>
        <br /><br />

        <form method="POST" action="{{ route('status.store') }}">
            @csrf
            <div class="mdui-textfield">
                <textarea class="mdui-textfield-input hitokoto_placeholder" name="content" maxlength="140" rows="4"
                    required autofocus></textarea>
                <div class="mdui-textfield-helper">此刻在想些什么？</div>
            </div>
            <button class="mdui-btn mdui-color-theme mdui-ripple">发布</button>
        </form>



        <h4><a href="{{ route('main') }}">我的时间河</a>&nbsp;|&nbsp;全站时间河</h4>
        @include('include._feed')

    </div>

    <script>
        fetch('https://v1.hitokoto.cn?c=k')
            .then(response => response.json())
            .then(data => {
                // 还是 JQ 来的方便
                $('.hitokoto_text').html(data.hitokoto)
                $('.hitokoto_placeholder').attr('placeholder', data.hitokoto)
            })
            .catch(console.error)
    </script>
@endsection
