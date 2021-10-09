@extends('layouts.admin')

@section('title', '管理员')

@section('content')
    <div class="mdui-typo-display-2">管理员主页</div>

    <p>注册用户数量: {{ App\Models\User::count() }}</p>
    <p>Linux 容器 数量: {{ App\Models\LxdContainer::count() }}</p>
    <p>远程桌面 数量: {{ App\Models\RemoteDesktop::count() }}</p>
    <p>Tunnel 数量: {{ App\Models\Tunnel::count() }}</p>
    <p>FastVisit 数量: {{ App\Models\FastVisit::count() }}</p>
    <p>文档 数量: {{ App\Models\Document::count() }}</p>



@endsection
