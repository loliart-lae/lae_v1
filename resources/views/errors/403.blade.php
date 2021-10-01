@extends('layouts.app')

@section('title', '禁止访问')

@section('content')
    <div class="mdui-typo-display-2">Forbidden.</div><br>
    <div class="mdui-typo-display-1-opacity">您没有访问资源的权限。</div>

    <script type="text/javascript">
        setTimeout(function() {
            window.location.href = '{{ config('app.url') }}';
        }, 2000)
    </script>
@endsection
