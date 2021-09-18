@extends('layouts.app')

@section('title', '仪表盘')

@section('content')

<div class="mdui-typo">
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

    <p>常见转发：SSH，内部端口 <kbd>22</kbd>，外部端口 “自定义”<p>

    <p>转发时，请注意：外部端口必须大于 <kbd>1025</kbd></p>

    <h1 class="mdui-text-color-theme">我不可以使用 Light App Engine 的服务做什么？</h1>

    <ul>
        <li>肉鸡，扫描，等危害网络安全等行为。</li>
        <li>任何违反服务器所在 “地区/国家” 法律行为。</li>
        <li>长时间高强度占用资源。</li>
    </ul>

    <p>Light App Engine 也为除了您以外的 {{ App\Models\User::count() - 2 }} 个用户提供服务，期待您的成果💗</p>
</div>



@endsection
