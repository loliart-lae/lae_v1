@extends('layouts.app')

@section('title', '为什么选择 LAE')

@section('content')

<div class="mdui-typo">
    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th> </th>
                    <th>穿透隧道</th>
                    <th>Linux 容器</th>
                    <th>共享 Windows 远程桌面</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>按分钟计费</td>
                    <td class="is-visible">支持</td>
                    <td class="is-visible">支持</td>
                    <td class="is-visible">支持</td>
                </tr>
                <tr>
                    <td>随时修改配置</td>
                    <td class="is-hidden">不支持</td>
                    <td class="is-visible">支持</td>
                    <td class="is-hidden">不支持</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
