@extends('layouts.app')

@section('title', '首页')

@section('content')
    {{-- <div class="mdui-typo-display-2">嘿，你好</div><br>
    <div class="mdui-typo-headline-opacity">借助 LAE 轻量应用引擎 在互联网中快速开辟一席之地</div> --}}

    <div class="mdui-typo-display-2">国庆盛宴🎂</div><br />
    <div class="mdui-typo-headline-opacity">庆祝中华人民共和国成立七十二周年</div>
    {{-- <div class="mdui-typo-headline-opacity" style="cursor: pointer" id="owo" onclick="updateOwo()">准备好了吗？要开始了哦～</div>
    <audio id="huluhuluhulu" src="https://ivampiresp.com/wp-content/uploads/2021/09/1632933552-huluhuluhulu.mp3"></audio>
    <script>
        var times = 0

        function updateOwo() {
            times++
            switch (times) {
                case 1:
                    $('#owo').html(`呼噜呼噜呼噜，别再点了啦 (〃￣ω￣〃ゞ`)
                    break

                case 2:
                    document.getElementById('huluhuluhulu').play()
                    $('#owo').html(`哇哇哇哇哇（//▽//） `)
                    break

                case 3:
                    $('#owo').html(`不给你听了 ʕฅ•ω•ฅʔ`)
                    break

                default:
                    @auth
                        $('#owo').html(`这也太....`)
                    @else
                        $('#owo').html(`你都没登录... ʕฅ•ω•ฅʔ`)
                    @endauth

                    break
            }
        }
    </script> --}}

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

        {{-- @guest --}}

        <br />

        {{-- <h2 class="mdui-text-color-theme">嗨嗨！让我们准备宴席吧！</h2> --}}

        {{-- <p>桌椅已经放好，让我们装饰吧！</p> --}}
        <h2 class="mdui-text-color-theme">嗨嗨！与我们一起开始盛宴吧！</h2>

        {{-- <h4 class="mdui-text-color-theme">我不想一个人了啦</h4>
        <p>一个人忙碌的盛宴并非快乐的盛宴。将你的伙伴拉入“项目”中，与他们一起准备您的盛宴。</p> --}}

        {{-- <h4 class="mdui-text-color-theme">现在开始一场游戏盛宴！</h4> --}}
        {{-- <h4>通过 Light App Engine，与您的朋友/基友/闺蜜/死党/等等人一起来一场游戏盛宴！</h4> --}}
        {{-- <p>经过我们的测试，Minecraft Java/Bedrock，Terraria 的服务器都能够在 Light App Engine 上运行，现在开始导入您的世界吧～</p> --}}
        <p>宝贵的假期当然要极致的享受，现在开始免费使用 Light App Engine！</p>
        <p>话不多说，现在注册并登录即可开始享用！</p>


        {{-- <h4 class="mdui-text-color-theme">将自己经过无数夜晚编写的程序交给我们托管！</h4>
        <p>Light App Engine 使用的 LXC
            容器，这意味着大部分程序都可以在上面完美运行，并且无需支付高昂的费用，当你不想使用时，可以直接删除，从而避免不小心续费过多时的资源浪费。不过我们相信你会一直维护着自己的程序，就像自己最想实现的梦想一样❤️</p> --}}

        {{-- <h4 class="mdui-text-color-theme">使用您最喜欢的题材搭建一个网站。</h4>
        <p>Light App Engine 内置了宝塔镜像，这意味着你可以更加方便的管理网站（配合穿透隧道等），期待你的成果！</p>

        <h4 class="mdui-text-color-theme">没有公网？来使用穿透隧道！</h4>
        <p>穿透隧道是Light App Engine推出的实用性功能。通过Frp，可以在你没有公网IP的情况下发布内网应用。</p>

        <h4 class="mdui-text-color-theme">有需要持续运行在 Windows 上的应用程序吗？</h4>
        <p>使用“共享的 Windows”吧，他没有特权，但是能够满足你的大部分应用。</p> --}}

        @auth
            <p>Light App Engine 是一个个人发起的项目，并由社区一同建造，为社区服务。我们不为赚钱，安稳服务社区就好。</p>
            <p>如果喜欢，您能推荐 Light App Engine 给您的朋友吗？这真的对我们来说很重要，非常感谢🙏</p>
            {{-- <br><br><br><br><br>

            <div>
                <p>还记得在童年，我有了第一部智能手机。</p>
                <p>喜欢上了一个叫“Minecraft”的游戏</p>
                <p>认识了和我差不多年龄的主播</p>
                <p>学会了安装模组，学会了导入地图</p>
                <p>还精通了大部分人都仰望的服务器</p>
                <p>可当我再次想起你们</p>
                <p>你们却在何方？</p>
                <p>“我早已放弃了我的世界，感觉他被网易代理之后，变了个样子，就不玩了。”</p>
                <p>“我的世界？我不玩了，《王者荣耀》比我的世界好玩多了”</p>
                <p>“还玩我的世界？该跟上时代了，《和平精英》不好吗？”</p>
                <p>身边的伙伴越来越少，不玩《疼逊游戏》就不能纳入同学的圈子</p>
                <p>群越来越冷清，有些早些认识的群主已不再上线。</p>
                <p>他们可能有了新的开始，而我也并非止步不前。</p>
                <p>童年我喜欢玩 Minecraft，因为她有趣，而现在她就是我的第二段人生</p>
                <p>她没有现实的残酷，没有现实的表里不一，没有现实的......</p>
                <br />
                <p>我已选择创立 Light App Engine，我的未来，没准已不再属于我。</p>
            </div> --}}
        @endauth



        {{-- <h2 class="mdui-text-color-theme">嗯？这是什么！</h2>
            <p>往简单了说，你可以在这里充值积分。创建自己的按时计费的容器，你可以在容器中做你想做的事情，前提是不违规。在你不想使用或者没有足够的余额的时候，我们会收回它。这也意味着，你得准备好充足的积分，确保你的容器能够稳定持久的运行。
            </p>
            <p>iVampireSP: 啊哈，这里不止有容器啦，还有“共享的 Windows”、“穿透隧道”、“Grafana”等。Light App Engine 不是我一人打造的，而是整个社区一同打造的。</p>

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

            <h2 class="mdui-text-color-theme">什么是 Grafana?</h2>
            <p>这是一个还测试的功能。我们为用户提供了一个 Grafana 面板。使用这个功能需要一定的技术含量。这个功能完全免费。</p>



            <p><em>大部分操作都需要 创建项目(创建项目时完全免费)，并且项目中有足够的余额</em></p>

            <p>我们正在为 {{ App\Models\User::count() - 1 }} 个用户提供服务，并且时刻欢迎您的加入！</p>
            <p>如果你有想法想对我说，欢迎发送邮件至<a href="mailto:im@ivampiresp.com">im@ivampiresp.com</a>，我会认真阅读每一份邮件！</p> --}}
        {{-- @else
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

            <h1 class="mdui-text-color-theme">什么是 端口转发？我又如何访问我的容器？</h1>

            <p>端口转发可以将内网端口转发到公网中。不同服务器之间内网无法互相访问。同服务器中，您可以借助一个容器访问另一个容器。</p>

            <p>常见转发：SSH，内部端口 <kbd>22</kbd>，外部端口 “自定义”
            <p>

            <p>转发时，请注意：外部端口必须大于 <kbd>1025</kbd></p>

            <h2 class="mdui-text-color-theme">什么是 穿透隧道？</h2>
            <p>穿透隧道 使用的是 Frp ，我们提供了配置文件生成，你无需编写一行配置文件，直接复制粘贴即可，很方便就能完成端口映射。</p>

            <h2 class="mdui-text-color-theme">什么是 共享的 Windows?</h2>
            <p>共享的 Windows 是一个使用远程桌面的功能，不具备管理员权限，但是你依旧可以拿他干一些需要的事情。</p>

            <h2 class="mdui-text-color-theme">什么是 文档？</h2>
            <p>文档 是 Light App Engine 偏向于社区化的一个功能，我们鼓励用户在这里撰写文档。<br />
                在编写/阅读文档时，你还可以进行SSH。每当你撰写一份文档并受到别人认同时，账号积分会 +1。</p>

            <h2 class="mdui-text-color-theme">什么是 Grafana?</h2>
            <p>这是一个还测试的功能。我们为用户提供了一个 Grafana 面板。使用这个功能需要一定的技术含量。这个功能完全免费。</p>



            <h1 class="mdui-text-color-theme">我不可以使用 Light App Engine 的服务做什么？</h1>

            <ul>
                <li>肉鸡，扫描，等危害网络安全等行为。</li>
                <li>任何违反服务器所在 “地区/国家” 法律行为。</li>
                <li>长时间高强度占用资源。</li>
            </ul>

            <p>Light App Engine 也为除了您以外的 {{ App\Models\User::count() - 2 }} 个用户提供服务，期待您的成果💗</p>
            <p>如果你有想法想对我说，欢迎发送邮件至<a href="mailto:im@ivampiresp.com">im@ivampiresp.com</a>，我会认真阅读每一份邮件！</p>
        @endguest --}}
    </div>

@endsection
