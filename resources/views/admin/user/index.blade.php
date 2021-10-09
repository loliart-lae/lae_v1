@extends('layouts.admin')

@section('title', '用户管理')

@section('content')
    <div class="mdui-typo-display-2">用户管理</div>

    <div class="mdui-typo">
        @foreach ($users as $user)
            <div class="mdui-col-sm-4 mdui-col-xs-12 mdui-m-t-1">
                <div class="mdui-card mdui-hoverable" style="margin-top: 5px">
                    <div class="mdui-card-header">
                        <img class="mdui-card-header-avatar"
                            src="{{ config('app.gravatar_url') }}/{{ md5(strtolower($user->email)) }}" />
                        <div class="mdui-card-header-title">
                            @if (is_null($user->website))
                                {{ $user->name }}
                            @else
                                <a target="_blank" href="{{ $user->website }}">{{ $user->name }}</a>
                            @endif
                        </div>
                        <div class="mdui-card-header-subtitle">{{ $user->bio ?? '未设置签名' }}</div>
                    </div>
                    <div class="mdui-card-content mdui-p-t-1">
                    </div>
                    <div class="mdui-card-actions">
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    {{ $users->links() }}

@endsection
