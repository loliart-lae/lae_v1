@extends('layouts.admin')

@section('title', '查看余额')

@section('content')
    <h1 class="mdui-text-color-theme">余额控制</h1>

    <form method="post" action="{{ route('balance.update', Request::get('email')) }}">
        @csrf


        <br /> <br />
        <span class="mdui-typo-headline">当前用户积分</span>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">积分</label>
            <input class="mdui-textfield-input" type="text" value="{{ $user->balance }}" readonly />
        </div>

        <br /> <br />

        <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple">修改</button>

    </form>
@endsection
