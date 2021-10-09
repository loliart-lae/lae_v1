@extends('layouts.admin')

@section('title', '管理员')

@section('content')

    <div class="mdui-typo-display-2">管理员主页</div>

    <div class="mdui-row mdui-m-t-2">
        <div class="mdui-card mdui-p-a-2" style="border-radius: 8px;">
            <div class="mdui-typo-headline">数据总量</div>

            <div class="mdui-col-xs-12 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">注册用户数量</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ App\Models\User::count() }}</div>
            </div>

            <div class="mdui-col-xs-12 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">Linux 容器 数量</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ App\Models\LxdContainer::count() }}</div>
            </div>

            <div class="mdui-col-xs-12 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">远程桌面 数量</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ App\Models\RemoteDesktop::count() }}</div>
            </div>

            <div class="mdui-col-xs-12 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">Tunnel 数量</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ App\Models\Tunnel::count() }}</div>
            </div>
            
            <div class="mdui-col-xs-12 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">FastVisit 数量</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ App\Models\FastVisit::count() }}</div>
            </div>

            <div class="mdui-col-xs-12 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">文档 数量</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ App\Models\Document::count() }}</div>
            </div>
        </div>
    </div>

    



@endsection
