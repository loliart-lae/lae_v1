@extends('layouts.app')

@section('title', '未经认证')

@section('content')
    <div class="mdui-typo-display-2">Unauthorized.</div><br>
    <div class="mdui-typo-display-1-opacity">此资源没有授权给您访问。</div>

    <script type="text/javascript">
        setTimeout(function() {
            window.location.href = '{{ config('app.url') }}';
        }, 2000)
    </script>
@endsection
