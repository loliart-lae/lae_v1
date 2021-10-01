@extends('layouts.app')

@section('title', '请求过多')

@section('content')
    <div class="mdui-typo-display-2">Too Many Requests.</div><br>
    <div class="mdui-typo-display-1-opacity">在短时间内请求过多。</div>

    <script type="text/javascript">
        setTimeout(function() {
            window.location.href = '{{ config('app.url') }}';
        }, 60000)
    </script>
@endsection
