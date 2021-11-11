@extends('layouts.app')

@section('title', '新建 分发组')

@section('content')
    <div class="mdui-typo-display-1">Transfer Bridge Group</div>
    <p>组相当于一个小型的网络，其中的客户机可以广播内容到当前网络。</p>
    <form method="post" action="{{ route('bridge.groups.store', $bridge->id) }}">
        @csrf
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">组名称</label>
            <input class="mdui-textfield-input" type="text" name="name" />
            <div class="mdui-textfield-helper">键入一个组名。</div>
        </div>

        <button type="submit"
            class="mdui-m-t-1 mdui-btn mdui-color-theme-accent mdui-ripple umami--click--new-bridge-group">创建</button>
    </form>
@endsection
