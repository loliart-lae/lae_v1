@extends('layouts.app')

@section('title', '为什么选择 LAE')

@section('content')

<div class="mdui-typo">
    <div class="mdui-table-fluid">
        <div class="mdui-table">
            <thead>
                <tr>
                    <th></th>
                    <th>Linux 容器</th>
                    <th>共享 Windows 远程桌面</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>按分钟计费</td>
                    <td class="is-visible">支持</td>
                    <td class="is-visible">支持</td>
                </tr>
                <tr>
                    <td>随时修改配置</td>
                    <td class="is-visible">支持</td>
                    <td class="is-hidden">不支持</td>
                </tr>
            </tbody>
        </div>
    </div>
</div>

@endsection
