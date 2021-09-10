@extends('layouts.app')

@section('title', '共享远程桌面')

@section('content')
    <h1 class="mdui-text-color-theme">共享远程桌面管理</h1>

    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>内部ID</th>
                    <th>用户名</th>
                    <th>CPU</th>
                    <th>内存</th>
                    <th>带宽</th>
                    <th>属于服务器</th>
                    <th>属于项目</th>
                    <th>连接信息</th>
                    <th>总价格</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody class="mdui-typo">
                <tr>
                    <td colspan="14" class="mdui-text-center">
                        <a href="{{ route('remote_desktop.create') }}">新建 共享的 Windows 远程桌面</a>
                    </td>
                </tr>
                @php($i = 1)
                @foreach ($remote_desktops as $remote_desktop)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $remote_desktop->id }}</td>
                        <td>
                            @if ($remote_desktop->status == 'active')
                                <a href="{{ route('remote_desktop.edit', $remote_desktop->id) }}">{{ $remote_desktop->username }}</a>
                            @else
                                {{ $remote_desktop->username }}
                            @endif
                        </td>
                        <td>{{ $remote_desktop->server->cpu }} Core</td>
                        <td>{{ $remote_desktop->server->mem }}M</td>
                        <td>{{ $remote_desktop->server->network_limit }} Mbps</td>
                        <td>{{ $remote_desktop->server->name }}</td>
                        <td><a
                                href="{{ route('projects.show', $remote_desktop->project->id) }}">{{ $remote_desktop->project->name }}</a>
                        </td>
                        <td>{{ $remote_desktop->server->domain }}</td>
                        <td>{{ $remote_desktop->server->price }}/m
                        </td>

                        <td>
                            @if ($remote_desktop->status == 'active')
                                <a href="#"
                                    onclick="if (confirm('删除后，该用户所有数据都会丢失并且无法找回！')) { $('#f-{{ $i }}').submit() }">删除</a>
                                <form id="f-{{ $i }}" method="post"
                                    action="{{ route('remote_desktop.destroy', $remote_desktop->id) }}">@csrf
                                    @method('DELETE')</form>
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
