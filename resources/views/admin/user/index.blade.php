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
                        <th style="overflow: visible; text-align: right;">
                            <button class="mdui-btn mdui-btn-icon">
                                <i class="mdui-icon material-icons">chevron_left</i>
                            </button>

                            <button class="mdui-btn mdui-btn-icon">
                                <i class="mdui-icon material-icons">chevron_right</i>
                            </button>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td style="width: 60px;">
                            <img class="avatar" src="{{ config('app.gravatar_url') }}/{{ md5(strtolower($user->email)) }}" />
                        </td>

                        <td style="width: 208px;">
                            @if (is_null($user->website))
                                {{ $user->name }}
                            @else
                                <a target="_blank" href="{{ $user->website }}">{{ $user->name }}</a>
                            @endif
                        </td>

                        <td>
                            {{ $user->bio ?? '未设置签名' }}
                        </td>

                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <style type="text/css">
        .avatar {
            float: left;
            width: 28px;
            height: 28px;
            margin-left: -5px;
            border-radius: 50%;
        }
    </style>


    {{ $users->links() }}

@endsection
