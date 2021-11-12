@extends('layouts.app')

@section('title', '全站时光长河')

@section('content')
    @auth
        @if (\App\Models\LiveTimePeriod::where('status', 1)->exists())
            <video id="streaming" controls style="width: 100%"></video>
            <script>
                var Hls = window.Hls
                var url =
                    '{{ config('app.streaming_proto') }}://{{ config('app.domain') }}:{{ config('app.streaming_port') }}/{{ config('app.streaming_application') }}/aeTimeRiver'
                if (Hls.isSupported()) {
                    var hls = new Hls()
                    hls.loadSource(url)
                    hls.attachMedia(video)
                    hls.on(Hls.Events.MANIFEST_PARSED, function() {
                        video.play()
                    })
                } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                    video.src = url
                    video.addEventListener('canplay', function() {
                        video.play()
                    })
                }
            </script>
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
                <h4><a href="{{ route('main') }}"
                        class="umami--click--user-toggle-timeriver">我的时间河</a>&nbsp;|&nbsp;全站时间河&nbsp;|&nbsp;<a
                        href="{{ route('articles') }}">博文</a></h4>
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
