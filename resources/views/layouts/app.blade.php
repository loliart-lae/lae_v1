<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />

    {{-- <meta name="theme-color" content="#2196F3" /> --}}

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{!! csrf_token() !!}" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdui@1.0.1/dist/css/mdui.min.css"
        integrity="sha384-cLRrMq39HOZdvE0j6yBojO4+1PrHfB7a9l5qLcmRm/fiWXYY+CndJPmyu5FV/9Tw" crossorigin="anonymous" />
    <link href="https://cdn.bootcdn.net/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <link rel="icon" href="/LAE.png">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <title>@yield('title') - {{ config('app.name') }}</title>

    <!-- JavaScripts -->
    <script src="https://cdn.jsdelivr.net/npm/mdui@1.0.1/dist/js/mdui.min.js"
        integrity="sha384-gCMZcshYKOGRX9r6wbDrvF+TcCCswSHFucUzUPwka+Gr+uHgjlYvkABr95TCOz3A" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/js-base64@3.7.1/base64.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    @livewireStyles
</head>

<body class="mdui-appbar-with-toolbar mdui-theme-primary-blue mdui-theme-accent-blue mdui-theme-layout-auto">
    <x-body-script />
    <script src="{{ mix('js/util.js') }}"></script>

    <header class="mdui-appbar mdui-appbar-fixed">
        <div class="mdui-toolbar mdui-color-theme">
            <span class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white"
                mdui-drawer="{target: '#main-drawer', swipe: true, overlay:true}"><i
                    class="mdui-icon material-icons-outlined">menu</i></span>
            <a href="/" class="mdui-typo-title"
                style="font-weight: 400;">{{ config('app.name') }}</a>
            <div class="mdui-toolbar-spacer"></div>
            @auth

                @if (!Agent::isMobile())
                    <a class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white" id="backMain"
                        mdui-tooltip="{content: '回到主层级', delay: 1000}" href="#">
                        <i class="mdui-icon material-icons-outlined">grid_view</i>
                    </a>
                @endif

            @endauth

            <span class="mdui-btn mdui-btn-icon turn-animate" id="turn" mdui-tooltip="{content: '指示器', delay: 1000}"
                onclick="$('#thisLink').click()">
                <i class="mdui-icon material-icons-outlined">refresh</i>
            </span>

            @auth
                <span class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white" mdui-menu="{target: '#app-menu'}">
                    <i class="mdui-icon material-icons-outlined">more_vert</i>
                </span>
                <x-tool-menu />
            @endauth
        </div>
    </header>

    <div class="mdui-drawer mdui-color-white mdui-drawer-close mdui-drawer-full-height" id="main-drawer">
        <div class="mdui-list" mdui-collapse="{accordion: true}" style="margin-bottom: 76px;">
            <x-main-menu />
        </div>
    </div>

    <x-offline-tip />

    <div class="mdui-container pjax-container" id="main">
        <div id="topic" class="mdui-m-b-1">
        </div>
        <div class="mdui-m-t-3">
            @yield('content')
        </div>
    </div>

    <a href="#" id="thisLink" style="display: none"></a>

    <div class="mdui-fab-wrapper mdui-fab-hide accelerato" id="bottom-fab">
        <button class="mdui-fab mdui-ripple mdui-color-theme">
            <i class="mdui-icon material-icons">add</i>
            <i class="mdui-icon mdui-fab-opened material-icons">menu</i>
        </button>
        <div class="mdui-fab-dial">
            <a href="{{ route('projects.index') }}" class="mdui-fab mdui-fab-mini mdui-ripple"><i
                    class="mdui-icon material-icons">groups</i>
            </a>

            <a href="/" class="mdui-fab mdui-fab-mini mdui-ripple"><i
                    class="mdui-icon material-icons">home</i>
            </a>

            <a href="#" class="mdui-fab mdui-fab-mini mdui-ripple"><i class="mdui-icon material-icons">arrow_upward</i>
            </a>
        </div>
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
        let bottom_fab = new mdui.Fab('#bottom-fab');


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
    @auth
        <script src="/js/message.js?pass={{ time() }}"></script>
    @endauth
    <script src="{{ mix('js/pjax.js') }}"></script>
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
                message: '{{ $error }}',
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
                        <i class="mdui-panel-item-arrow mdui-icon material-icons-outlined">keyboard_arrow_down</i>
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
    @livewireScripts

    <div class="mdui-m-t-5 mdui-m-b-5"></div>
</body>

</html>
