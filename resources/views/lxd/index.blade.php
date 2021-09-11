@extends('layouts.app')

@section('title', '容器')

@section('content')
    <h1 class="mdui-text-color-theme">容器管理</h1>

    <div class="mdui-table-fluid mdui-typo">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>内部 ID</th>
                    <th width="3%">名称</th>
                    <th>CPU</th>
                    <th>内存</th>
                    <th>硬盘</th>
                    <th>内部 IP</th>
                    <th>带宽限制</th>
                    <th>模板</th>
                    <th>属于服务器</th>
                    <th>属于项目</th>
                    <th>端口转发</th>
                    <th>总价格</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="14" class="mdui-text-center">
                        <a href="{{ route('lxd.create') }}">新建 Linux 容器</a>
                    </td>
                </tr>
                @php($i = 1)
                @foreach ($lxdContainers as $lxd)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $lxd->id }}</td>
                        <td>{{ $lxd->name }}</td>
                        <td>{{ $lxd->template->cpu }} Core</td>
                        <td>{{ $lxd->template->mem }}M</td>
                        <td>{{ $lxd->template->disk }} G</td>
                        <td>{{ $lxd->lan_ip }}</td>
                        <td>{{ $lxd->server->network_limit }} Mbps</td>
                        <td>
                            @if ($lxd->status == 'running')
                                <a href="{{ route('lxd.edit', $lxd->id) }}">{{ $lxd->template->name }}</a>
                            @else
                                {{ $lxd->template->name }}
                            @endif
                        </td>
                        <td>{{ $lxd->server->name }}</td>
                        <td><a href="{{ route('projects.show', $lxd->project->id) }}">{{ $lxd->project->name }}</a>
                        </td>
                        @php($forwards = count($lxd->forward))
                        <td>
                            @if ($lxd->status == 'running')
                                <a href="{{ route('forward.index', $lxd->id) }}">{{ $forwards }} 端口</a>
                            @else
                                正在调度
                            @endif


                        </td>
                        <td>{{ $lxd->server->price + $lxd->template->price + $forwards * $lxd->server->forward_price }}/m
                        </td>

                        <td>
                            @if ($lxd->status == 'running')
                                <a href="#" onclick="$('#f-{{ $i }}').submit()">删除</a>
                                <form id="f-{{ $i }}" method="post"
                                    action="{{ route('lxd.destroy', $lxd->id) }}">@csrf @method('DELETE')</form>
                            @else
                                {{ $lxd->status }}
                            @endif
                        </td>

                    </tr>
                @endforeach
                @if ($i > 10)
                    <tr>
                        <td colspan="12" class="mdui-text-center">
                            <a href="{{ route('lxd.create') }}">来 1 份容器，谢谢</a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>


@endsection
