@extends('layouts.app')

@section('title', '虚拟机')

@section('content')
    <div class="mdui-typo-display-2">虚拟机</div>

    <div class="mdui-row mdui-p-b-2 mdui-p-l-1">
        <a href="{{ route('virtualMachine.create') }}"
            class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--goto-create-tunnel">
            新建 虚拟机
        </a>
    </div>

    <div>

        @php($i = 1)
        @php($last_project_id = 0)
        @foreach ($virtualMachines as $virtualMachine)
            @if ($virtualMachine->project->id !== $last_project_id)
                @php($last_project_id = $virtualMachine->project->id)
                <h1>{{ $virtualMachine->project->name }}</h1>
            @endif
            <div class="mdui-panel mdui-m-t-1 mdui-panel-popout" mdui-panel>
                <div class="mdui-panel-item @if ($i == 1) mdui-panel-item-open @endif">
                    <div class="mdui-panel-item-header mdui-typo umami--click--show-lxd-panel">
                        <div class="mdui-panel-item-title">{{ $i++ }}. {{ $virtualMachine->name }}</div>
                        <div class="mdui-panel-item-summary">
                            {{ $virtualMachine->ip_address }}
                        </div>
                        <div class="mdui-panel-item-summary">
                            <div id="power-{{ $virtualMachine->id }}">
                                @if ($virtualMachine->status == 1)
                                    <i class="mdui-icon material-icons-outlined power-btn mdui-text-color-green"
                                        onclick="power({{ $virtualMachine->id }})">power_settings_new</i>
                                @else
                                    <i class="mdui-icon material-icons-outlined power-btn mdui-text-color-red"
                                        onclick="power({{ $virtualMachine->id }})">power_settings_new</i>
                                @endif

                            </div>
                        </div>
                        <i class="mdui-panel-item-arrow mdui-icon material-icons-outlined">keyboard_arrow_down</i>
                    </div>
                    <div class="mdui-panel-item-body">
                        <div class="mdui-typo">
                            <p>内部 ID：{{ $virtualMachine->id }}</p>
                            <p>名称：{{ $virtualMachine->name }}</p>
                            <p>核心：{{ $virtualMachine->template->cpu }} Core</p>
                            <p>内存：{{ $virtualMachine->template->memory }} M</p>
                            <p>存储：{{ $virtualMachine->template->disk }} G</p>
                            <p>网络限制：{{ $virtualMachine->server->network_limit }} Mbps</p>
                            <p>所在服务器：{{ $virtualMachine->server->name }}</p>
                            <p>所在项目：<a
                                    href="{{ route('projects.show', $virtualMachine->project->id) }}">{{ $virtualMachine->project->name }}</a>
                            </p>
                            <p>
                                积分/分钟：
                                {{ $virtualMachine->server->price + $virtualMachine->template->price }}/m
                            </p>
                        </div>
                        <div class="mdui-panel-item-actions">
                            <button onclick="window.open('{{ route('virtualMachine.show', $virtualMachine->id) }}')"
                                class="mdui-btn mdui-ripple umami--click--virtualMachine-show">显示 VNC</button>
                            <a href="{{ route('virtualMachine.edit', $virtualMachine->id) }}"
                                class="mdui-btn mdui-ripple umami--click--virtualMachine-edit">编辑</a>
                            @if ($virtualMachine->status == 1 || $virtualMachine->status == 0)
                                <button
                                    onclick="if (confirm('确认删除吗？删除将会清除全部数据，并且无法找回！')) {$('#f-{{ $i }}').submit()} else {return false}"
                                    class="mdui-btn mdui-ripple umami--click--virtualMachine-delete">销毁</button>
                                <form id="f-{{ $i }}" method="post"
                                    action="{{ route('virtualMachine.destroy', $virtualMachine->id) }}">@csrf
                                    @method('DELETE')</form>
                            @else
                                <button class="mdui-btn mdui-ripple">{{ $virtualMachine->status }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        mdui.mutation()

        function power(id) {
            $('#power-' + id + ' .power-btn').removeClass('mdui-text-color-green')
            $('#power-' + id + ' .power-btn').removeClass('mdui-text-color-red')
            $('#power-' + id + ' .power-btn').addClass('mdui-text-color-yellow')
            $.ajax({
                type: 'PUT',
                url: '{{ url()->current() }}' + '/' + id + '/power',
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        if (data.power == 1) {
                            $('#power-' + id + ' .power-btn').removeClass('mdui-text-color-yellow')
                            $('#power-' + id + ' .power-btn').removeClass('mdui-text-color-red')
                            $('#power-' + id + ' .power-btn').addClass('mdui-text-color-green')
                        } else {
                            $('#power-' + id + ' .power-btn').removeClass('mdui-text-color-yellow')
                            $('#power-' + id + ' .power-btn').removeClass('mdui-text-color-green')
                            $('#power-' + id + ' .power-btn').addClass('mdui-text-color-red')
                        }
                    } else {
                        mdui.snackbar({
                            'position': 'right-bottom',
                            'message': data.msg
                        })
                    }

                }
            })
        }
    </script>


@endsection
