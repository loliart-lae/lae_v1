@extends('layouts.app')

@section('title', '贡献名单')

@section('content')
    <br /><br />
    <div class="mdui-typo-display-2">非常感谢以下人员对 Light App Engine 的贡献</div><br>
    <div class="mdui-typo-display-1-opacity">Light App Engine 离不开广大用户的支持。</div>
    <br /><br />

    <div class="mdui-row">
        <h1 class="mdui-text-color-theme">开发人员</h1>
        
        <div class="mdui-col-sm-6 mdui-col-md-4">
            <div class="mdui-card">
                <!-- 卡片头部，包含头像、标题、副标题 -->
                <div class="mdui-card-header">
                    <img class="mdui-card-header-avatar" src="https://nwl.im/avatar"/>
                    <div class="mdui-card-header-title">iVampireSP.com</div>
                    <div class="mdui-card-header-subtitle">创始人</div>
                </div>

                <div class="mdui-card-media">
                    <img src="https://i.loli.net/2021/09/11/mKfYd4cWSwNiLx1.jpg"/>
                    <div class="mdui-card-media-covered">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">Title</div>
                            <div class="mdui-card-primary-subtitle">Subtitle</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mdui-col-sm-6 mdui-col-md-4">
            <div class="mdui-card">
                <!-- 卡片头部，包含头像、标题、副标题 -->
                <div class="mdui-card-header">
                    <img class="mdui-card-header-avatar" src="https://q1.qlogo.cn/g?b=qq&nk=1016696385&s=100"/>
                    <div class="mdui-card-header-title">冰砚炽</div>
                    <div class="mdui-card-header-subtitle">前端优化</div>
                </div>

                <div class="mdui-card-media">
                    <img src="https://i.loli.net/2021/09/11/QvS4W3tNhlb7G9p.png"/>
                    <div class="mdui-card-media-covered">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">咸鱼中的咸鱼</div>
                        </div>
                        <div class="mdui-card-actions">
                            <button class="mdui-btn mdui-ripple mdui-ripple-white">Blog</button>
                            <button class="mdui-btn mdui-ripple mdui-ripple-white">Github</button>
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
