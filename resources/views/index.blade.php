@extends('layouts.app')

@section('title', '首页')

@section('content')
<div class="mdui-typo-display-1">嘿，你好</div><br>
<div class="mdui-typo-headline">借助 LAE 轻量应用引擎 在互联网中快速开辟一席之地</div>

<div class="mdui-typo">
    <h2 class="mdui-text-color-theme">嗯？这是什么！</h2>
    <p>往简单了说，你可以在这里充值积分。创建自己的按时计费的容器，你可以在容器中做你想做的事情，前提是不违规。在你不想使用或者没有足够的余额的时候，我们会收回它。这也意味着，你得准备好充足的积分，确保你的容器能够稳定持久的运行。</p>

    <h2 class="mdui-text-color-theme">我应该如何来创建容器？</h2>
    <p>让我们来走一下创建容器的流程：</p>
    <ol>
        <li>创建/进入<abbr title="位于顶部的 “项目管理”">项目</abbr></li>
        <li>确保<abbr title="位于顶部的 “项目管理”">项目</abbr>有足够的<abbr title="如果余额不足，需要往项目中充值">余额</abbr></li>
        <li>在 <kbd>容器管理</kbd> 中新建容器</li>
    </ol>
    <p><em>创建容器必须要有项目，并且项目中有足够的余额</em></p>
</div>

@endsection
