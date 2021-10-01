@extends('layouts.app')

@section('title', '找不到资源')

@section('content')
    <div class="mdui-typo-display-2">Not Found.</div><br>
    <div class="mdui-typo-display-1-opacity">无法找到您请求的资源。</div>

    <script type="text/javascript">
        setTimeout(function() {
            window.location.href = '{{ config('app.url') }}';
        }, 2000)
    </script>
@endsection
