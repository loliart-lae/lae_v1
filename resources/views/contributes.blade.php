@extends('layouts.app')

@section('title', '贡献名单')

@section('content')


    <div class="mdui-card mdui-center mdui-container" style="background-color: #e8e8e8">
        <div class="mdui-container mdui-m-t-3 mdui-m-b-1">
            <div class="mdui-row">
                <div class="mdui-col-xs-8">
                    <div class="mdui-typo-display-1">提醒</div>
                </div>
                <div class="mdui-col-xs-4">
                    <div class="mdui-typo-subheading-opacity mdui-text-right">
                        2021-9-16
                        <i class="mdui-icon material-icons">close</i>
                    </div>
                </div>
            </div>

            <div class="mdui-row mdui-m-t-2 mdui-m-l-1">
                <p>为了更方便的与用户们交流与提供更加实时的技术支持我们创建了一个 QQ 群：769779712</p>
            </div>
        </div>
    </div>
    <br />

    <div class="mdui-row">
        <div class="mdui-typo-display-2">非常感谢以下人员对 Light App Engine 的贡献</div><br>
        <div class="mdui-typo-headline-opacity">Light App Engine 离不开广大用户的支持。</div>
    </div>
    <h1> </h1>
<br />


    <div class="mdui-row">
        <h1 class="mdui-text-color-theme">开发人员</h1>

        <div class="mdui-col-sm-12 mdui-col-md-4 mdui-m-t-1">
            <div class="mdui-card">
                <div class="mdui-card-header">
                    <img class="mdui-card-header-avatar" src="https://nwl.im/avatar" />
                    <div class="mdui-card-header-title">iVampireSP.com</div>
                    <div class="mdui-card-header-subtitle">创始人</div>
                </div>

                <div class="mdui-card-media">
                    <img src="https://i.loli.net/2021/09/11/mKfYd4cWSwNiLx1.jpg" height="250" />
                    <div class="mdui-card-media-covered">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">咕咕咕咕咕咕咕咕咕</div>
                        </div>
                        <div class="mdui-card-actions mdui-text-capitalize">
                            <a target="_blank" href="https://ivampiresp.com/"
                                class="mdui-btn mdui-ripple mdui-ripple-white">Blog</a>
                            <a target="_blank" href="https://github.com/iVampireSP"
                                class="mdui-btn mdui-ripple mdui-ripple-white">Github</a>
                            <a target="_blank" href="mailto:im@ivampiresp.com"
                                class="mdui-btn mdui-ripple mdui-ripple-white">Emial</a>
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
                                class="mdui-btn mdui-ripple mdui-ripple-white">Emial</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mdui-row">
        <h1 class="mdui-text-color-theme">Bug 发现者</h1>
        <p>非常感谢以下人员及时发现并反馈Bug。</p>
        <div class="mdui-typo">
            <ul>
                <li>苏沫</li>
                <li>Tony Stark</li>
                <li>凌辰溪</li>
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
                <li>以及没有被提及的广大用户们。</li>
            </ul>
        </div>
    </div>

    <div class="mdui-row">
        <h1 class="mdui-text-color-theme">知识分享者</h1>
        <p>非常感谢以下人员丰富社区知识库，让大家能够更好的博览。</p>
        <div class="mdui-typo">
            <ol>
                <p>暂无成员，争做第一吧。</p>
            </ol>
        </div>
    </div>

@endsection
