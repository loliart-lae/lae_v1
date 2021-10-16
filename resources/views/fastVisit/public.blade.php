@extends('layouts.app')

@section('title', '继续')

@section('content')
    <div class="mdui-typo-display-2">继续...</div>
    <div class="mdui-typo-display-1-opacity">{{ config('app.name') }}</div>

    <br /><br />
    <div class="mdui-typo-display-2">当前正在使用 快捷访问功能</div>
    <p>现在注册，即可使用“内网穿透”，“快捷访问”，“共享的 Windows”，“Linux 容器”等功能。</p>

    <br /><br />
    <p>感谢你看到这里。如果您的浏览器没有自动跳转，请点击下方按钮。</p>
    <a href="{{ $data->uri }}" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent umami--click--manual-goto-link">手动前往 {{ $data->name }}</a>
    <script type="text/javascript">
        setTimeout(function() {
            window.location.href = '{{ $data->uri }}';
        }, 3000)
    </script>
@endsection
