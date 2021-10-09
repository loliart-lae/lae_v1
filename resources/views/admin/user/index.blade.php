@extends('layouts.admin')

@section('title', '用户管理')

@section('content')
    <div class="mdui-typo-display-2">用户管理</div>

    <div class="mdui-typo mdui-p-t-3">
        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>头像</th>
                        <th>用户名</th>
                        <th>签名</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>
                            <img class="mdui-card-header-avatar" src="{{ config('app.gravatar_url') }}/{{ md5(strtolower($user->email)) }}" />
                        </td>

                        <td>
                            @if (is_null($user->website))
                                {{ $user->name }}
                            @else
                                <a target="_blank" href="{{ $user->website }}">{{ $user->name }}</a>
                            @endif
                        </td>

                        <td>
                            {{ $user->bio ?? '未设置签名' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>        
    </div>


    {{ $users->links() }}

@endsection
