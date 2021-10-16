@extends('layouts.app')

@section('title', '共享远程桌面')

@section('content')
    <div class="mdui-typo-display-2">共享远程桌面管理</div>

    <a href="{{ route('remote_desktop.create') }}" class="mdui-btn mdui-color-theme-accent mdui-ripple">新建 共享的 Windows
        远程桌面</a>
    <br /><br />

    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>内部 ID</th>
                    <th>用户名</th>
                    <th>CPU</th>
                    <th>内存</th>
                    <th>带宽</th>
                    <th>CPU 使用率</th>
                    <th>内存 使用率</th>
                    <th>属于服务器</th>
                    <th>连接信息</th>
                    <th>积分/分钟</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody class="mdui-typo">
                @php($i = 1)
                @php($project_id = 0)
                @foreach ($remote_desktops as $remote_desktop)
                    @if ($remote_desktop->project->id != $project_id)
                        @php($project_id = $remote_desktop->project->id)
                        <tr>
                            <td colspan="12" class="mdui-text-center">
                                <a
                                    href="{{ route('projects.show', $remote_desktop->project->id) }}">{{ $remote_desktop->project->name }}</a>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td nowrap="nowrap">{{ $i++ }}</td>
                        <td nowrap="nowrap">{{ $remote_desktop->id }}</td>
                        <td nowrap="nowrap">
                            @if ($remote_desktop->status == 'active')
                                <a
                                    href="{{ route('remote_desktop.edit', $remote_desktop->id) }}">{{ $remote_desktop->username }}</a>
                            @else
                                {{ $remote_desktop->username }}
                            @endif
                        </td>
                        <td nowrap="nowrap">{{ $remote_desktop->server->cpu }} Core</td>
                        <td nowrap="nowrap">{{ $remote_desktop->server->mem }}M</td>
                        <td nowrap="nowrap">{{ $remote_desktop->server->network_limit }} Mbps</td>
                        @php($resource = json_decode(Cache::get('windows_server_status_' . 2), true))
                        <td nowrap="nowrap">{{ $resource['cpu'] }}%</td>
                        <td nowrap="nowrap">{{ $resource['mem'] }}%</td>
                        <td nowrap="nowrap">{{ $remote_desktop->server->name }}</td>
                        <td nowrap="nowrap">{{ $remote_desktop->server->domain }}</td>
                        <td nowrap="nowrap">{{ $remote_desktop->server->price }}/m
                        </td>

                        <td nowrap="nowrap">
                            @if ($remote_desktop->status == 'active')
                                <a href="#"
                                    onclick="if (confirm('删除后，该用户所有数据都会丢失并且无法找回！')) { $('#f-{{ $i }}').submit() }">删除</a>
                                <form id="f-{{ $i }}" method="post"
                                    action="{{ route('remote_desktop.destroy', $remote_desktop->id) }}">@csrf
                                    @method('DELETE')</form>
                            @elseif($remote_desktop->status == 'pending')
                                <div class="mdui-progress">
                                    <div class="mdui-progress-indeterminate"></div>
                                </div>
                            @else
                                {{ $remote_desktop->status }}
                            @endif
                        </td>

                    </tr>
                @endforeach
                @if ($i > 10)
                    <tr>
                        <td colspan="12" class="mdui-text-center">
                            <a href="{{ route('remote_desktop.create') }}">新建个 阿噜噜噜噜噜噜吧 账号</a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>


@endsection
