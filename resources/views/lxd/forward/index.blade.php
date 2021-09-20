@extends('layouts.app')

@section('title', '端口转发')

@section('content')
    <h1 class="mdui-text-color-theme">端口转发</h1>
    <p>服务器 {{ $server_name }} 的转发积分: {{ $forward_price }}/条</p>
    <br />
    <form method="post" action="{{ route('forward.store', Request::route('lxd_id')) }}">
        <h3 class="mdui-text-color-theme">新建转发</h3>
        <p>端口转发 功能会将您的业务端口暴露至公网，请注意您的业务安全。</p>
        @csrf
        <div class="mdui-row">
            <div class="mdui-col-xs-3">
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">容器内端口</label>
                    <input class="mdui-textfield-input" type="number" name="from" value="{{ old('from') }}" />
                </div>
            </div>
            <div class="mdui-col-xs-3">
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">输出端口</label>
                    <input class="mdui-textfield-input" type="number" name="to" value="{{ old('to') }}" />
                </div>
            </div>
            <div class="mdui-col-xs-3">
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">原因</label>
                    <input class="mdui-textfield-input" type="text" name="reason" value="{{ old('reason') }}" />
                </div>
            </div>
            <div class="mdui-col-xs-3">
                <button type="submit" style="margin-top: 34px;"
                    class="mdui-btn mdui-color-theme-accent mdui-ripple">新建</button>
            </div>

        </div>

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
                            <td nowrap="nowrap">{{ $i++ }}</td>
                            <td nowrap="nowrap">{{ $forward->id }}</td>
                            <td nowrap="nowrap">{{ $forward->from }}</td>
                            <td nowrap="nowrap">{{ $forward->to }}</td>
                            <td nowrap="nowrap">TCP&UDP</td>
                            <td nowrap="nowrap">{{ $forward->reason }}</td>
                            <td nowrap="nowrap">{{ $forward->server->address }}:{{ $forward->to }}</td>
                            <td nowrap="nowrap">
                                @if ($forward->status == 'active' || $forward->status == 'failed')
                                    <a href="#" onclick="$('#f-{{ $i }}').submit()">@if ($forward->status == 'failed') 失败 @endif
                                        删除</a>
                                    <form id="f-{{ $i }}" method="post"
                                        action="{{ route('forward.destroy', [Request::route('lxd_id'), $forward->id]) }}">
                                        @csrf @method('DELETE')</form>
                                @elseif ($forward->status == 'pending')
                                    <div class="mdui-progress">
                                        <div class="mdui-progress-indeterminate"></div>
                                    </div>
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
