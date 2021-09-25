@extends('layouts.app')

@section('title', '首页')

@section('content')
    <div class="mdui-typo-display-2">嘿，你好</div><br>
    <div class="mdui-typo-headline-opacity">借助 LAE 轻量应用引擎 在互联网中快速开辟一席之地</div>

    {{-- <div class="mdui-typo-display-2">中秋节快乐</div><br>
<div class="mdui-typo-headline-opacity">假期宝贵，准备好用Light App Engine与您的朋友们来一场盛宴了吗？</div> --}}


    <div class="mdui-typo">
        {{-- <h3>世界与你同在</h3>
    <h2 class="mdui-text-color-theme">方便部署 Minecraft 服务器</h2>
    <iframe src="//player.bilibili.com/player.html?aid=208100618&bvid=BV1Ch411p7CH&cid=411531988&page=1" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"> </iframe>
    <br />
    <a target="_blank" href="https://www.bilibili.com/video/BV1Ch411p7CH">前往 Bilibili 播放</a>
    <br /><br />
    <h3>与好朋友们玩得开心</h3>
    <h2 class="mdui-text-color-theme">搭建一个 Terraria 服务器</h2>
    <a href="#" onclick="window.open('https://lightart.top/documents/8')">阅览文档</a> --}}

        @guest
            <h2 class="mdui-text-color-theme">嗯？这是什么！</h2>
            <p>往简单了说，你可以在这里充值积分。创建自己的按时计费的容器，你可以在容器中做你想做的事情，前提是不违规。在你不想使用或者没有足够的余额的时候，我们会收回它。这也意味着，你得准备好充足的积分，确保你的容器能够稳定持久的运行。
            </p>

            <h2 class="mdui-text-color-theme">我应该如何来创建容器？</h2>
            <p>让我们来走一下创建容器的流程：</p>
            <ol>
                <li>创建/进入<abbr title="位于顶部的 “项目管理”">项目</abbr></li>
                <li>确保<abbr title="位于顶部的 “项目管理”">项目</abbr>有足够的<abbr title="如果余额不足，需要往项目中充值">余额</abbr></li>
                <li>在 <kbd>容器管理</kbd> 中新建容器</li>
            </ol>
            <p><em>创建容器必须要有项目，并且项目中有足够的余额</em></p>



            <p>我们正在为 {{ App\Models\User::count() - 1 }} 个用户提供服务，并且时刻欢迎您的加入！</p>
            <p>如果你有想法想对我说，欢迎发送邮件至<a href="mailto:im@ivampiresp.com">im@ivampiresp.com</a>，我会认真阅读每一份邮件！</p>
        @else
            <h1 class="mdui-text-color-theme">我们是？</h1>
            <p>嘿，这里是 <b>Light App Engine</b>，简称 <b>LAE</b>。</p>

            <h1 class="mdui-text-color-theme">在这个平台可以做什么？</h1>
            <p>你可以租赁容器，进行测试。或者利用容器组建一个集群，还可以使用单一容器开一个 Minecraft 服务器之类的。</p>

            <h1 class="mdui-text-color-theme">如何充值？</h1>
            <p>目前的充值流程是这样的：</p>

            <ol>
                <li>点击顶栏 “剩余积分”</li>
                <li>输入金额</li>
                <li>扫码充值</li>
            </ol>

            <h1 class="mdui-text-color-theme"><mark>如何创建我的实例？</mark></h1>
            <p>要创建实例，首先你需要<b>确保</b>：</p>

            <ol>
                <li><mark>你已经创建了项目</mark></li>
                <li><mark>项目中有足够的积分余额</mark></li>
            </ol>

            <p>确保这些条件之后，你可以按照这个流程操作：</p>

            <ol>
                <li>点击 “容器管理”</li>
                <li>点击 “新建Linux容器”</li>
                <li>填写基本信息，然后点击 “创建”</li>
                <li>稍等片刻后，您的容器就已经准备完成。</li>
            </ol>

            <p>到这里，你就可以使用容器啦~</p>

            <h1 class="mdui-text-color-theme">什么是端口转发？我又如何访问我的容器？</h1>

            <p>端口转发可以将内网端口转发到公网中。不同服务器之间内网无法互相访问。同服务器中，您可以借助一个容器访问另一个容器。</p>

            <p>常见转发：SSH，内部端口 <kbd>22</kbd>，外部端口 “自定义”
            <p>

            <p>转发时，请注意：外部端口必须大于 <kbd>1025</kbd></p>

            <h1 class="mdui-text-color-theme">我不可以使用 Light App Engine 的服务做什么？</h1>

            <ul>
                <li>肉鸡，扫描，等危害网络安全等行为。</li>
                <li>任何违反服务器所在 “地区/国家” 法律行为。</li>
                <li>长时间高强度占用资源。</li>
            </ul>

            <p>Light App Engine 也为除了您以外的 {{ App\Models\User::count() - 2 }} 个用户提供服务，期待您的成果💗</p>
            <p>如果你有想法想对我说，欢迎发送邮件至<a href="mailto:im@ivampiresp.com">im@ivampiresp.com</a>，我会认真阅读每一份邮件！</p>
        @endguest
    </div>

@endsection
