@extends('layouts.app')

@section('title', '应用容器')

@section('content')
    <div class="mdui-typo-display-2">应用容器</div>

    <button class="mdui-btn mdui-color-theme-accent mdui-ripple" mdui-dialog="{target: '#webssh-dialog'}">Web SSH</button>

    <a href="{{ route('lxd.create') }}" class="mdui-btn mdui-color-theme-accent mdui-ripple">新建 应用容器</a>
    {{-- &nbsp;&nbsp;
    <a href="{{ route('images.index') }}" class="mdui-btn mdui-color-theme-accent mdui-ripple">镜像管理</a> --}}
    <div class="mdui-typo">

        @php($i = 1)
        @php($last_project_id = 0)
        @foreach ($lxdContainers as $lxd)
            @if ($lxd->project->id !== $last_project_id)
                @php($last_project_id = $lxd->project->id)
                <h1>{{ $lxd->project->name }}</h1>
            @endif
            <div class="mdui-panel mdui-m-t-1 mdui-panel-popout" mdui-panel>
                <div class="mdui-panel-item @if ($i == 1) mdui-panel-item-open @endif">
                    <div class="mdui-panel-item-header umami--click--show-lxd-panel">
                        <div class="mdui-panel-item-title">{{ $i++ }}. {{ $lxd->name }}</div>
                        <div class="mdui-panel-item-summary">
                            @if ($lxd->status == 'running')
                                <a href="{{ route('lxd.edit', $lxd->id) }}">{{ $lxd->template->name }}</a>
                            @else
                                {{ $lxd->template->name }}
                            @endif
                        </div>
                        <div class="mdui-panel-item-summary">
                            @if (is_null($lxd->lan_ip))
                                <div class="mdui-progress">
                                    <div class="mdui-progress-indeterminate"></div>
                                </div>
                            @else
                                <div id="power-{{ $lxd->id }}">
                                    <i class="mdui-icon material-icons power-btn mdui-text-color-green"
                                        onclick="power({{ $lxd->id }}, {{ $lxd->project_id }})">power_settings_new</i>
                                </div>
                            @endif
                        </div>
                        <i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
                    </div>
                    <div class="mdui-panel-item-body">
                        <p>名称：{{ $lxd->name }}</p>
                        <p>核心：{{ $lxd->template->cpu }} Core</p>
                        <p>内存：{{ $lxd->template->mem }}M</p>
                        <p>存储：{{ $lxd->template->disk }} G</p>
                        <p>内部 IP：{{ $lxd->lan_ip }}</p>
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
                            积分/分钟：
                            @if ($lxd->status == 'running')
                                {{ $lxd->server->price + $lxd->template->price + $forwards * $lxd->server->forward_price }}/m
                            @else
                                {{ $lxd->server->price + $lxd->template->price + $forwards * $lxd->server->forward_price * 0.9 }}/m
                            @endif
                        </p>

                        <div class="mdui-panel-item-actions">
                            @if ($lxd->status == 'running')
                                <a class="mdui-btn mdui-ripple umami--click--goto-forwards"
                                    href="{{ route('forward.index', $lxd->id) }}">{{ $forwards }} 端口</a>
                            @else
                                <button class="mdui-btn mdui-ripple" mdui-panel-item-close>
                                    无法操作
                                </button>
                            @endif
                            @if ($lxd->status == 'running' || $lxd->status == 'failed')
                                <button
                                    onclick="if (confirm('确认删除吗？删除将会清除全部数据，并且无法找回！')) {$('#f-{{ $i }}').submit()} else {return false}"
                                    class="mdui-btn mdui-ripple umami--click--lxd-delete">销毁</button>
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

            function power(id, project_id) {
                $('#power-' + id + ' .power-btn').removeClass('mdui-text-color-green')
                $('#power-' + id + ' .power-btn').removeClass('mdui-text-color-red')
                $('#power-' + id + ' .power-btn').addClass('mdui-text-color-yellow')
                $.ajax({
                    type: 'PUT',
                    url: '{{ url()->current() }}' + '/' + id + '/power',
                    data: {
                        'project_id': project_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.power == 'running') {
                            $('#power-' + id + ' .power-btn').removeClass('mdui-text-color-yellow')
                            $('#power-' + id + ' .power-btn').removeClass('mdui-text-color-red')
                            $('#power-' + id + ' .power-btn').addClass('mdui-text-color-green')
                        } else {
                            $('#power-' + id + ' .power-btn').removeClass('mdui-text-color-yellow')
                            $('#power-' + id + ' .power-btn').removeClass('mdui-text-color-green')
                            $('#power-' + id + ' .power-btn').addClass('mdui-text-color-red')
                        }
                    }
                })
            }
        </script>
    </div>

@endsection
