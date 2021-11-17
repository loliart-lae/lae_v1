@extends('layouts.app')

@section('title', '贡献名单')

@section('content')
    <div class="mdui-row">
        <div class="mdui-typo-display-1">非常感谢以下人员对 Light App Engine的贡献</div>
        <div class="mdui-typo-headline-opacity mdui-p-t-1">贡献者们成就了这个平台
            <br />
            而我们使用开源回报社区
        </div>
    </div>

    <div class="mdui-row mdui-p-t-3">
        <h1 class="mdui-text-color-theme">开发人员</h1>

        <div class="mdui-col-sm-12 mdui-col-md-4 mdui-m-t-1">
            <div class="mdui-card">
                <div class="mdui-card-header">
                    <img class="mdui-card-header-avatar"
                        src="https://sdn.geekzu.org/avatar/9116fc3de8f9a46668beb1a6b7dbcbcd" />
                    <div class="mdui-card-header-title">iVampireSP.com</div>
                    <div class="mdui-card-header-subtitle">项目发起人</div>
                </div>

                <div class="mdui-card-media">
                    <img src="https://i.loli.net/2021/09/11/mKfYd4cWSwNiLx1.jpg" height="250" />
                    <div class="mdui-card-media-covered">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">想当一个小天使</div>
                        </div>
                        <div class="mdui-card-actions mdui-text-capitalize">
                            <a target="_blank" href="https://ivampiresp.com/"
                                class="mdui-btn mdui-ripple mdui-ripple-white">Blog</a>
                            <a target="_blank" href="https://github.com/iVampireSP"
                                class="mdui-btn mdui-ripple mdui-ripple-white">Github</a>
                            <a target="_blank" href="mailto:im@ivampiresp.com"
                                class="mdui-btn mdui-ripple mdui-ripple-white">Email</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mdui-col-sm-12 mdui-col-md-4 mdui-m-t-1">
            <div class="mdui-card">
                <div class="mdui-card-header">
                    <img class="mdui-card-header-avatar" src="https://q1.qlogo.cn/g?b=qq&nk=1016696385&s=100" />
                    <div class="mdui-card-header-title">冰砚炽</div>
                    <div class="mdui-card-header-subtitle">前端优化</div>
                </div>

                <div class="mdui-card-media">
                    <img src="https://i.loli.net/2021/09/11/QvS4W3tNhlb7G9p.png" height="250" />
                    <div class="mdui-card-media-covered">
                        <div class="mdui-card-primary">
                            <a class="mdui-card-primary-title">咸鱼中的咸鱼</a>
                        </div>
                        <div class="mdui-card-actions">
                            <a target="_blank" href="https://www.yistars.cn/"
                                class="mdui-btn mdui-ripple mdui-ripple-white">Blog</a>
                            <a target="_blank" href="https://github.com/BingYanchi"
                                class="mdui-btn mdui-ripple mdui-ripple-white">Github</a>
                            <a target="_blank" href="mailto:Bing_Yanchi@outlook.com"
                                class="mdui-btn mdui-ripple mdui-ripple-white">Email</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mdui-row">
        <h1 class="mdui-text-color-theme">问题发现者</h1>
        <p>非常感谢以下人员及时发现并反馈问题。</p>
        <div class="mdui-typo">
            <ul>
                <li>苏沫</li>
                <li>Tony Stark</li>
                <li>凌辰溪</li>
                <li>Chi_Tang</li>
                <li>SakuraPuare</li>
                <li>懒猫</li>
                <li>AevTe</li>
                <li>电脑菌</li>
                <li>Qiuscraft.</li>
                <li>以及没有被提及的广大用户们。</li>
            </ul>
        </div>
    </div>

    <div class="mdui-row">
        <h1 class="mdui-text-color-theme">意见提交者</h1>
        <p>非常感谢以下人员向我们提供宝贵的意见。</p>
        <div class="mdui-typo">
            <ul>
                <li>苏沫</li>
                <li>Tony Stark</li>
                <li>xixi</li>
                <li>FunnyStudio</li>
                <li>HuoYiNetwork_Li</li>
                <li>MSNL-Dcloud</li>
                <li>以及没有被提及的广大用户们。</li>
            </ul>
        </div>
    </div>

    <div class="mdui-row">
        <h1 class="mdui-text-color-theme">特别贡献者</h1>
        <p>感谢热爱的你们。</p>
        <div class="mdui-typo">
            <ul>
                {{-- 初次宣传 --}}
                <li>KiteAB</li>
                {{-- 配音 --}}
                <li>思衔</li>
                {{-- 2021.10.3 提供两台服务器 --}}
                <li>久梦</li>
            </ul>
        </div>
    </div>

    <div class="mdui-row">
        <h1 class="mdui-text-color-theme">知识分享者</h1>
        <p>非常感谢以下人员丰富社区知识库。</p>
        <div class="mdui-typo">
            <ul>
                <li>HuoYiNetwork_Li</li>
                <li>以及没有被提及的广大用户们。</li>
            </ul>
        </div>
    </div>

    <div class="mdui-row">
        <h1 class="mdui-text-color-theme">友情链接</h1>
        <div class="mdui-typo">
            <ol>
                <p><a href="https://geekjie.com/">极客街社区</a></p>
            </ol>
        </div>
    </div>

    <div class="mdui-container mdui-m-t-5 load-hidden">
        <div class="mdui-typo">
            <p class="mdui-typo-caption-opacity mdui-text-center">
                {{ config('app.name') }}, <a class="umami--click--lae-github-repo"
                    href="https://github.com/loliart-lae/lae">Github</a><br />
                Crafted with 💗 by <a class="umami--click--contributes"
                    href="{{ route('contributes') }}">Contributors</a><br />
                Powered by LoliArt & Yistars
            </p>
        </div>
    </div>
@endsection
