@extends('layouts.admin')

@section('title', '管理员')

@section('content')

    <div class="mdui-typo-display-2">管理员主页</div>
    <p>你好，{{ Auth::user()->name }}。</p>

    @php
        $lxdContainers = App\Models\LxdContainer::count();
        $remote_desktops = App\Models\RemoteDesktop::count();
        $tunnels = App\Models\Tunnel::count();
        $fastVisits = App\Models\FastVisit::count();
        $documents  = App\Models\Document::count();
        $staticPages = App\Models\StaticPage::count();
        $easyPanels = App\Models\EasyPanelVirtualHost::count();

    @endphp

    <div class="mdui-row mdui-m-t-2">
        <div class="mdui-card mdui-p-a-2" style="border-radius: 8px;">
            <div class="mdui-typo-headline">数据总量</div>

            <div class="mdui-col-xs-6 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">注册用户数量</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ App\Models\User::count() }}</div>
            </div>

            <div class="mdui-col-xs-6 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">服务器数量</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ App\Models\Server::count() }}</div>
            </div>

            <div class="mdui-col-xs-6 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">Linux 容器 数量</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ $lxdContainers }}</div>
            </div>

            <div class="mdui-col-xs-6 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">远程桌面 数量</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ $remote_desktops }}</div>
            </div>

            <div class="mdui-col-xs-6 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">Tunnel 数量</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ $tunnels }}</div>
            </div>

            <div class="mdui-col-xs-6 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">快捷访问 数量</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ $fastVisits }}</div>
            </div>

            <div class="mdui-col-xs-6 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">文档 数量</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ $documents }}</div>
            </div>

            <div class="mdui-col-xs-6 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">EasyPanel 站点数量</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ $easyPanels }}</div>
            </div>

            <div class="mdui-col-xs-6 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">静态站点 数量</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ $staticPages }}</div>
            </div>

            <div class="mdui-col-xs-6 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">游戏服务 数量</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ $gameServers }}</div>
            </div>

            <div class="mdui-col-xs-6 mdui-col-sm-2 mdui-m-t-2">
                <div class="mdui-typo-body-1-opacity">服务数量总计</div>
                <div class="mdui-typo-display-1 mdui-m-t-1">{{ $lxdContainers + $remote_desktops + $tunnels + $fastVisits + $documents + $staticPages + $easyPanels }}</div>
            </div>
        </div>
    </div>





@endsection
