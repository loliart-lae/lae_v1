@extends('layouts.app')

@section('title', '分发组')

@section('content')
    <div class="mdui-typo-display-1">分发组</div>
    <p>Transfer Bridge 是由LAE研发的文本信息交换集群网络。</p>
    <ul class="mdui-list">

        <li class="mdui-list-item mdui-ripple">
            <i class="mdui-list-item-icon mdui-icon material-icons">lightbulb</i>
            <div class="mdui-list-item-content">启用这个集群</div>
            <label class="mdui-switch">
                <input type="checkbox" name="enable" @endif />
                <i class="mdui-switch-icon"></i>
            </label>
        </li>

        <li class="mdui-list-item mdui-ripple">
            <i class="mdui-list-item-icon mdui-icon material-icons">app_registration</i>
            <div class="mdui-list-item-content">允许自动注册新客户端到组</div>
            <label class="mdui-switch">
                <input type="checkbox" name="enable" @endif />
                <i class="mdui-switch-icon"></i>
            </label>
        </li>
    </ul>
@endsection
