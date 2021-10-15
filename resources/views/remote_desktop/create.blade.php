@extends('layouts.app')

@section('title', '新建共享的Windows 远程桌面账户')

@section('content')
    <div class="mdui-typo-display-2">新建共享的 Windows 远程桌面账号</div>

    <p>在选定的项目中新建 共享的 Windows 远程桌面账号。</p>

    <form method="post" id="f-buy-shared-windows" action="{{ route('remote_desktop.store') }}">
        @csrf
        <x-choose-project-form />

        <div class="mdui-row mdui-p-t-4 mdui-p-b-2 mdui-p-l-1">
            <span class="mdui-typo-headline">选择 共享的 Windows 远程桌面 服务器</span>
            <p class="mdui-typo-subheading">共享的 Windows 远程桌面 服务器影响着访问速度以及连通性，稳定性，以及基础价格。</p>
        </div>

        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>CPU</th>
                        <th>内存</th>
                        <th>带宽</th>
                        <th>CPU 使用率</th>
                        <th>内存 使用率</th>
                        <th>基础价格(积分/分钟)</th>
                        <th>选择</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 1)
                    @foreach ($servers as $server)
                        <tr>
                            <td nowrap="nowrap">{{ $i++ }}</td>
                            <td nowrap="nowrap">{{ $server->name }}</td>
                            <td nowrap="nowrap">{{ $server->cpu }}</td>
                            <td nowrap="nowrap">{{ $server->mem }}</td>
                            <td nowrap="nowrap">{{ $server->network_limit }} Mbps</td>
                            @php($status = json_decode(Cache::get('windows_server_status_' . $server->id, ['cpu' => 'null', 'mem' => 'null'])))
                            <td nowrap="nowrap">{{ $status->cpu }}%</td>
                            <td nowrap="nowrap">{{ $status->mem }}%</td>
                            <td nowrap="nowrap">{{ $server->price }}</td>


                            <td>
                                <label class="mdui-radio">
                                    <input type="radio" value="{{ $server->id }} " name="server_id"
                                        @if ($i == 2) checked @endif required />
                                    <i class="mdui-radio-icon"></i>

                                </label>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
            <span class="mdui-typo-headline">用户名</span>
            <p>只允许字母、数字，短破折号（-）和下划线（_）,至少3位，最多15位</p>
            <div class="mdui-textfield mdui-textfield-floating-label">
                <label class="mdui-textfield-label">用户名</label>
                <input class="mdui-textfield-input" type="text" name="username" value="{{ old('username') }}" required />
            </div>
        </div>

        <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
            <span class="mdui-typo-headline">密码</span>
            <p>只允许字母、数字，短破折号（-）和下划线（_）。</p>
            <div class="mdui-textfield mdui-textfield-floating-label">
                <label class="mdui-textfield-label">密码</label>
                <input class="mdui-textfield-input" type="password" name="password" value="{{ old('password') }}" required />
            </div>
        </div>

        <div class="mdui-row mdui-p-y-2">
            <button type="submit" class="mdui-m-l-1 mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple">新建</button>
            <span class="mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple"
                mdui-dialog="{target: '#sub-dialog'}">必看(使用须知)</span>
            

            <div class="mdui-dialog" id="sub-dialog">
                <div class="mdui-dialog-title">请仔细阅读</div>
                <div class="mdui-dialog-content">1. 禁止将“共享的 Windows”用于挖矿、攻击（DDOS，CC）、QEMU、刷流量等。如有发现，将直接删除用户。<br />
                    2. 请勿长时间占用CPU与内存，连续10分钟占CPU 20%以上或者内存占用超过500Mb的，将直接删除用户<br />
                    3. 浏览器部分类型的页面将会持续高强度消耗CPU资源，用完后请关闭。<br />
                    4. 如有需要安装的软件请尽量使用绿色版。<br />
                    5. 为了正常服务，请不要泄漏连接地址。<br />
                    6. 服务器会为了稳定性不定时重启。<br />
                    7. 用的愉快～</div>
                <div class="mdui-dialog-actions">
                    <button class="mdui-btn mdui-ripple" mdui-dialog-close>新建</button>
                </div>
            </div>
        </div>
    </form>

    <div class="mdui-typo" style="text-align: right;margin-top: 10px"><small class="mdui-clearfix">
        注意：每分钟价格 = 地区服务器基础价格<br />共享的 Windows 远程桌面 没有管理员账号，一些软件可用绿色版免安装。
    </small></div>

@endsection
