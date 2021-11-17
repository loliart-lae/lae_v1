@extends('layouts.app')

@section('title', '编辑虚拟机')

@section('content')
    <div class="mdui-typo-display-1">编辑 {{ $virtualMachine->name }}</div>

    <form method="post" action="{{ route('virtualMachine.update', $virtualMachine->id) }}">
        @csrf
        @method('PUT')

        <div class="mdui-tab mdui-tab-scrollable mdui-m-t-1" id="page-tab" mdui-tab>
            <a href="#choose-basic" class="mdui-ripple">基础设定</a>
            <a href="#choose-template" class="mdui-ripple">重设模板</a>
            <a href="#choose-image" class="mdui-ripple">CD-ROM</a>
        </div>

        <div id="choose-basic">
            <div class="mdui-textfield">
                <label class="mdui-textfield-label">名称</label>
                <input class="mdui-textfield-input" type="text" name="name" value="{{ $virtualMachine->name }}" />
            </div>

            {{-- <div class="mdui-textfield">
                <label class="mdui-textfield-label">虚拟机 IP 地址</label>
                <input class="mdui-textfield-input" type="text" name="ip_address"
                    value="{{ $virtualMachine->ip_address }}" />
                <div class="mdui-textfield-helper">如果不设置，虚拟机将无法连接到网络。</div>
            </div> --}}
        </div>

        <div id="choose-template">
            <div class="mdui-row mdui-p-t-4 mdui-p-b-2 mdui-p-l-1">
                <span class="mdui-typo-headline">选择 配置模板</span>
                <p class="mdui-typo-subheading">@if ($virtualMachine->status) 在未关闭虚拟机的情况下修改模板将不会及时生效，需要将虚拟机关闭并重新打开才能应用。 @else 配置模板影响着计费，计费每 1 分钟进行一次。 @endif </p>
            </div>
            <div class="mdui-table-fluid">
                <table class="mdui-table mdui-table-hoverable">
                    <thead>
                        <tr>
                            <th>名称</th>
                            <th>CPU</th>
                            <th>内存</th>
                            <th>硬盘</th>
                            <th>硬盘读速度</th>
                            <th>硬盘写速度</th>
                            <th>网络限制</th>
                            <th>积分/分钟</th>
                            <th>月预估</th>
                            <th>选择</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($templates as $template)
                            <tr>
                                <td nowrap>{{ $template->name }}</td>
                                <td nowrap>{{ $template->cpu }}</td>
                                <td nowrap>{{ $template->memory }} M</td>
                                <td nowrap>{{ $template->disk }} G</td>
                                <td nowrap>{{ $template->disk_read }} MB/s</td>
                                <td nowrap>{{ $template->disk_write }} MB/s</td>
                                <td nowrap>{{ $template->network_limit }} MB/s</td>
                                <td nowrap>{{ $template->price }}</td>
                                <td nowrap>
                                    {{ number_format(($template->price * 44640) / config('billing.exchange_rate'), 2) }}
                                    元 /
                                    月
                                </td>

                                <td>
                                    <label class="mdui-radio">
                                        <input type="radio" value="{{ $template->id }}" name="template_id"
                                            @if ($virtualMachine->template_id == $template->id) checked @endif required />
                                        <i class="mdui-radio-icon"></i>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div id="choose-image">
            <div class="mdui-row-md-4 mdui-m-b-2 mdui-m-t-2">
                <div class="mdui-col">
                    <label class="mdui-checkbox"
                        mdui-tooltip="{content: '当勾选此项后，将会卸载虚拟机上的 CD-ROM，下面的选择镜像选项也不会起到任何效果。', position: 'right'}">
                        <input type="checkbox" name="remove_cd_rom" value="1" />
                        <i class="mdui-checkbox-icon"></i>
                        推出 CD-ROM
                    </label>
                </div>
            </div>

            <div class="mdui-row mdui-p-b-2 mdui-p-l-1">
                <span class="mdui-typo-headline">选择镜像</span>
            </div>
            <div class="mdui-table-fluid">
                <table class="mdui-table mdui-table-hoverable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>镜像</th>
                            <th>选择</th>
                        </tr>
                    </thead>
                    <tbody id="images">
                        <tr>
                            <td colspan="3" class="mdui-text-center" id="image-ask"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <script>
                $('#images').html(`<tr>
                            <td colspan="3" class="mdui-text-center" id="image-ask">正在询问镜像...</td>
                        </tr>`)
                $.ajax({
                    url: '{{ route('virtualMachine.getImage', $virtualMachine->server_id) }}',
                    success: function(data) {
                        $('#images').html(null)
                        $('#image-ask').remove()
                        for (let i in data) {
                            $('#images').append(`
                            <tr>
                                <td nowrap>${i}</td>
                                <td nowrap>${data[i].volid}</td>
                                <td> <label class="mdui-checkbox">
                                        <input type="checkbox" value="${i}" name="image_id[]" />
                                        <i class="mdui-checkbox-icon"></i>
                                    </label></td>
                            </tr>
                            `)
                        }
                    },
                    error: function(data) {
                        $('#image-ask').html('无法询问镜像。')
                    }
                })
            </script>
        </div>


        <button type="submit"
            class="mdui-m-t-3 mdui-btn mdui-color-theme-accent mdui-ripple umami--click--update">保存</button>
    </form>

@endsection
