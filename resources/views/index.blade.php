@extends('layouts.app')

@section('title', '首页')

@section('content')
    {{-- <div class="mdui-typo-display-2">嘿，你好</div><br>
    <div class="mdui-typo-headline-opacity">借助 LAE 轻量应用引擎 在互联网中快速开辟一席之地</div> --}}
    @guest

        <div class="mdui-typo-display-2">Light App Engine</div>
        <div class="mdui-typo-headline-opacity mdui-p-t-2">轻量应用引擎</div>

        <div class="mdui-typo mdui-p-t-4">


            {{-- <h2 class="mdui-text-color-theme">2021.10.3 01:48 附：LAE 仍需要改进</h2>
        <p>Light App Engine 仍需要改进。</p>
        <p>非常感谢用户宣传LAE。经过这次稍小流量的冲击，我们发现了自身的问题。</p>
        <p>有流量总是好的，不过我们的Agent程序都是自研的，没有足够的经验，没有对突发情况的处理，也没有足够的安全性措施。</p>
        <p>这次LAE意识到，快速更新功能还并不可取，追求稳定才是首位。</p>
        <p>LAE 将会继续听从用户社区的意见。为用户提供更好的服务，以稳定安全作为首要目标。</p>
        <p>非常感谢大家。</p>
        <br /><br /> --}}
            <p><mark>温馨提醒 ①：请不要使用不得当的方式宣传 LAE</mark></p>
            <p><mark>温馨提醒 ②：平台积分与 RMB 兑换汇率为 100:1，请在预估价格时候注意辨识</mark></p>

            <h2 class="mdui-text-color-theme">嗯？这是什么！</h2>
            <p>往简单了说，你可以在这里充值积分。创建自己的按时计费的容器，你可以在容器中做你想做的事情，前提是不违规。在你不想使用或者没有足够的余额的时候，我们会收回它。这也意味着，你得准备好充足的积分，确保你的容器能够稳定持久的运行。
            </p>
            <p>iVampireSP: 啊哈，这里不止有容器啦，还有“共享的 Windows”，“穿透隧道”等。Light App Engine 不是我一人打造的，而是整个社区一同打造的。</p>

            <h2 class="mdui-text-color-theme">我应该如何来创建 Linux 容器？</h2>
            <p>让我们来走一下创建容器的流程：</p>
            <ol>
                <li>创建/进入<abbr title="位于顶部的 “项目管理”">项目</abbr></li>
                <li>确保<abbr title="位于顶部的 “项目管理”">项目</abbr>有足够的<abbr title="如果余额不足，需要往项目中充值">余额</abbr></li>
                <li>在 <kbd> Linux 容器</kbd> 中新建容器</li>
            </ol>

            <h2 class="mdui-text-color-theme">什么是 穿透隧道？</h2>
            <p>穿透隧道 使用的是 Frp ，我们提供了配置文件生成，你无需编写一行配置文件，直接复制粘贴即可，很方便就能完成端口映射。</p>

            <h2 class="mdui-text-color-theme">什么是 共享的 Windows?</h2>
            <p>共享的 Windows 是一个使用远程桌面的功能，不具备管理员权限，但是你依旧可以拿他干一些需要的事情。</p>

            <h2 class="mdui-text-color-theme">什么是 文档？</h2>
            <p>文档 是 Light App Engine 偏向于社区化的一个功能，我们鼓励用户在这里撰写文档。<br />
                在编写/阅读文档时，你还可以进行SSH。每当你撰写一份文档并受到别人认同时，账号积分会 +1。</p>

            <p><em>大部分操作都需要 创建项目(创建项目时完全免费)，并且项目中有足够的余额</em></p>



            <h1 class="mdui-text-color-theme">如何充值？</h1>
            <p>目前的充值流程是这样的：</p>

            <ol>
                <li>点击顶栏 “用户名 / ***”</li>
                <li>点击积分后的 “充值”</li>
                <li>输入金额</li>
                <li>扫码充值</li>
            </ol>

            <h1 class="mdui-text-color-theme"><mark>如何创建我的实例？</mark></h1>
            <p>要创建实例，首先你需要<b>确保</b>：</p>

            <ol>
                <li><mark>你已经创建了项目</mark></li>
                <li><mark>项目中有足够的积分余额</mark></li>
            </ol>

            <p>确保这些条件之后，你可以按照这个流程操作（此处以应用容器为例）：</p>

            <ol>
                <li>点击 “应用容器”</li>
                <li>点击 “新建应用容器”</li>
                <li>填写基本信息，然后点击 “创建”</li>
                <li>稍等片刻后，您的容器就已经准备完成。</li>
            </ol>

            <p>到这里，你就可以使用容器啦~</p>

            <h1 class="mdui-text-color-theme">什么是 端口转发？我又如何访问我的容器？</h1>

            <p>端口转发可以将内网端口转发到公网中。不同服务器之间内网无法互相访问。同服务器中，您可以借助一个容器访问另一个容器。</p>

            <p>常见转发：SSH，内部端口 <kbd>22</kbd>，外部端口 “自定义”</p>

            <p>转发时，请注意：外部端口必须大于 <kbd>1025</kbd></p>

            <h2 class="mdui-text-color-theme">什么是 穿透隧道？</h2>
            <p>穿透隧道 使用的是 Frp ，我们提供了配置文件生成，你无需编写一行配置文件，直接复制粘贴即可，很方便就能完成端口映射。</p>

            <h2 class="mdui-text-color-theme">什么是 共享的 Windows?</h2>
            <p>共享的 Windows 是一个使用远程桌面的功能，不具备管理员权限，但是你依旧可以拿他干一些需要的事情。</p>

            <h2 class="mdui-text-color-theme">什么是 文档？</h2>
            <p>文档 是 Light App Engine 偏向于社区化的一个功能，我们鼓励用户在这里撰写文档。<br />
                在编写/阅读文档时，你还可以进行 SSH。每当你撰写一份文档并受到别人认同时，账号积分会 +1。</p>

            <h1 class="mdui-text-color-theme">我不可以使用 Light App Engine 的服务做什么？</h1>

            <ul>
                <li>肉鸡，扫描，等危害网络安全等行为。</li>
                <li>任何违反服务器所在 “地区/国家” 法律行为。</li>
                <li>长时间高强度占用资源。</li>
            </ul>

            <p>Light App Engine 也为除了您以外的 {{ App\Models\User::count() - 1 }} 个用户提供服务，期待您的成果💗</p>
            <p>如果你有想法想对我说，欢迎发送邮件至 <a href="mailto:im@ivampiresp.com">im@ivampiresp.com</a>，我会认真阅读每一份邮件！</p>

        @else
            <div class="mdui-text-center mdui-typo-caption-opacity mdui-text-center">Powered by Open App Engine</div>
            <a id="goto-main" style="display: none" href="{{ route('main') }}"></a>
            <script>
                mdui.mutation()
                $(document).ready(function() {
                    setTimeout(function() {
                        $('#goto-main').click()
                    }, 500)
                })
            </script>
        @endguest
    </div>

@endsection
