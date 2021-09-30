@extends('layouts.app')

@section('title', '为什么选择 LAE')

@section('content')

<div class="mdui-typo">
    <div class="mdui-typo-display-2">优雅，永不过时</div>
    <div class="mdui-typo-headline-opacity animate__animated animate__hinge">或许这不是个好选择</div>

    <div class="mdui-typo">
        <h1>Light App Engine</h1>
        <p>一个非常轻量的云计算，但是他也不只是一个云计算。</p>
        <p>他也更像一个社区，什么都交谈。</p>
        <p>然后我也不知道该怎么说，只想让 Light App Engine 用有属于他自己的样子，而并非像大厂一样。</p>
    </div>


    {{-- <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>穿透隧道</th>
                    <th>Linux 容器</th>
                    <th>共享 Windows 远程桌面</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>按分钟计费</td>
                    <td class="is-hidden">不属于</td>
                    <td class="is-visible">属于</td>
                    <td class="is-visible">属于</td>
                </tr>
                <tr>
                    <td>随时修改配置</td>
                    <td class="is-hidden">不支持</td>
                    <td class="is-visible">支持</td>
                    <td class="is-hidden">不支持</td>
                </tr>
                <tr>
                    <td>可选择镜像</td>
                    <td></td>
                    <td class="is-visible">支持</td>
                    <td class="is-hidden">不支持</td>
                </tr>
                <tr>
                    <td>计费标准</td>
                    <td>每条 0.01 积分</td>
                    <td>每分钟 0.012 积分左右</td>
                    <td>每分钟 0.0012 积分</td>
                </tr>
            </tbody>
        </table>
    </div> --}}
</div>

<style>
    /* 响应式工具表格中的样式 */
    .is-visible {
      background-color: #E8EAF6 !important;
      color: #3F51B5 !important;
      text-align: center;
    }
    .is-hidden {
      background-color: transparent !important;
      color: #ccc !important;
      text-align: center;
    }
</style>

@endsection
