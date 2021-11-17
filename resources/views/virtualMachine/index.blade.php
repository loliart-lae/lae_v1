@extends('layouts.app')

@section('title', '云虚拟机')

@section('content')

    <style>
        #sideload {
            width: 100%;
            height: 100vh;
            background: transparent;
            position: fixed;
            right: -100%;
            top: 0;
            z-index: 2001;
            transition: all .5 ease-in-out
        }

        #sideload .control {
            width: 3%;
            height: 100%;
        }

        #sideload iframe {
            width: 97%;
            height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            z-index: 2001;
            transition: all .5s cubic-bezier(0.65, 0.05, 0.36, 1)
        }

    </style>

    <script>
        var this_vm, this_vm_cpu_percent, this_vm_memory, this_vm_memory_percent
    </script>
    <div class="mdui-typo-display-2">云虚拟机</div>

    <div class="mdui-row mdui-p-b-2 mdui-p-l-1">
        <a href="{{ route('virtualMachine.create') }}"
            class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--goto-create-tunnel">
            新建 云虚拟机
        </a>
    </div>

    <div>
        <div class="mdui-row">
            @php($i = 1)
            @php($last_project_id = 0)
            @foreach ($virtualMachines as $virtualMachine)
                @php($i++)

                @if ($virtualMachine->project->id !== $last_project_id)
                    @php($last_project_id = $virtualMachine->project->id)
                    <h1 class="mdui-typo-display-1 mdui-col-xs-12 mdui-p-t-2" style="margin:0;position: relative;top:8px">
                        {{ $virtualMachine->project->name }}</h1>
                @endif
                <div class="mdui-col-lg-6 mdui-col-md-6 mdui-col-xs-12">
                    <div class="mdui-card mdui-m-t-2 mdui-shadow-0" style="border: 1px solid #f1f1f1">
                        <div class="mdui-card-primary">
                            <div class="mdui-typo">
                                <div class="mdui-row">
                                    <div class="mdui-col-xs-6 mdui-col-sm-4">
                                        <div>{{ $virtualMachine->id }}.
                                            {{ $virtualMachine->name }}
                                        </div>
                                    </div>

                                    <div class="mdui-col-lg-6 mdui-col-md-6 mdui-col-xs-12 mdui-hidden-sm-down">
                                        <div class="mdui-col-xs-12">
                                            @if ($virtualMachine->status)
                                                <span class="vm_uptime_{{ $virtualMachine->id }}"></span>
                                            @endif
                                        </div>
                                        <div style="position: absolute; right: 0;top: -2px">
                                            <i
                                                class="mdui-icon material-icons-outlined power-btn-{{ $virtualMachine->id }}  @if ($virtualMachine->status) mdui-text-color-green @else mdui-text-color-red @endif">power_settings_new</i>
                                        </div>
                                    </div>

                                </div>
                                <div class="mdui-row">
                                    <div class="mdui-col-xs-6">内部 ID：{{ $virtualMachine->id }}</div>
                                    <div class="mdui-col-xs-6">名称：{{ $virtualMachine->name }}</div>
                                    <div class="mdui-col-xs-6">共享带宽：{{ $virtualMachine->server->network_limit }}
                                        Mbps
                                    </div>
                                    <div class="mdui-col-xs-6">所在服务器：{{ $virtualMachine->server->name }}</div>
                                    <div class="mdui-col-xs-6">所在项目：<a
                                            href="{{ route('projects.show', $virtualMachine->project->id) }}">{{ $virtualMachine->project->name }}</a>
                                    </div>
                                    <div class="mdui-col-xs-6"> 积分/分钟：
                                        {{ $virtualMachine->server->price + $virtualMachine->template->price }}/m
                                    </div>
                                </div>
                                <div class="mdui-row mdui-m-t-3">
                                    <div class="mdui-col-xs-6"> CPU占用 <span
                                            class="vm_{{ $virtualMachine->id }}_cpu_usage">0</span>%
                                        <div class="mdui-progress">
                                            <div class="mdui-progress-determinate"
                                                id="vm_cpu_progress_{{ $virtualMachine->id }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdui-col-xs-6"> 内存占用 <span
                                            class="vm_{{ $virtualMachine->id }}_memory_usage">0</span>%
                                        <div class="mdui-progress">
                                            <div class="mdui-progress-determinate"
                                                id="vm_memory_progress_{{ $virtualMachine->id }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    this_vm = {!! json_encode(Cache::get('ae-vm-status-' . $virtualMachine->id)) !!}
                                    if (this_vm != null) {
                                        this_vm_cpu_percent = Number(this_vm.cpu * 100).toFixed(1);
                                        this_vm_memory = this_vm.mem / this_vm.max_mem
                                        this_vm_memory_percent = Number(this_vm_memory * 100).toFixed(1);
                                        $('.vm_uptime_' + this_vm.id).text(window.util.time.formatSeconds(this_vm.uptime))

                                        $('#vm_memory_progress_' + this_vm.id).width(this_vm_memory_percent + '%')
                                        $('#vm_cpu_progress_' + this_vm.id).width(this_vm_cpu_percent + '%')
                                        $('.vm_' + this_vm.id + '_cpu_usage').text(this_vm_cpu_percent)
                                        $('.vm_' + this_vm.id + '_memory_usage').text(this_vm_memory_percent)
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="mdui-card-actions">
                            <span onclick="power({{ $virtualMachine->id }})"
                                class="power-btn-{{ $virtualMachine->id }} mdui-btn mdui-ripple @if ($virtualMachine->status == 1) mdui-text-color-green @else mdui-text-color-red @endif">电源</span>
                            <a id="vnc_btn_{{ $virtualMachine->id }}"
                                href="javascript: sideload('{{ route('virtualMachine.show', $virtualMachine->id) }}')"
                                @if ($virtualMachine->status) @else disabled @endif class="mdui-btn mdui-ripple umami--click--virtualMachine-show">显示
                                VNC</a>
                            <a href="{{ route('virtualMachine.edit', $virtualMachine->id) }}"
                                class="mdui-btn mdui-ripple umami--click--virtualMachine-edit">编辑</a>
                            <button
                                onclick="if (confirm('确认删除吗？删除将会清除全部数据，并且无法找回！')) {$('#f-{{ $i }}').submit()} else {return false}"
                                class="mdui-btn mdui-ripple umami--click--virtualMachine-delete">销毁</button>
                            <form id="f-{{ $i }}" method="post"
                                action="{{ route('virtualMachine.destroy', $virtualMachine->id) }}">@csrf
                                @method('DELETE')</form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <textarea id="load_vnc_content"
        style="display: none">   <div id="sideload"><div class="control" onclick="closeSideload()"></div>
                                            <iframe src="about:blank" id="load_vnc_href" style="border:0;margin:0;padding:0"></iframe></div></textarea>

    <script>
        let last_vnc_url
        let this_page = window.location.pathname
        setInterval(function() {
            if (window.location.pathname != this_page) {
                $('#load_vnc_href').attr('src', '#')
                $('#sideload').remove()
            }
        }, 1000)
        $('#sideload').remove()

        mdui.mutation()

        function sideload(url) {

            if (url == null) {
                return false;
            }

            if (url != last_vnc_url) {
                $('#sideload').remove()
                $('body').append($('#load_vnc_content').text())
                $('#load_vnc_href').attr('src', url)
            } else {
                // $('#sideload iframe').css('height', '100%')
                $('#load_vnc_href').css('width', '97%')
            }
            mdui.$.showOverlay()

            setTimeout(function() {
                $('#sideload').css('right', '0%')
                $('#sideload').css('top', '0%')
            }, 100)

            last_vnc_url = url
        }

        function closeSideload() {
            mdui.$.hideOverlay()
            $('#sideload').css('right', '-100%')
            $('#sideload').css('top', '-100%')

            $('#sideload iframe').css('width', '0')
        }

        function power(id) {
            let url = '{{ url()->current() }}'
            let btn = '.power-btn-' + id
            $(btn).removeClass('mdui-text-color-green')
            $(btn).removeClass('mdui-text-color-red')
            $(btn).addClass('mdui-text-color-yellow')
            $.ajax({
                type: 'PUT',
                url: url + '/' + id + '/power',
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        if (data.power) {
                            $(btn).removeClass('mdui-text-color-yellow')
                            $(btn).removeClass('mdui-text-color-red')
                            $(btn).addClass('mdui-text-color-green')
                            $('#vnc_btn_' + id).removeAttr('disabled')
                        } else {
                            $(btn).removeClass('mdui-text-color-yellow')
                            $(btn).removeClass('mdui-text-color-green')
                            $(btn).addClass('mdui-text-color-red')
                            $('#vnc_btn_' + id).attr('disabled', 'disabled')
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
