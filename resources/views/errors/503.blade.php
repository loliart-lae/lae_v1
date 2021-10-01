@extends('layouts.app')

@section('title', '服务不可用')

@section('content')
    <div class="mdui-typo-display-2">Service Unavailable.</div><br>
    <div class="mdui-typo-display-1-opacity">{{ config('app.name') }} 正在维护，请稍后再试。</div>

    <script type="text/javascript">
        setTimeout(function() {
            window.location.href = '{{ config('app.url') }}';
        }, 60000)
    </script>
@endsection
