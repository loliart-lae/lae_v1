@extends('layouts.app')

@section('title', '编辑图表')

@section('content')
    <div class="mdui-typo-display-1">编辑图表</div>

    <form method="post" style="display: none" id="delete-form"
        action="{{ route('serverMonitor.destroy', $monitor->id) }}">
        @csrf
        @method('DELETE')
    </form>

    <form method="post" action="{{ route('serverMonitor.update', $monitor->id) }}">
        @csrf
        @method('PUT')

        <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
            <span class="mdui-typo-headline">名称</span>
            <p>标识该图表</p>
            <div class="mdui-textfield">
                <label class="mdui-textfield-label">名称</label>
                <input class="mdui-textfield-input" type="text" name="name" value="{{ $monitor->name }}" required />
            </div>
        </div>

        <div class="mdui-row-md-4 mdui-m-b-2 mdui-m-t-1">
            <div class="mdui-col">
                <label class="mdui-checkbox">
                    <input type="checkbox" id="is_public" name="is_public" value="1" @if ($monitor->public) checked @endif />
                    <i class="mdui-checkbox-icon"></i>
                    是否公开
                </label>
            </div>

        </div>

        <div class="mdui-row-md-4 mdui-m-b-2">
            <div class="mdui-col">
                <label class="mdui-checkbox" mdui-tooltip="{content: '重置后，你需要使用新的token来提交数据。', position: 'right'}">
                    <input type="checkbox" name="reset_token" value="1" />
                    <i class="mdui-checkbox-icon"></i>
                    重置认证
                </label>
            </div>
        </div>


        <div class="mdui-m-b-3" id="public_url">
            公开地址: <span class="mdui-typo"><a href="{{ route('serverMonitor.public', $monitor->id) }}"
                    target="_blank">{{ route('serverMonitor.public', $monitor->id) }}</a></span>
        </div>

        <div class="mdui-textfield">
            <label class="mdui-textfield-label">Token</label>
            <input class="mdui-textfield-input" value="{{ $monitor->token }}" readonly />
        </div>

        <br />

        <script>
            $('#is_public').on('change', function() {
                if ($(this).prop('checked')) {
                    $('#public_url').show()
                } else {
                    $('#public_url').hide()
                }
            })
        </script>


        <button class="mdui-btn mdui-ripple mdui-color-theme">修改</button>
        <span class="mdui-btn mdui-ripple mdui-color-theme" onclick="$('#delete-form').submit()">删除</span>

    </form>




@endsection
