@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <div>
        <!--
        <h1 class="mdui-text-color-theme">你的信息</h1>
        -->

        <div class="mdui-row mdui-typo">
            <div class="mdui-col-xs-12 mdui-col-sm-5">
                <img class="mdui-img-circle animate__bounceIn mdui-center" src="{{ config('app.gravatar_url') }}/{{ md5($user->email) }}?s=192">

            </div>

            <div class="mdui-col-xs-12 mdui-col-sm-7">
                <div class="mdui-typo-display-1">{{ $user->name }}</div>

                <p>积分：{{ $user->balance }}，<a href="{{ route('billing.index') }}">充值</a></p>

                <form method="POST" action="{{ route('user.update', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mdui-textfield">
                        <label class="mdui-textfield-label">签名</label>
                        <input class="mdui-textfield-input" type="text" name="bio" value="{{ $user->bio}}"/>
                    </div>
                    <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple">修改</button>
                </form>
            </div>
        </div>
    </div>

@endsection
