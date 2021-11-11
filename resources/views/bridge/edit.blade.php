@extends('layouts.app')

@section('title', '编辑 Transfer Bridge 集群')

@section('content')
    <div class="mdui-typo-display-1">编辑 Transfer Bridge 集群</div>
    <p>Transfer Bridge 是由LAE研发的文本信息交换集群网络。</p>
    <form method="post" action="{{ route('bridge.update', $bridge->id) }}">
        @csrf
        @method('PUT')
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">集群名称</label>
            <input class="mdui-textfield-input" type="text" name="name" value="{{ $bridge->name }}" />
            <div class="mdui-textfield-helper">修改集群名称。</div>
        </div>

        <div class="mdui-typo-headline mdui-m-t-2">集群设置</div>

        <ul class="mdui-list">

            <li class="mdui-list-item mdui-ripple">
                <i class="mdui-list-item-icon mdui-icon material-icons">lightbulb</i>
                <div class="mdui-list-item-content">启用这个集群</div>
                <label class="mdui-switch">
                    <input type="checkbox" name="enabled" value="1" @if ($bridge->enabled) checked @endif />
                    <i class="mdui-switch-icon"></i>
                </label>
            </li>

            <li class="mdui-list-item mdui-ripple">
                <i class="mdui-list-item-icon mdui-icon material-icons">app_registration</i>
                <div class="mdui-list-item-content">允许自动注册新客户端到组</div>
                <label class="mdui-switch">
                    <input type="checkbox" name="allow_auto_register" value="1" id="allow_auto_register"
                        @if ($bridge->allow_auto_register) checked @endif />
                    <i class="mdui-switch-icon"></i>
                </label>
            </li>

            <label class="mdui-list-item mdui-ripple">
                <i class="mdui-list-item-icon mdui-icon material-icons">fingerprint</i>
                <div class="mdui-list-item-content">重设组识别码并离线所有设备</div>
                <div class="mdui-switch">
                    <input type="checkbox" name="reset_uuid" value="1" />
                    <i class="mdui-switch-icon"></i>
                </div>
            </label>
        </ul>

        <div id="group-select">
            <div class="mdui-typo-headline mdui-m-t-2">请选择组以自动注册</div>

            @if (count($bridge->groups))
                <select class="mdui-select mdui-m-t-2" name="default_group_id" mdui-select>
                    @foreach ($bridge->groups as $group)
                        <option value="{{ $group->id }}" @if ($group->id == $bridge->default_group_id) selected @endif>{{ $group->name }}</option>
                    @endforeach
                </select>
            @else
                没有组可供选择。
            @endif

        </div>

        <script>
            $('#allow_auto_register').on('change', function() {
                if ($('#allow_auto_register').prop('checked')) {
                    $('#group-select').fadeIn()
                } else {
                    $('#group-select').fadeOut()
                }
                mdui.mutation()
            })
        </script>

        <button type="submit"
            class="mdui-m-t-1 mdui-btn mdui-color-theme-accent mdui-ripple umami--click--save-bridge">保存</button>
    </form>
@endsection
