@extends('layouts.app')

@section('title', '编辑 Transfer Bridge 集群')

@section('content')
    <div class="mdui-typo-display-1">Transfer Bridge 集群</div>
    <p>Transfer Bridge 是由LAE研发的文本信息交换集群网络。</p>
    <form method="post" action="{{ route('bridge.store') }}">
        @csrf

        <div class="mdui-textfield mdui-textfield-floating-label">
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
                    <input type="checkbox" name="enabled" @if ($bridge->enabled) checked @endif />
                    <i class="mdui-switch-icon"></i>
                </label>
            </li>

            <li class="mdui-list-item mdui-ripple">
                <i class="mdui-list-item-icon mdui-icon material-icons">app_registration</i>
                <div class="mdui-list-item-content">允许自动注册新客户端到组</div>
                <label class="mdui-switch">
                    <input type="checkbox" name="allow_auto_register" id="allow_auto_register" @if ($bridge->allow_auto_register) checked @endif />
                    <i class="mdui-switch-icon"></i>
                </label>
            </li>
        </ul>

        <div id="group-select">
            <div class="mdui-typo-headline mdui-m-t-2">请选择组以自动注册</div>

            @if (count($bridge->groups))
                <select class="mdui-select mdui-m-t-2" mdui-select>
                    @foreach ($bridge->groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
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

        <div class="mdui-row-md-4 mdui-m-b-2 mdui-m-t-2">
            <div class="mdui-col">
                <label class="mdui-checkbox"
                    mdui-tooltip="{content: '重新设置集群的 通用唯一识别码，如果你的集群通用唯一识别码已经泄漏，可以通过这个选项来重置。但是已加入集群的设备将会全部离线。', position: 'right'}">
                    <input type="checkbox" name="reset_uuid" value="1" />
                    <i class="mdui-checkbox-icon"></i>
                    重设 通用唯一识别码
                </label>
            </div>
        </div>

        <button type="submit"
            class="mdui-m-t-1 mdui-btn mdui-color-theme-accent mdui-ripple umami--click--save-bridge">新建</button>
    </form>
@endsection
