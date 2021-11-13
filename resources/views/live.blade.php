@extends('layouts.app')

@section('title', $live->name ?? '暂无流媒体')

@section('content')

@if (!is_null($live))
<div class="mdui-row">
    <div class="mdui-col-xs-12 mdui-col-sm-8" id="streaming_div">
        <div class="mdui-typo-headline">{{ $live->name }}</div>
        <video id="streaming" style="border-radius:5px;margin-top:10px" controls muted autoplay playsinline></video>
    </div>
    <div class="mdui-col-xs-12 mdui-col-sm-4">
        <ul id="comments">
            <li></li>
        </ul>
    </div>
</div>

<script>
    var video_streaming = document.getElementById('streaming')
        var Hls = window.Hls
        var url = '{{ config('app.streaming_play_url') }}'
        if (Hls.isSupported()) {
            var hls = new Hls()
            hls.loadSource(url)
            hls.attachMedia(video_streaming)
        } else if (video_streaming.canPlayType('application/vnd.apple.mpegurl')) {
            video_streaming.src = url
        }
</script>
@else

<div class="mdui-typo-headline mdui-typo">暂时没有流媒体安排，你可以去<a href="{{ route('live.create') }}">安排</a>一个。</div>

@endif


@endsection
