@extends('layouts.app')

@section('title', '游戏服务器')

@section('content')
    <div class="mdui-typo-display-2">游戏服务器</div>

    <a href="{{ route('gameServer.create') }}" class="mdui-btn mdui-color-theme-accent mdui-ripple">新建 游戏服务器</a>
    <br /><br />

    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>镜像</th>
                    <th>模板</th>
                    <th>积分/分钟</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody class="mdui-typo">
                @php($project_id = 0)
                @foreach ($gameServers as $gameServer)
                    @if ($gameServer->project->id != $project_id)
                        @php($project_id = $gameServer->project->id)
                        <tr>
                            <td colspan="12" class="mdui-text-center">
                                <a
                                    href="{{ route('projects.show', $gameServer->project->id) }}">{{ $gameServer->project->name }}</a>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td nowrap>{{ $gameServer->id }}</td>
                        <td nowrap><a href="{{ route('gameServer.edit', $gameServer->id) }}">{{ $gameServer->name }}</a>
                        </td>
                        <td nowrap>{{ $gameServer->image->name }}</td>
                        <td nowrap>{{ $gameServer->template->name }}</td>
                        <td nowrap>{{ $gameServer->template->price }}</td>

                        <td nowrap>
                            <a target="_blank"
                                href="{{ config('app.pterodactyl_panel') }}/auth/fastLogin/{{ $gameServer->user->token }}">登录</a>|
                            <a href="#"
                                onclick="if (confirm('删除后，数据将会彻底删除。')) { $('#f-{{ $gameServer->id }}').submit() }">删除</a>
                            <form id="f-{{ $gameServer->id }}" method="post"
                                action="{{ route('gameServer.destroy', $gameServer->id) }}">@csrf
                                @method('DELETE')</form>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


@endsection
