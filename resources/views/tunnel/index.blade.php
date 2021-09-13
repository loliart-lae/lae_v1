@extends('layouts.app')

@section('title', '穿透隧道')

@section('content')
    <h1 class="mdui-text-color-theme">内网穿透隧道管理</h1>

        <a target="_blank" href="https://security.nwl.im/frp/0.37.1/"
            class="mdui-btn mdui-color-theme-accent mdui-ripple">下载Frp 各版本客户端</a>

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
                    <th>共享带宽配额</th>
                    <th>属于的服务器</th>
                    <th>属于项目</th>
                    <th>总价格</th>
                    <th>当前可选择的操作</th>
                </tr>
            </thead>
            <tbody class="mdui-typo">
                <tr>
                    <td colspan="11" class="mdui-text-center">
                        <a href="{{ route('tunnels.create') }}">新建 隧道</a>
                        {{-- 或者 <a href="{{ route('tunnels.create') }}">新建 启动集</a> --}}
                    </td>
                </tr>
                @php($i = 1)
                @foreach ($tunnels as $tunnel)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $tunnel->id }}</td>
                        <td>{{ $tunnel->name }}</td>
                        <td>{{ $tunnel->protocol }}</td>
                        <td>{{ $tunnel->local_address }}</td>
                        <td>
                            @switch($tunnel->protocol)
                                @case('http')
                                    {{ $tunnel->custom_domain }}
                                @break
                                @case('https')
                                    {{ $tunnel->custom_domain }}
                                @break
                                @default
                                    {{ $tunnel->server->address }}:{{ $tunnel->remote_port }}

                            @endswitch

                        </td>
                        <td>{{ $tunnel->server->network_limit }} Mbps</td>
                        <td>{{ $tunnel->server->name }}</td>
                        <td><a
                                href="{{ route('projects.show', $tunnel->project->id) }}">{{ $tunnel->project->name }}</a>
                        </td>
                        <td>{{ $tunnel->server->price }}/m
                        </td>

                        <td><a href="#" onclick="window.open('{{ route('tunnels.show', $tunnel->id) }}')">配置文件</a> |
                            <a href="#"
                                onclick="if (confirm('删除后，该隧道将无法再次启动，并且还有可能面临端口被占用的风险。')) { $('#f-{{ $i }}').submit() }">删除</a>
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
