<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />

    <meta name="theme-color" content="#2196F3" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{!! csrf_token() !!}" />

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdui@1.0.1/dist/css/mdui.min.css"
        integrity="sha384-cLRrMq39HOZdvE0j6yBojO4+1PrHfB7a9l5qLcmRm/fiWXYY+CndJPmyu5FV/9Tw" crossorigin="anonymous" />
    <link href="https://cdn.bootcdn.net/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <title>@yield('title') - {{ config('app.name') }}</title>
    <style>
        .mdui-theme-primary-blue .mdui-color-theme {
            color: white !important
        }

        .mdui-tab-scrollable {
            padding-left: 0
        }

        .mdui-btn {
            border-radius: 4px
        }

        .mdui-card,
        .mdui-table-fluid,
        .mdui-dialog,
        .mdui-panel-item {
            border-radius: 5px
        }

        .pjax-container {
            transition: all 0.3s ease-in-out
        }

        .mdui-typo-display-2 {
            margin-bottom: 10px
        }

        .can_copy {
            cursor: pointer
        }

    </style>

    <!-- JavaScripts -->
    <script src="/js/mdui.js?bypasscache=202109150854"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-base64@3.7.1/base64.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery.pjax/1.9.6/jquery.pjax.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/masonry/4.2.2/masonry.pkgd.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>

</head>

<body class="mdui-appbar-with-toolbar mdui-theme-primary-blue mdui-theme-accent-blue mdui-theme-layout-auto">
    <div id="offline_tip" style="width: 100%;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 99999;
    backdrop-filter: blur(20px) saturate(200%);display:none;opacity:1">
        <div style="width:100%;position: absolute;
    top: 50%;
    margin-top: -75px;text-align:center">
            <h1 style="font-weight: 200;">无法连接到服务器<br /></h1>
            <p><span onclick="close_offline_tip()" style="cursor: pointer">此提示将在通信恢复后自动关闭，您也可以点击这里手动关闭。</span></p>
        </div>

    </div>
    <div class="mdui-appbar mdui-appbar-fixed" id="appbar" mdui-headroom>
        <div class="mdui-tab mdui-color-theme mdui-tab-scrollable mdui-tab-full-width @auth
        mdui-tab-centered
        @endauth"
            mdui-tab>
            @guest
                <a href="{{ route('index') }}" class="main_link">{{ config('app.name') }}</a>
                <a href="{{ route('login') }}" class="mdui-ripple mdui-ripple-white">登录</a>
                {{-- <a href="{{ route('why') }}" class="mdui-ripple mdui-ripple-white">为什么选择</a> --}}
                <a href="{{ route('why_begin') }}" class="mdui-ripple mdui-ripple-white">我们的初心</a>
                <!-- 说实话我也不知道为什么这里会给未登录用户展示这个，很奇怪 我先注释掉吧
                            <a href="{{ route('login') }}" class="mdui-ripple mdui-ripple-white">项目管理</a>
                            <a href="{{ route('login') }}" class="mdui-ripple mdui-ripple-white">Linux 容器</a>
                            <a href="{{ route('login') }}" class="mdui-ripple mdui-ripple-white">共享的 Windows</a>
                            <a href="{{ route('login') }}" class="mdui-ripple mdui-ripple-white">穿透隧道</a>
                            <a href="{{ route('login') }}" class="mdui-ripple mdui-ripple-white">快捷访问</a>
                            <a href="{{ route('login') }}" class="mdui-ripple mdui-ripple-white">文档中心</a>
                            -->
            @else
                <a href="{{ route('main') }}" class="main_link">{{ config('app.name') }}</a>
                <a href="{{ route('user.index') }}" class="mdui-ripple mdui-ripple-white"
                    style="white-space: nowrap"><small>
                        {{ Auth::user()->name }} / <span id="userBalance"
                            style="display: contents;">{{ Auth::user()->balance }}</span></small></a>
                <a href="{{ route('projects.index') }}" class="mdui-ripple mdui-ripple-white">项目管理</a>
                <a href="{{ route('lxd.index') }}" class="mdui-ripple mdui-ripple-white">应用容器</a>
                <a href="{{ route('remote_desktop.index') }}" class="mdui-ripple mdui-ripple-white">共享的 Windows</a>
                <a href="{{ route('tunnels.index') }}" class="mdui-ripple mdui-ripple-white">穿透隧道</a>
                <a href="{{ route('fastVisit.index') }}" class="mdui-ripple mdui-ripple-white">快捷访问</a>
                {{-- <a href="{{ route('images.index') }}" class="mdui-ripple mdui-ripple-white">图片展廊</a> --}}

                {{-- <a href="{{ route('forums.index') }}" class="mdui-ripple mdui-ripple-white">社区论坛</a> --}}
                <a href="{{ route('staticPage.index') }}" class="mdui-ripple mdui-ripple-white">静态站点</a>
                {{-- <a onclick="mdui.alert('在做了再做了')" class="mdui-ripple mdui-ripple-white">B2B主机</a> --}}
                {{-- <a href="{{ route('commandJobs.index') }}" class="mdui-ripple mdui-ripple-white">脚本队列</a> --}}
                <a href="{{ route('documents.index') }}" class="mdui-ripple mdui-ripple-white">文档中心</a>
                <a target="_blank" href="https://f.lightart.top" class="mdui-ripple mdui-ripple-white">社区论坛</a>

                <a onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                    class="mdui-ripple mdui-ripple-white">退出登录</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endguest
        </div>
    </div>
    <div class="@yield('container', 'mdui-container') pjax-container">
        <div id="topic" class="mdui-m-b-1">
        </div>

        <a id="pre_btn" href="{{ url()->previous() }}" class="mdui-btn mdui-ripple mdui-m-b-1"><i
                style="position: relative; top: -1px;margin-right: 2px"
                class="mdui-icon material-icons">arrow_back</i>返回</a>

        @yield('content')


        <div class="mdui-typo" style="margin-top: 50px">
            <p class="mdui-typo-caption-opacity mdui-text-center">
                <br />Hosted by {{ config('app.host_by') }}
            </p>
        </div>

    </div>

    <div class="mdui-container mdui-m-b-5">
        <div class="mdui-typo">
            <p class="mdui-typo-caption-opacity mdui-text-center">
                {{ config('app.name') }}, <a href="https://github.com/loliart-lae/lae">Github</a><br />
                Crafted with 💗 by <a href="{{ route('contributes') }}">Contributors</a><br />
            </p>
        </div>
    </div>

    <script src="/vendor/editor.md/lib/marked.min.js"></script>
    <script src="/vendor/editor.md/lib/prettify.min.js"></script>
    <script src="/vendor/editor.md/lib/underscore.min.js"></script>
    <script src="/vendor/editor.md/lib/flowchart.min.js"></script>
    <script src="/vendor/editor.md/lib/jquery.flowchart.min.js"></script>
    <script src="/vendor/editor.md/js/editormd.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        })

        var main_link = '{{ config('app.name') }}'
        $.pjax.defaults.timeout = 1200

        function close_offline_tip() {
            $('#offline_tip').fadeOut()
            $('body').css('overflow', 'auto')
        }



        function showOfflineTip() {
            mdui.snackbar({
                message: '无法连接到 LAE',
                position: 'right-bottom',
                buttonText: '显示',
                onButtonClick: function() {
                    $('#offline_tip').fadeIn()
                    $('body').css('overflow', 'hidden')
                }
            })
        }

        window.addEventListener('online', close_offline_tip)
        window.addEventListener('offline', showOfflineTip)

        $(document).pjax('a', '.pjax-container')

        $("#pre_btn").hide()
        $(document).on('pjax:clicked', function() {
            $("#pre_btn").fadeIn()
            $('.pjax-container').css('opacity', '0.7')
            $('.pjax-container').css('transform', 'scale(0.99)')
        })
        $(document).on("pjax:timeout", function(event) {
            $('.pjax-container').css('opacity', '0.2')
            $('.pjax-container').css('transform', 'scale(0.98)')
            $('.main_link').html(`<div class="mdui-progress" style="background-color: rgb(48 48 48)">
  <div class="mdui-progress-indeterminate" style="background-color: #2196f3"></div>
</div>`)

            event.preventDefault()
        })

        $(document).on("pjax:complete", function(event) {
            $('.main_link').html(main_link)
            $('.pjax-container').css('opacity', '1')
            $('.pjax-container').css('transform', 'unset')
        })
    </script>
    @yield('script')
    @auth
        <script>
            setInterval(function() {
                var updateCount = 0
                var date = new Date()
                var startTime = Date.parse(date)

                if (localStorage.getItem('startTime') == null) {
                    localStorage.setItem('startTime', startTime)
                }
                current = localStorage.getItem('startTime')
                if (startTime - current >= 10000) {
                    // 立即更新localStorage，然后获取通知
                    localStorage.setItem('startTime', startTime)

                    $.ajax({
                        type: 'GET',
                        url: '{{ route('messages.unread') }}',
                        dataType: 'json',
                        success: function(data) {
                            close_offline_tip()
                            var currentBalance = parseFloat($('#userBalance').text())
                            if (currentBalance != data.balance && updateCount == 0) {
                                mdui.snackbar({
                                    message: '账户积分已更新为:' + data.balance,
                                    position: 'right-bottom'
                                })
                                $({
                                    // 起始值
                                    countNum: currentBalance
                                }).animate({
                                    // 最终值
                                    countNum: data.balance
                                }, {
                                    // 动画持续时间
                                    duration: 2000,
                                    easing: "linear",
                                    step: function() {
                                        // 设置每步动画计算的数值
                                        $('#userBalance').text(Math.floor(this.countNum))
                                    },
                                    complete: function() {
                                        // 设置动画结束的数值
                                        $('#userBalance').text(this.countNum)
                                    }
                                })
                            }
                            updateCount++
                            $('#userBalance').html(data.balance)
                            for (var i = 0; i < data.data.length; i++) {
                                if (data.data.length != 0) {
                                    mdui.snackbar({
                                        message: data.data[i].content,
                                        position: 'right-bottom'
                                    })
                                }
                            }
                        },
                        error: function(data) {
                            showOfflineTip()
                        }
                    })
                }
            }, 1000)
        </script>
    @endauth
    <script>
        @if (session('status'))
            mdui.snackbar({
            message: '{{ session('status') }}',
            position: 'top'
            })
        @endif
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                mdui.snackbar({
                message: 'Error: ' + '{{ $error }}',
                position: 'bottom'
                })
            @endforeach
        @endif

        if (!$.cookie('is_readed')) {
            if (!window.localStorage) {
                mdui.snackbar({
                    message: '你的浏览器不支持 localStorage, 队列更新可能不会启用。',
                    position: 'bottom'
                })
            } else {
                @auth
                    mdui.snackbar({
                    message: '检测到 localStorage, 你将会间接收到通知。',
                    position: 'bottom'
                    })
                @endauth
            }
            $('#topic').append(`<div class="mdui-panel" mdui-panel>
                <div class="mdui-panel-item mdui-panel-item-open">
                    <div class="mdui-panel-item-header">
                        <div class="mdui-panel-item-title">加入 QQ 群</div>
                        <i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
                    </div>
                    <div class="mdui-panel-item-body">
                        为了更方便的与用户们交流与提供更加实时的技术支持，我们创建了一个 QQ 群：769779712。
                        <div class="mdui-panel-item-actions">
                            <button class="mdui-btn mdui-ripple" onclick="$.cookie('is_readed', '1', {
                                expires: 7,
                                path: '/'
                            });$('#topic').hide()">我知道了，消失吧！</button>
                        </div>
                    </div>
                </div>
            </div>`)
            $('#topic').css('margin-bottom', '10px')
        }
    </script>
</body>

</html>
