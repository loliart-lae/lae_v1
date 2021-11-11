@extends('layouts.app')

@section('title', '编辑客户机')

@section('content')
    <div class="mdui-typo-display-1">编辑客户机</div>
    <form method="post" action="{{ route('bridge.guest.update', [$group->id, $group->bridge->id]) }}">
        @csrf

        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">组名称</label>
            <input class="mdui-textfield-input" type="text" name="name" value="{{ $group->bridge->name }}" />
        </div>

        <div class="mdui-typo-headline mdui-m-t-2">组设置</div>

        <ul class="mdui-list">
            <li class="mdui-list-item mdui-ripple">
                <i class="mdui-list-item-icon mdui-icon material-icons">lightbulb</i>
                <div class="mdui-list-item-content">启用这个组</div>
                <label class="mdui-switch">
                    <input type="checkbox" name="enabled" @if ($group->bridge->enabled) checked @endif />
                    <i class="mdui-switch-icon"></i>
                </label>
            </li>
        </ul>

        <button type="submit"
            class="mdui-m-t-1 mdui-btn mdui-color-theme-accent mdui-ripple umami--click--save-bridge-group">保存</button>
    </form>
@endsection
