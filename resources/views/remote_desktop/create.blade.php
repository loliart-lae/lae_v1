@extends('layouts.app')

@section('title', '新建共享的Windows 远程桌面账户')

@section('content')
    <div class="mdui-typo-display-2">新建共享的 Windows 远程桌面账号</div>

    <p>在选定的项目中新建 共享的 Windows 远程桌面账号。</p>
    <br />
    <form method="post" id="f-buy-shared-windows" action="{{ route('remote_desktop.store') }}">
        @csrf
        <span class="mdui-typo-headline">选择项目</span>
        <br />
        <br />
        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>项目积分</th>
                        <th>选择</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 1)
                    @foreach ($projects as $project)
                        <tr>
                            <td nowrap="nowrap">{{ $i++ }}</td>
                            <td nowrap="nowrap">{{ $project->project->name }}</td>
                            <td nowrap="nowrap">{{ $project->project->balance }}</td>

                            <td nowrap="nowrap">
                                <label class="mdui-radio">
                                    <input type="radio" value="{{ $project->project->id }}" name="project_id"
                                        @if ($i == 2) checked @endif required />
                                    <i class="mdui-radio-icon"></i>

                                </label>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <br />
        <br />
        <span class="mdui-typo-headline">选择 共享的 Windows 远程桌面 服务器</span>
        <p class="mdui-typo-subheading">共享的 Windows 远程桌面 服务器影响着访问速度以及连通性，稳定性，以及基础价格。</p>
        <br />

        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>CPU</th>
                        <th>内存</th>
                        <th>带宽</th>
                        <th>基础价格(积分/分钟)</th>
                        <th>选择</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 1)
                    @foreach ($servers as $server)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $server->name }}</td>
                            <td>{{ $server->cpu }}</td>
                            <td>{{ $server->mem }}</td>
                            <td>{{ $server->network_limit }} Mbps</td>
                            <td>{{ $server->price }}</td>


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


        <br /> <br />
        <span class="mdui-typo-headline">用户名</span>
        <p>只允许字母、数字，短破折号（-）和下划线（_）,至少3位，最多15位</p>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">用户名</label>
            <input class="mdui-textfield-input" type="text" name="username" value="{{ old('username') }}" required />
        </div>

        <br /> <br />
        <span class="mdui-typo-headline">密码</span>
        <p>只允许字母、数字，短破折号（-）和下划线（_）。</p>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">密码</label>
            <input class="mdui-textfield-input" type="password" name="password" value="{{ old('password') }}" required />
        </div>

        <br /> <br />

        <span onclick="show_buy_tip()" class="mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple">新建</span>
        <script>
            function show_buy_tip() {
                mdui.confirm(`1. 禁止将“共享的 Windows”用于挖矿、攻击（DDOS，CC）、QEMU等。如有发现，将直接删除用户，不保留数据。<br />
            2. 请勿长时间占用CPU与内存，连续10分钟占CPU 20%以上或者内存占用超过500Mb的，将直接删除用户，不保留数据。<br />
            3. 浏览器部分类型的页面将会持续高强度消耗CPU资源，用完后请关闭。<br />
            4. 如有需要安装的软件请尽量使用绿色版。<br />
            5. 为了正常服务，请不要泄漏连接地址。<br />
            6. 服务器会为了稳定性不定时重启。<br />
            7. 用的愉快～`, '请仔细阅读', function() {
                    $('#f-buy-shared-windows').submit()
                })
            }
        </script>

        <br /><br />
        <div class="mdui-typo" style="text-align: right;margin-top: 10px"><small class="mdui-clearfix">注意：每分钟价格 =
                地区服务器基础价格<br />共享的 Windows 远程桌面 没有管理员账号，如需安装软件请前往社区中发帖。一些软件可用绿色版免安装。</small></div>
    </form>
@endsection
