@extends('layouts.app')

@section('title', '贡献名单')

@section('content')
    <br /><br />
    <div class="mdui-typo-display-2">非常感谢以下人员对 Light App Engine 的贡献</div><br>
    <div class="mdui-typo-display-1-opacity">Light App Engine 离不开广大用户的支持。</div>
    <br /><br /><br /><br />

    <div>
        <div class="mdui-typo-display-2">开发人员</div>
        <div style="margin-left: 10px;padding: 20px">
        
        <div class="mdui-col-sm-6 mdui-col-md-4">
            <div class="mdui-card">
                <!-- 卡片头部，包含头像、标题、副标题 -->
                <div class="mdui-card-header">
                    <img class="mdui-card-header-avatar" src="https://nwl.im/avatar"/>
                </div>

                <div class="mdui-card-media">
                    <img src="https://i.loli.net/2021/09/11/QvS4W3tNhlb7G9p.png"/>
                    <div class="mdui-card-media-covered">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">Title</div>
                            <div class="mdui-card-primary-subtitle">Subtitle</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <div class="mdui-m-b-4">
                <img width="100" height="100"class="mdui-img-circle" src="https://nwl.im/avatar" />
                <div class="mdui-typo-display-1">iVampireSP.com</div>
                <ul>
                    <li>创始人</li>
                </ul>
            </div>

            <div class="mdui-m-b-4">
                <img width="100" height="100"class="mdui-img-circle" src="https://q1.qlogo.cn/g?b=qq&nk=1016696385&s=100" />
                <div class="mdui-typo-display-1">冰砚炽</div>
                <ul>
                    <li>前端优化</li>
                </ul>
            </div>

        </div>

    </div>

    <div>
        <div class="mdui-typo-display-2">Bug 发现者</div>
        <p>非常感谢以下人员及时发现并反馈Bug。</p>
        <div style="margin-left: 10px;padding: 20px">
            <ol>
                <li>苏沫</li>
                <li>Tony Stark</li>
                <li>以及没有被提及的广大用户们。</li>
            </ol>
        </div>
    </div>

    <div>
        <div class="mdui-typo-display-2">意见提交者</div>
        <p>非常感谢以下人员向我们提供宝贵的意见。</p>
        <div style="margin-left: 10px;padding: 20px">
            <ol>
                <li>苏沫</li>
                <li>Tony Stark</li>
                <li>以及没有被提及的广大用户们。</li>
            </ol>
        </div>
    </div>

    <div>
        <div class="mdui-typo-display-2">知识分享者</div>
        <p>非常感谢以下人员丰富社区知识库，让大家能够更好的博览。</p>
        <div style="margin-left: 10px;padding: 20px">
            <ol>
                <p>暂无成员，争做第一吧。</p>
            </ol>
        </div>
    </div>





@endsection
