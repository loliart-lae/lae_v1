@extends('layouts.app')

@section('title', '端口转发')

@section('content')
    <h1 class="mdui-text-color-theme">端口转发</h1>
    <p>服务器 {{ $server_name }} 的转发积分: {{ $forward_price }}/条</p>
    <br />

    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>内部 ID</th>
                    <th>容器内端口</th>
                    <th>输出端口</th>
                    <th>协议</th>
                    <th>原因</th>
                    <th>外部连接</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody class="mdui-typo">
                @php($i = 1)
                <tr>
                    <td colspan="8" class="mdui-text-center">
                        <a href="{{ route('forward.create', Request::route('lxd_id')) }}">新建端口转发</a>
                    </td>
                </tr>
                @foreach ($forwards as $forward)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $forward->id }}</td>
                        <td>{{ $forward->from }}</td>
                        <td>{{ $forward->to }}</td>
                        <td>TCP&UDP</td>
                        <td>{{ $forward->reason }}</td>
                        <td>{{ $forward->server->address }}:{{ $forward->to }}</td>
                        <td>
                            @if ($forward->status == 'active')
                                <a href="#" onclick="$('#f-{{ $i }}').submit()">删除</a>
                                <form id="f-{{ $i }}" method="post"
                                    action="{{ route('forward.destroy', [Request::route('lxd_id'), $forward->id]) }}">@csrf @method('DELETE')</form>
                            @else
                                {{ $forward->status }}
                            @endif
                        </td>

                    </tr>
                @endforeach
                @if ($i > 10)
                    <tr>
                        <td colspan="7" class="mdui-text-center">
                            <a href="{{ route('forward.create', Request::route('lxd_id')) }}">新建端口转发</a>
                        </td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>


@endsection
