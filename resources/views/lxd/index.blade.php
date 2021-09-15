@extends('layouts.app')

@section('title', '容器')

@section('content')
    <h1 class="mdui-text-color-theme">容器管理</h1>
    <br />
    <button class="mdui-btn mdui-color-theme-accent mdui-ripple" mdui-dialog="{target: '#webssh-dialog'}">Web SSH</button>
    &nbsp;&nbsp;
    <a href="{{ route('lxd.create') }}" class="mdui-btn mdui-color-theme-accent mdui-ripple">新建 Linux 容器</a>
    <br /><br />
    <div class="mdui-table-fluid mdui-typo">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>内部 ID</th>
                    <th>容器的显示名称</th>
                    <th>CPU 核心数</th>
                    <th>内存</th>
                    <th>硬盘空间</th>
                    <th>内部 IP</th>
                    <th>带宽限制</th>
                    <th>容器所使用的模板</th>
                    <th>属于的服务器</th>
                    <th>属于的项目</th>
                    <th>端口转发</th>
                    <th>总价格</th>
                    <th>可用操作</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="14" class="mdui-text-center">
                        <a href="{{ route('lxd.create') }}">新建 Linux 容器</a>
                    </td>
                </tr>
                @php($i = 1)
                @foreach ($lxdContainers as $lxd)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $lxd->id }}</td>
                        <td>{{ $lxd->name }}</td>
                        <td>{{ $lxd->template->cpu }} Core</td>
                        <td>{{ $lxd->template->mem }}M</td>
                        <td>{{ $lxd->template->disk }} G</td>
                        <td>{{ $lxd->lan_ip }}</td>
                        <td>{{ $lxd->server->network_limit }} Mbps</td>
                        <td>
                            @if ($lxd->status == 'running')
                                <a href="{{ route('lxd.edit', $lxd->id) }}">{{ $lxd->template->name }}</a>
                            @else
                                {{ $lxd->template->name }}
                            @endif
                        </td>
                        <td>{{ $lxd->server->name }}</td>
                        <td><a href="{{ route('projects.show', $lxd->project->id) }}">{{ $lxd->project->name }}</a>
                        </td>
                        @php($forwards = count($lxd->forward))
                        <td>
                            @if ($lxd->status == 'running')
                                <a href="{{ route('forward.index', $lxd->id) }}">{{ $forwards }} 端口</a>
                            @else
                                正在调度
                            @endif


                        </td>
                        <td>{{ $lxd->server->price + $lxd->template->price + $forwards * $lxd->server->forward_price }}/m
                        </td>

                        <td>
                            @if ($lxd->status == 'running')
                                <a href="#" onclick="$('#f-{{ $i }}').submit()">删除</a>
                                <form id="f-{{ $i }}" method="post"
                                    action="{{ route('lxd.destroy', $lxd->id) }}">@csrf @method('DELETE')</form>
                            @else
                                {{ $lxd->status }}
                            @endif
                        </td>

                    </tr>
                @endforeach
                @if ($i > 10)
                    <tr>
                        <td colspan="12" class="mdui-text-center">
                            <a href="{{ route('lxd.create') }}">来 1 份容器，谢谢</a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="mdui-dialog" id="webssh-dialog">
        <div class="mdui-dialog-title">连接到 Web SSH</div>
        <div class="mdui-dialog-content">
            <div class="mdui-textfield">
                <label class="mdui-textfield-label">IP 或者 主机名</label>
                <input class="mdui-textfield-input" id="sshHost" name="hostname" type="text" />
            </div>

            <div class="mdui-textfield">
                <label class="mdui-textfield-label">端口</label>
                <input class="mdui-textfield-input" id="sshPort" name="port" type="text" />
            </div>

            <div class="mdui-textfield">
                <label class="mdui-textfield-label">用户名</label>
                <input class="mdui-textfield-input" id="sshUser" name="username" type="text" />
            </div>

            <div class="mdui-textfield">
                <label class="mdui-textfield-label">密码</label>
                <input class="mdui-textfield-input" id="sshPwd" name="base64Pwd" type="password" />
            </div>
            <input type="hidden" id="realPwd" name="password" />
        </div>
        <div class="mdui-dialog-actions">
            <button class="mdui-btn mdui-ripple" mdui-dialog-close>取消</button>
            <button onclick="gotoWebSSH()" class="mdui-btn mdui-ripple">连接</button>
        </div>

        <script>
            $('#sshPwd').keyup(function() {
                $('#realPwd').val(Base64.encode($('#sshPwd').val()));
            });

            function gotoWebSSH() {
                let hostname = $('#sshHost').val();
                let port = $('#sshPort').val();
                let username = $('#sshUser').val();
                let password = $('#realPwd').val();

                window.open(`https://webssh.lightart.top/?hostname=${hostname}&port=${port}&username=${username}&password=${password}`);
            }
        </script>
    </div>

@endsection
