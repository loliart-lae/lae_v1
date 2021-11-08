@extends('layouts.app')

@section('title', '安全页面')

@section('content')
    <span style='font-size: 10rem;opacity: .8' class="material-icons-outlined">
        password
    </span>

    <p class="mdui-typo">
        连接地址: <a
            href="{{ $cyberPanelSite->package->server->domain }}">{{ $cyberPanelSite->package->server->domain }}</a>
    </p>
    <p>
        用户名: {{ $cyberPanelSite->owner }}
    </p>
    <p>
        密码: {{ $cyberPanelSite->password }}
    </p>
@endsection
