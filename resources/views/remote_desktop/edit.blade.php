@extends('layouts.app')

@section('title', '修改密码并注销')

@section('content')
    <div class="mdui-typo-display-2">修改密码并注销</div>

    <br />
    <form method="post" action="{{ route('remote_desktop.update', $id) }}">
        @csrf
        @method('PUT')

        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">正在修改“{{ $rdp->username }}”的密码</label>
            <input class="mdui-textfield-input" type="password" name="password" />
        </div>
        <p>新密码可以和原密码相同，但是不能为空。修改后会注销账号。</p>
        <br />
        <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple">修改并注销</button>
    </form>
@endsection
