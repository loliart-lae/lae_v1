@extends('layouts.app')

@section('title', '需要登录')

@section('content')

<div class="mdui-typo-display-2">前往登录...</div><br>
<div class="mdui-typo-display-1-opacity">需要登录后才能继续。</div>

<script type="text/javascript">
    setTimeout(function() {
        window.location.href = '{{ route('login') }}';
    }, 2000)
</script>
@endsection
