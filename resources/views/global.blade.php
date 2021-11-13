@extends('layouts.app')

@section('title', '全站时光长河')

@section('content')
@auth
@php($live = \App\Models\LiveTimePeriod::where('status', 1)->first())
@if (!is_null($live))
<div id="streaming_div" class="mdui-typo">
    <div class="mdui-typo-headline"><a href="{{ route('streaming') }}">{{ $live->name }}</a></div>

    <video id="streaming" style="width:100%;border-radius:5px;margin-top:10px" controls muted autoplay playsinline>
        <source src="{{ config('app.streaming_play_url') }}" />
    </video>
</div>

@endif
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
        <h4><a href="{{ route('main') }}" class="umami--click--user-toggle-timeriver">我的时间河</a>&nbsp;|&nbsp;<span
                class="global-time-river">全站时间河</span>&nbsp;|&nbsp;<a href="{{ route('articles') }}">博文</a></h4>
        @include('include._feed')
    </div>

</div>
@else
<div class="mdui-m-l-1 mdui-m-r-1 mdui-typo">
    @include('include._feed')
</div>
@endauth

<script>
    window.util.text.putLyric(function(data) {
            $('.hitokoto_text').text(data.content)
        })
</script>
@endsection
