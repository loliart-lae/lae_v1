<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />

    <meta name="theme-color" content="#2196F3" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{!! csrf_token() !!}" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdui@1.0.1/dist/css/mdui.min.css"
        integrity="sha384-cLRrMq39HOZdvE0j6yBojO4+1PrHfB7a9l5qLcmRm/fiWXYY+CndJPmyu5FV/9Tw" crossorigin="anonymous" />
    <link href="https://cdn.bootcdn.net/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <title>@yield('title') - {{ config('app.name') }}</title>

    <!-- JavaScripts -->
    <script src="https://cdn.jsdelivr.net/npm/mdui@1.0.1/dist/js/mdui.min.js"
        integrity="sha384-gCMZcshYKOGRX9r6wbDrvF+TcCCswSHFucUzUPwka+Gr+uHgjlYvkABr95TCOz3A" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/js-base64@3.7.1/base64.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
</head>

<body class="mdui-appbar-with-toolbar mdui-theme-primary-blue mdui-theme-accent-blue mdui-theme-layout-auto">
    <x-body-script />

    <header class="mdui-appbar mdui-appbar-fixed">
        <div class="mdui-toolbar mdui-color-theme">
            <span class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white"
                mdui-drawer="{target: '#main-drawer', swipe: true, overlay:true}"><i
                    class="mdui-icon material-icons">menu</i></span>
            <a href="/" class="mdui-typo-title">{{ config('app.name') }}</a>
            <div class="mdui-toolbar-spacer"></div>
        </div>
    </header>

    <div class="mdui-drawer mdui-color-white mdui-drawer-close mdui-drawer-full-height" id="main-drawer">
        <div class="mdui-list" mdui-collapse="{accordion: true}" style="margin-bottom: 76px;">
            <x-main-menu />
        </div>
    </div>

    <x-offline-tip />

    @include('include._loading')

    <div class="mdui-container pjax-container" id="main">
        <div id="topic" class="mdui-m-b-1">
        </div>
        <div class="mdui-m-t-3">
            @yield('content')
        </div>
    </div>

    <div class="mdui-container mdui-m-b-5 mdui-p-t-5 load-hidden">
        <div class="mdui-typo">
            <p class="mdui-typo-caption-opacity mdui-text-center">
                {{ config('app.name') }}, <a class="umami--click--lae-github-repo"
                    href="https://github.com/loliart-lae/lae">Github</a><br />
                Crafted with 💗 by <a class="umami--click--contributes"
                    href="{{ route('contributes') }}">Contributors</a><br />
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
        mdui.mutation()
        let main_link = '{{ config('app.name') }}'

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
    </script>
    <script src="{{ mix('js/pjax.js') }}"></script>
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
                            <button class="mdui-btn mdui-ripple umami--click--hide-topic" onclick="$.cookie('is_readed', '1', {
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

    @include('extend.footer')
</body>

</html>
