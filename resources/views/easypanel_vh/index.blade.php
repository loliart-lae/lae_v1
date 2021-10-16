@extends('layouts.app')

@section('title', 'EasyPanel 虚拟主机')

@section('content')
    <div class="mdui-typo-display-2">EasyPanel 虚拟主机</div>

    <a href="{{ route('easyPanel.create') }}" class="mdui-btn mdui-color-theme-accent mdui-ripple">新建 EasyPanel 虚拟主机</a>

    <div class="mdui-table-fluid mdui-m-t-2">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>内部 ID</th>
                    <th>名称</th>
                    <th>空间容量</th>
                    <th>数据库容量</th>
                    <th>服务器</th>
                    <th>网络限制</th>
                    <th>积分/分钟</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody class="mdui-typo">
                @php($i = 1)
                @php($project_id = 0)
                @foreach ($eps as $ep)
                    @if ($ep->project->id != $project_id)
                        @php($project_id = $ep->project->id)
                        <tr>
                            <td colspan="12" class="mdui-text-center">
                                <a href="{{ route('projects.show', $ep->project->id) }}">{{ $ep->project->name }}</a>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td nowrap="nowrap">{{ $i++ }}</td>
                        <td nowrap="nowrap">{{ $ep->id }}</td>
                        <td nowrap="nowrap">{{ $ep->name }}</td>

                        <td nowrap="nowrap">{{ $ep->template->web_quota }} M</td>
                        <td nowrap="nowrap">{{ $ep->template->db_quota }} M</td>
                        <td nowrap="nowrap">{{ $ep->server->name }}</td>
                        <td nowrap="nowrap">{{ $ep->server->network_limit }} Mbps</td>
                        <td nowrap="nowrap">{{ $ep->server->price + $ep->template->price }}</td>

                        <td nowrap="nowrap">
                            @if ($ep->status == 'active')
                                <a href="#"
                                    onclick="if (confirm('删除后，这个站点的数据将会全部丢失，并且网站将无法访问。')) { $('#f-{{ $i }}').submit() }">删除</a>|<a
                                    href="#" onclick="$('#f-pwd-{{ $i }}').submit()">重置密码</a>|<a href="#"
                                    onclick="$('#f-login-{{ $i }}').submit()">进入</a>
                            @elseif ($ep->status == 'pending')
                                <div class="mdui-progress">
                                    <div class="mdui-progress-indeterminate"></div>
                                </div>
                            @elseif ($ep->status == 'backup')
                                正在备份
                            @else
                                {{ $ep->status }}
                            @endif
                        </td>

                    </tr>
                    <form id="f-{{ $i }}" method="post" action="{{ route('easyPanel.destroy', $ep->id) }}">
                        @csrf
                        @method('DELETE')</form>
                    <form id="f-pwd-{{ $i }}" method="post"
                        action="{{ route('easyPanel.update', $ep->id) }}">@csrf
                        @method('PUT')</form>

                    <form id="f-login-{{ $i }}"
                        action="http://{{ $ep->server->domain }}/vhost/index.php?c=session&a=login" method="post"
                        target="_blank">
                        <input type="hidden" name="username" value="{{ $ep->username }}" />
                        <input type="hidden" name="passwd" value="{{ $ep->password }}" />
                    </form>

                @endforeach
                @if ($i > 10)
                    <tr>
                        <td colspan="12" class="mdui-text-center">
                            <a href="{{ route('easyPanel.create') }}">这个其实也可以搞Hexo之类</a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>


@endsection
