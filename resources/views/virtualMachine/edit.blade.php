@extends('layouts.app')

@section('title', '编辑虚拟机')

@section('content')
    <div class="mdui-typo-display-1">编辑 {{ $virtualMachine->name }}</div>

    <form method="post" action="{{ route('virtualMachine.update', $virtualMachine->id) }}">
        @csrf
        @method('PUT')
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">名称</label>
            <input class="mdui-textfield-input" type="text" name="name" value="{{ $virtualMachine->name }}" />
        </div>

        <div class="mdui-textfield">
            <label class="mdui-textfield-label">虚拟机 IP 地址</label>
            <input class="mdui-textfield-input" type="text" name="ip_address" value="{{ $virtualMachine->ip_address }}" />
            <div class="mdui-textfield-helper">如果不设置，虚拟机将无法连接到网络。</div>
        </div>

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
                url: '{{ route('virtualMachine.get_image', $virtualMachine->server_id) }}',
                success: function(data) {
                    $('#image-ask').remove()
                    for (let i in data) {
                        $('#images').append(`
                            <tr>
                                <td nowrap>${i}</td>
                                <td nowrap>${data[i].volid}</td>
                                <td> <label class="mdui-radio">
                                        <input type="radio" value="${i}" name="image_id" />
                                        <i class="mdui-radio-icon"></i>
                                    </label></td>
                            </tr>
                            `)
                    }
                },
                error: function(data) {
                    $('#image-ask').text('无法询问镜像。')
                }
            })
        </script>


        <button type="submit"
            class="mdui-m-t-3 mdui-btn mdui-color-theme-accent mdui-ripple umami--click--update">保存</button>
    </form>

@endsection
