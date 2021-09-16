@extends('layouts.app')

@section('title', '容器')

@section('content')
    <h1 class="mdui-text-color-theme">容器管理</h1>

    <button class="mdui-btn mdui-color-theme-accent mdui-ripple" mdui-dialog="{target: '#webssh-dialog'}">Web SSH</button>
    &nbsp;&nbsp;
    <a href="{{ route('lxd.create') }}" class="mdui-btn mdui-color-theme-accent mdui-ripple">新建 Linux 容器</a>
    <br /><br />
    <div class="mdui-typo">

        @php($i = 1)
        @php($last_project_id = 0)
        @foreach ($lxdContainers as $lxd)
            @if ($lxd->project->id !== $last_project_id)
                @php($last_project_id = $lxd->project->id)
                <h1>{{ $lxd->project->name }}</h1>
            @endif
            <div class="mdui-panel" mdui-panel>
                <div class="mdui-panel-item">
                    <div class="mdui-panel-item-header">
                        <div class="mdui-panel-item-title">{{ $i++ }}. {{ $lxd->name }}</div>
                        <div class="mdui-panel-item-summary">
                            @if ($lxd->status == 'running')
                                <a href="{{ route('lxd.edit', $lxd->id) }}">{{ $lxd->template->name }}</a>
                            @else
                                {{ $lxd->template->name }}
                            @endif
                        </div>
                        <div class="mdui-panel-item-summary">{{ $lxd->lan_ip }}</div>
                        <i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
                    </div>
                    <div class="mdui-panel-item-body">
                        <p>内部 ID：{{ $lxd->id }}</p>
                        <p>名称：{{ $lxd->name }}</p>
                        <p>核心：{{ $lxd->template->cpu }} Core</p>
                        <p>内存：{{ $lxd->template->mem }}M</p>
                        <p>存储：{{ $lxd->template->disk }} G</p>
                        <p>内部IP：{{ $lxd->lan_ip }}</p>
                        <p>网络限制：{{ $lxd->server->network_limit }} Mbps</p>
                        <p>模板名称：
                            @if ($lxd->status == 'running')
                                <a href="{{ route('lxd.edit', $lxd->id) }}">{{ $lxd->template->name }}</a>
                            @else
                                {{ $lxd->template->name }}
                            @endif
                        </p>
                        @php($forwards = count($lxd->forward))
                        <p>所在服务器：{{ $lxd->server->name }}</p>
                        <p>所在项目：<a
                                href="{{ route('projects.show', $lxd->project->id) }}">{{ $lxd->project->name }}</a>
                        </p>
                        <p>
                            价格：{{ $lxd->server->price + $lxd->template->price + $forwards * $lxd->server->forward_price }}/m
                        </p>

                        <div class="mdui-panel-item-actions">
                            @if ($lxd->status == 'running')
                                <a class="mdui-btn mdui-ripple"
                                    href="{{ route('forward.index', $lxd->id) }}">{{ $forwards }} 端口</a>
                            @else
                                <button class="mdui-btn mdui-ripple" mdui-panel-item-close>正在调度</button>
                            @endif
                            @if ($lxd->status == 'running')
                                <button onclick="$('#f-{{ $i }}').submit()"
                                    class="mdui-btn mdui-ripple">销毁</button>
                                <form id="f-{{ $i }}" method="post"
                                    action="{{ route('lxd.destroy', $lxd->id) }}">@csrf @method('DELETE')</form>
                            @else
                                <button class="mdui-btn mdui-ripple">{{ $lxd->status }}</button>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{-- <div class="mdui-table-fluid mdui-typo">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>内部 ID</th>
                    <th>显示名称</th>
                    <th>CPU</th>
                    <th>内存</th>
                    <th>硬盘</th>
                    <th>内部 IP</th>
                    <th>带宽限制</th>
                    <th>使用模板</th>
                    <th>属于服务器</th>
                    <th>属于项目</th>
                    <th>端口转发</th>
                    <th>价格</th>
                    <th>操作</th>
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
                        <td nowrap="nowrap">{{ $i++ }}</td>
                        <td nowrap="nowrap">{{ $lxd->id }}</td>
                        <td nowrap="nowrap">{{ $lxd->name }}</td>
                        <td nowrap="nowrap">{{ $lxd->template->cpu }} Core</td>
                        <td nowrap="nowrap">{{ $lxd->template->mem }}M</td>
                        <td nowrap="nowrap">{{ $lxd->template->disk }} G</td>
                        <td nowrap="nowrap">{{ $lxd->lan_ip }}</td>
                        <td nowrap="nowrap">{{ $lxd->server->network_limit }} Mbps</td>
                        <td nowrap="nowrap">
                            @if ($lxd->status == 'running')
                                <a href="{{ route('lxd.edit', $lxd->id) }}">{{ $lxd->template->name }}</a>
                            @else
                                {{ $lxd->template->name }}
                            @endif
                        </td>
                        <td nowrap="nowrap">{{ $lxd->server->name }}</td>
                        <td nowrap="nowrap"><a
                                href="{{ route('projects.show', $lxd->project->id) }}">{{ $lxd->project->name }}</a>
                        </td>
                        @php($forwards = count($lxd->forward))
                        <td nowrap="nowrap">
                            @if ($lxd->status == 'running')
                                <a href="{{ route('forward.index', $lxd->id) }}">{{ $forwards }} 端口</a>
                            @else
                                正在调度
                            @endif


                        </td>
                        <td nowrap="nowrap">
                            {{ $lxd->server->price + $lxd->template->price + $forwards * $lxd->server->forward_price }}/m
                        </td>

                        <td nowrap="nowrap">
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
    </div> --}}

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
                $('#realPwd').val(Base64.encode($('#sshPwd').val()))
            });

            function gotoWebSSH() {
                let hostname = $('#sshHost').val()
                let port = $('#sshPort').val()
                let username = $('#sshUser').val()
                let password = $('#realPwd').val()

                window.open(
                    `https://webssh.lightart.top/?hostname=${hostname}&port=${port}&username=${username}&password=${password}`
                );
            }

            mdui.mutation()
        </script>
    </div>

@endsection
