@extends('layouts.app')

@section('title', '出现错误')

@section('content')
    <div class="mdui-typo-display-2">Server Error.</div><br>
    <div class="mdui-typo-display-1-opacity">暂时无法处理请求。</div>

    <script type="text/javascript">
        setTimeout(function() {
            window.location.href = '{{ config('app.url') }}';
        }, 2000)
    </script>
@endsection
