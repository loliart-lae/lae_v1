@extends('layouts.app')

@section('title', '快捷访问')

@section('content')
    <div class="mdui-typo-display-2">快捷访问</div>
    <p>快捷访问可以设置访问网址时的返回内容，可以跳转网页，自定义内容等。</p>
    <br />

    <button class="mdui-btn mdui-color-theme-accent mdui-ripple" mdui-dialog="{target: '#new_dialog'}">新建入口</button>

    <div id="new_dialog" class="mdui-dialog">
        <div class="mdui-dialog-title">新建访问入口</div>
        <div class="mdui-dialog-content">选择执行的容器
            <ol>
                <li>使用指定的容器<br />选择项目中的容器，然后根据策略在该容器中执行命令。</li>
                <li>自动创建容器<br />自动创建一个新的容器，然后根据策略在该容器中执行命令。你可以在命令行中配置当命令执行完成时是否进行销毁。<br />当创建容器失败时，任务将不会执行。</li>
            </ol>
        </div>
        <div class="mdui-dialog-actions">
            <button class="mdui-btn mdui-ripple" mdui-dialog-close>取消</button>
            <button class="mdui-btn mdui-ripple" mdui-dialog-confirm>使用指定的容器</button>
            <button class="mdui-btn mdui-ripple" mdui-dialog-confirm>自动创建容器并执行</button>
        </div>
    </div>

@endsection
