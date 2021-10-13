@extends('layouts.app')

@section('title', '修改密码')

@section('content')
    <div class="mdui-typo-display-2">修改密码</div>

    <br />
    <form method="post" action="{{ route('remote_desktop.update', $id) }}">
        @csrf
        @method('PUT')

        <div class="mdui-textfield">
            <label class="mdui-textfield-label">正在修改“{{ $rdp->username }}”的密码</label>
            <input class="mdui-textfield-input" type="password" name="password" />
        </div>
        <br />
        <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple">修改</button>
    </form>
@endsection
