@extends('layouts.app')

@section('title', '首页')

@section('content')
{{-- <div class="mdui-typo-display-2">嘿，你好</div><br>
<div class="mdui-typo-headline-opacity">借助 LAE 轻量应用引擎 在互联网中快速开辟一席之地</div> --}}

<div class="mdui-typo-display-2">中秋节快乐</div><br>
<div class="mdui-typo-headline-opacity">假期宝贵，准备好用Light App Engine与您的朋友们来一场盛宴了吗？</div>


<div class="mdui-typo">
    <h3>世界与你同在</h3>
    <h2 class="mdui-text-color-theme">方便部署 Minecraft 服务器</h2>
    <iframe src="//player.bilibili.com/player.html?aid=208100618&bvid=BV1Ch411p7CH&cid=411531988&page=1" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"> </iframe>
    <br />
    <a target="_blank" href="https://www.bilibili.com/video/BV1Ch411p7CH">前往 Bilibili 播放</a>
    <br /><br />
    <h3>与好朋友们玩得开心</h3>
    <h2 class="mdui-text-color-theme">搭建一个 Terraria 服务器</h2>
    <a href="#" onclick="window.open('https://lightart.top/documents/8')">阅览文档</a>


    {{-- <h2 class="mdui-text-color-theme">嗯？这是什么！</h2>
    <p>往简单了说，你可以在这里充值积分。创建自己的按时计费的容器，你可以在容器中做你想做的事情，前提是不违规。在你不想使用或者没有足够的余额的时候，我们会收回它。这也意味着，你得准备好充足的积分，确保你的容器能够稳定持久的运行。</p>

    <h2 class="mdui-text-color-theme">我应该如何来创建容器？</h2>
    <p>让我们来走一下创建容器的流程：</p>
    <ol>
        <li>创建/进入<abbr title="位于顶部的 “项目管理”">项目</abbr></li>
        <li>确保<abbr title="位于顶部的 “项目管理”">项目</abbr>有足够的<abbr title="如果余额不足，需要往项目中充值">余额</abbr></li>
        <li>在 <kbd>容器管理</kbd> 中新建容器</li>
    </ol>
    <p><em>创建容器必须要有项目，并且项目中有足够的余额</em></p> --}}



    <p>我们正在为 {{ App\Models\User::count() - 1 }} 个用户提供服务，并且时刻欢迎您的加入！</p>
    <p>如果你有想法想对我说，欢迎发送邮件至<a href="mailto:im@ivampiresp.com">im@ivampiresp.com</a>，我会认真阅读每一份邮件！</p>
</div>

@endsection
