@extends('layouts.admin')

@section('title', '余额管理')

@section('content')
    <h1 class="mdui-text-color-theme">余额管理</h1>

    <form method="get" action="{{ route('admin.balance.user.find') }}">
        <br /> <br />
        <span class="mdui-typo-headline">输入用户邮箱</span>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">邮箱</label>
            <input class="mdui-textfield-input" type="email" name="email" value="{{ old('email') }}" required />
        </div>

        <br /> <br />

        <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple">查询</button>

    </form>
@endsection
