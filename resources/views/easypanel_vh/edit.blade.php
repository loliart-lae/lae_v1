@extends('layouts.app')

@section('title', '编辑 ' . $ep->name)

@section('content')
    <div class="mdui-typo-display-2">编辑 {{ $ep->name }}</div>

    <form method="post" action="{{ route('easyPanel.update', $ep->id) }}">
        @csrf
        @method('PUT')
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">名称</label>
            <input class="mdui-textfield-input" type="text" name="name" value="{{ $ep->name }}" />
        </div>

        <div class="mdui-row-md-4 mdui-m-b-2 mdui-m-t-2">
            <div class="mdui-col">
                <label class="mdui-checkbox" mdui-tooltip="{content: '重新设置 EasyPanel 面板的密码。', position: 'right'}">
                    <input type="checkbox" name="reset_pwd" value="1" />
                    <i class="mdui-checkbox-icon"></i>
                    重设密码
                </label>
            </div>
        </div>

        <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--edit-easypanel">保存</button>
    </form>
@endsection
