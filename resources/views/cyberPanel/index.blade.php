@extends('layouts.app')

@section('title', 'CyberPanel 虚拟主机')

@section('content')
    <div class="mdui-typo-display-2">CyberPanel 虚拟主机</div>

    <a href="{{ route('cyberPanel.create') }}" class="mdui-btn mdui-color-theme-accent mdui-ripple">新建 CyberPanel 虚拟主机</a>

    <div class="mdui-table-fluid mdui-m-t-2">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>域名</th>
                    <th>磁盘大小</th>
                    <th>数据库数量</th>
                    <th>服务器</th>
                    <th>网络限制</th>
                    <th>套餐</th>
                    <th>积分/分钟</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody class="mdui-typo">
                @php($i = 1)
                @php($project_id = 0)
                @foreach ($cyberPanelSites as $cp)
                    @if ($cp->project->id != $project_id)
                        @php($project_id = $cp->project->id)
                        <tr>
                            <td colspan="12" class="mdui-text-center">
                                <a href="{{ route('projects.show', $cp->project->id) }}">{{ $cp->project->name }}</a>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td nowrap>{{ $i++ }}</td>
                        <td nowrap><a href="{{ route('cyberPanel.edit', $cp->id) }}">{{ $cp->name }}</a></td>
                        <td nowrap>{{ $cp->domain }}</td>

                        <td nowrap>{{ $cp->package->disks }} M</td>
                        <td nowrap>{{ $cp->package->databases }} 个</td>
                        <td nowrap>{{ $cp->package->server->name }}</td>
                        <td nowrap>{{ $cp->package->server->network_limit }} Mbps</td>
                        <td nowrap>{{ $cp->package->name }}</td>
                        <td nowrap>{{ $cp->package->server->price + $cp->package->price }}</td>

                        <td nowrap>
                            <a href="#"
                                onclick="if (confirm('删除后，这个站点的数据将会全部丢失，并且网站将无法访问。')) { $('#f-{{ $i }}').submit() }">删除</a>
                            | <a onclick="$('#f-login-{{ $i }}').submit()">进入</a>
                        </td>

                    </tr>
                    <form action="{{ $cp->package->server->domain }}/api/loginAPI" method="post" target="_blank"
                        id="f-login-{{ $i }}">
                        <input type="hidden" name="username" value="{{ $cp->owner }}" />
                        <input type="hidden" name="password" value="{{ $cp->password }}" />
                    </form>

                    <form id="f-{{ $i }}" method="post" action="{{ route('cyberPanel.destroy', $cp->id) }}">
                        @csrf
                        @method('DELETE')</form>

                @endforeach
                @if ($i > 10)
                    <tr>
                        <td colspan="12" class="mdui-text-center">
                            <a href="{{ route('cyberPanel.create') }}">咕</a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
