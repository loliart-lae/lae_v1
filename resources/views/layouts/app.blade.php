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

    <title>@yield('title') - {{ config('app.name') }}</title>
    <style>
        .mdui-theme-primary-blue .mdui-color-theme {
            color: white !important;
        }

        .mdui-tab-scrollable {
            padding-left: 0;
        }

        .mdui-btn {
            border-radius: 3px
        }

        .mdui-card,
        .mdui-table-fluid,
        .mdui-dialog {
            border-radius: 5px
        }

    </style>

    <!-- JavaScripts -->
    <script src="/js/mdui.js?bypasscache=202109150854"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-base64@3.7.1/base64.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery.pjax/1.9.6/jquery.pjax.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

</head>

<body class="mdui-appbar-with-toolbar mdui-theme-primary-blue mdui-theme-accent-blue mdui-theme-layout-auto">
    <div class="mdui-appbar mdui-appbar-fixed mdui-tab-centered" id="appbar">
        <div class="mdui-tab mdui-color-theme mdui-tab-scrollable mdui-tab-full-width mdui-tab-centered" mdui-tab>
            @guest
                <a class="main_link" href="{{ route('index') }}" class="mdui-ripple mdui-ripple-white">Light App Engine</a>
                <a href="{{ route('login') }}" class="mdui-btn mdui-ripple mdui-ripple-white">登录</a>
                <a target="_blank" href="https://f.lightart.top/t/knowledge-base"
                    class="mdui-btn mdui-ripple mdui-ripple-white">知识库</a>
                <a href="{{ route('why') }}" class="mdui-btn mdui-ripple mdui-ripple-white" disabled>为什么选择</a>
                <a href="{{ route('about_us') }}" class="mdui-btn mdui-ripple mdui-ripple-white" disabled>关于我们</a>
            @else
                <a class="main_link" href="{{ route('main') }}" class="mdui-ripple mdui-ripple-white">Light App Engine</a>
                <a href="{{ route('billing.index') }}" class="mdui-ripple mdui-ripple-white">剩余积分:
                    <span id="userBalance" style="display: contents;">{{ Auth::user()->balance }}</span></a>
                <a href="{{ route('projects.index') }}" class="mdui-ripple mdui-ripple-white">项目管理</a>
                <a href="{{ route('lxd.index') }}" class="mdui-ripple mdui-ripple-white">容器管理</a>
                <a href="{{ route('remote_desktop.index') }}" class="mdui-ripple mdui-ripple-white">共享的 Windows</a>
                <a href="{{ route('tunnels.index') }}" class="mdui-ripple mdui-ripple-white">穿透隧道</a>
                <a href="{{ route('documents.index') }}" class="mdui-ripple mdui-ripple-white">文档</a>
                <a target="_blank" href="https://f.lightart.top/" class="mdui-ripple mdui-ripple-white">社区</a>
                <a target="_blank" href="https://f.lightart.top/t/knowledge-base"
                    class="mdui-ripple mdui-ripple-white">知识库</a>
                <a onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                    class="mdui-ripple mdui-ripple-white">退出登录</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endguest
        </div>
    </div>
    <div class="@yield('container', 'mdui-container') mdui-p-a-2 pjax-container">
        <div id="topic">

        </div>

        <a id="pre_btn" href="{{ url()->previous() }}" class="mdui-btn mdui-ripple mdui-m-b-1"><i
                style="position: relative; top: -1px;margin-right: 2px"
                class="mdui-icon material-icons">arrow_back</i>返回</a>

        @yield('content')

        <div class="mdui-typo mdui-m-t-5">
            <p class="mdui-typo-caption-opacity mdui-text-center">
                <a href="{{ route('contributes') }}">Contributors</a>
                <br />Light App Engine host by {{ config('app.host_by') }}<br />
                Crafted with 💗 by iVampireSP.com<br />
            </p>
        </div>
    </div>


    <script>
        var main_link = 'Light App Engine'
        $.pjax.defaults.timeout = 1200
        $(document).pjax('a', '.pjax-container')

        $("#pre_btn").hide()
        $(document).on('pjax:clicked', function() {
            $("#pre_btn").fadeIn()
        })
        $(document).on("pjax:timeout", function(event) {
            $('.main_link').html(`<div class="mdui-progress" style="background-color: rgb(48 48 48)">
  <div class="mdui-progress-indeterminate" style="background-color: #2196f3"></div>
</div>`)
            event.preventDefault()
        })

        $(document).on("pjax:complete", function(event) {
            $('.main_link').html(main_link)
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
                                        $('#userBalance').text(Math.floor(this.countNum));
                                    },
                                    complete: function() {
                                        // 设置动画结束的数值
                                        $('#userBalance').text(this.countNum);
                                    }
                                });
                            }
                            updateCount++;
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
                            mdui.snackbar({
                                message: '此时无法获取通知。',
                                position: 'right-bottom'
                            })
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
            });
        @endif
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                mdui.snackbar({
                message: 'Error: ' + '{{ $error }}',
                position: 'bottom'
                });
            @endforeach
        @endif

        if (!$.cookie('is_readed')) {
            if (!window.localStorage) {
                mdui.snackbar({
                    message: '你的浏览器不支持 localStorage, 队列更新可能不会启用。',
                    position: 'bottom'
                });
            } else {
                mdui.snackbar({
                    message: '检测到 localStorage, 你将会间接收到通知。',
                    position: 'bottom'
                });
            }
            $('#topic').append(` <div class="mdui-float-right"><div class="mdui-chip">
                <span class="mdui-chip-title">为了更方便的与用户们交流与提供更加实时的技术支持我们创建了一个 QQ 群：769779712</span>
                <span class="mdui-chip-delete" onclick="$.cookie('is_readed', '1', {
                                            expires: 7,
                                            path: '/'
                                        });$('#topic').hide()"><i class="mdui-icon material-icons">cancel</i></span>
            </div></div>`)
            $('#topic').css('margin-bottom', '10px')
        }
    </script>
</body>

</html>
