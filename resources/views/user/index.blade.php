@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <div>
        <h1 class="mdui-text-color-theme">{{ $user->name }}</h1>
        <img class="mdui-img-circle animate__bounceIn" width="25%" src="{{ config('app.gravatar_url') }}/{{ md5($user->email) }}?s=192">
        名称：{{ $user->name }} <br />
        签名：{{ $user->bio }}

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

@endsection
