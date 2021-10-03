@extends('layouts.app')

@section('title', '穿透隧道')

@section('content')
    <div class="mdui-typo-display-2">穿透隧道</div>

    <a href="{{ route('tunnels.create') }}" class="mdui-btn mdui-color-theme-accent mdui-ripple">新建隧道</a>
    &nbsp;&nbsp;
    <a target="_blank" href="https://security.nwl.im/frp/0.37.1/" class="mdui-btn mdui-color-theme-accent mdui-ripple">下载 Frp
        各平台客户端</a>

    {{-- <a href="" class="mdui-btn mdui-color-theme-accent mdui-ripple">启动集</a> --}}
    <br /><br />
    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>内部 ID</th>
                    <th>名称</th>
                    <th>协议</th>
                    <th>内部地址</th>
                    <th>外部地址</th>
                    <th>CNAME</th>
                    <th>共享带宽</th>
                    <th>属于服务器</th>
                    <th>积分/分钟</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody class="mdui-typo">
                @php($i = 1)
                @php($project_id = 0)
                @foreach ($tunnels as $tunnel)
                    @if ($tunnel->project->id != $project_id)
                        @php($project_id = $tunnel->project->id)
                        <tr>
                            <td colspan="11" class="mdui-text-center">
                                <a
                                    href="{{ route('projects.show', $tunnel->project->id) }}">{{ $tunnel->project->name }}</a>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td nowrap="nowrap">{{ $i++ }}</td>
                        <td nowrap="nowrap">{{ $tunnel->id }}</td>
                        <td nowrap="nowrap">{{ $tunnel->name }}</td>
                        <td nowrap="nowrap">{{ strtoupper($tunnel->protocol) }}</td>
                        <td nowrap="nowrap">{{ $tunnel->local_address }}</td>
                        <td nowrap="nowrap">
                            @switch($tunnel->protocol)
                                @case('http')
                                    {{ $tunnel->custom_domain }}
                                @break
                                @case('https')
                                    {{ $tunnel->custom_domain }}
                                @break
                                @case('xtcp')
                                    <a href="#" onclick="window.open('{{ route('tunnels.show', $tunnel->id) }}')">对端</a>
                                @break
                                @default
                                    {{ $tunnel->server->address }}:{{ $tunnel->remote_port }}

                            @endswitch

                        </td>
                        <td nowrap="nowrap">{{ $tunnel->server->address }}</td>
                        <td nowrap="nowrap">
                            @if ($tunnel->protocol != 'xtcp')
                                {{ $tunnel->server->network_limit }} Mbps
                            @else
                                取决于客户机
                            @endif
                        </td>
                        <td nowrap="nowrap">{{ $tunnel->server->name }}</td>
                        <td nowrap="nowrap">
                            @if ($tunnel->protocol != 'xtcp')
                                {{ $tunnel->server->price }}/m
                            @else
                                免费
                            @endif
                        </td>

                        @if ($tunnel->protocol == 'xtcp')
                            @php($tip = '删除后，对端也将无法访问。')
                        @else
                            @php($tip = ' 删除后，该隧道将无法再次启动，并且还有可能面临端口被占用的风险。')
                        @endif
                        <td nowrap="nowrap"><a href="#"
                                onclick="window.open('{{ route('tunnels.show', $tunnel->id) }}')">配置文件</a> |
                            <a href="#"
                                onclick="if (confirm('{{ $tip }}')) { $('#f-{{ $i }}').submit() }">删除</a>
                            <form id="f-{{ $i }}" method="post"
                                action="{{ route('tunnels.destroy', $tunnel->id) }}">
                                @csrf
                                @method('DELETE')</form>
                        </td>

                    </tr>
                @endforeach
                @if ($i > 10)
                    <tr>
                        <td colspan="11" class="mdui-text-center">
                            <a href="{{ route('tunnels.create') }}">Create A Frp Tunnel Please (miao~)</a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>


@endsection
