@extends('layouts.app')

@section('title', '继续')

@section('content')
    <div class="mdui-typo-display-2">继续...</div>
    <div class="mdui-typo-display-1-opacity">带你到目标地址。</div>

    <br /><br />
    <div class="mdui-typo-display-2 animate__animated animate__fadeInUp">推荐使用 Light App Engine</div>
    <p>这段时间，各种方便实用的功能统统免费使用，现在注册并登录即可长期免费使用。</p>

    <br /><br />
    <p>感谢你看到这里。如果您的浏览器没有自动跳转，请点击下方按钮。</p>
    <a href="{{ $data->uri }}" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent">手动前往 {{ $data->name }}</a>
    <script type="text/javascript">
        setTimeout(function() {
            window.location.href = '{{ $data->uri }}';
        }, 3000)
    </script>
@endsection
