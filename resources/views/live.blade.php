@extends('layouts.app')

@section('title', $live->name ?? '暂无流媒体')

@section('content')

@if (!is_null($live))
<div id="streaming_div">
    <div class="mdui-typo-headline">{{ $live->name }}</div>

    <video id="streaming" style="width:100%;border-radius:5px;margin-top:10px" controls></video>
</div>

<script>
    var video_streaming = document.getElementById('streaming')
        var Hls = window.Hls
        var url =
            '//{{ config('app.domain') }}/streaming/aeTimeRiver.m3u8'
        if (Hls.isSupported()) {
            var hls = new Hls()
            hls.loadSource(url)
            hls.attachMedia(video_streaming)
            hls.on(Hls.Events.MANIFEST_PARSED, function() {
                video_streaming.play()
            })
        } else if (video_streaming.canPlayType('application/vnd.apple.mpegurl')) {
            video_streaming.src = url
            video_streaming.addEventListener('canplay', function() {
                video_streaming.play()
            })
        }
</script>
@else

<div class="mdui-typo-headline mdui-typo">暂时没有流媒体安排，你可以去<a href="{{ route('live.create') }}">安排</a>一个。</div>

@endif


@endsection
