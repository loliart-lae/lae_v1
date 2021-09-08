<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{!! csrf_token() !!}" />

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdui@1.0.1/dist/css/mdui.min.css"
        integrity="sha384-cLRrMq39HOZdvE0j6yBojO4+1PrHfB7a9l5qLcmRm/fiWXYY+CndJPmyu5FV/9Tw" crossorigin="anonymous" />
    <link href="https://cdn.bootcss.com/nprogress/0.2.0/nprogress.min.css" rel="stylesheet">

    <title>@yield('title') - {{ config('app.name') }}</title>
    <style>
        .mdui-list-item {
            border-radius: 0 50px 50px 0;
        }

        .mdui-theme-primary-blue .mdui-color-theme {
            color: white !important;
        }

    </style>
</head>

<body class="mdui-appbar-with-toolbar mdui-theme-primary-blue mdui-theme-accent-blue mdui-theme-layout-auto">
    <div class="mdui-appbar mdui-appbar-fixed" id="appbar">
        <div class="mdui-tab mdui-color-theme" mdui-tab>
            @guest
                <a href="{{ route('index') }}" class="mdui-ripple mdui-ripple-white">Light App Engine</a>
                <a href="https://f.lightart.top/t/knowledge-base" class="mdui-btn mdui-ripple mdui-ripple-white">知识库</a>
                <a href="{{ route('doing') }}" class="mdui-btn mdui-ripple mdui-ripple-white">为什么选择</a>
                <a href="{{ route('about_us') }}" class="mdui-btn mdui-ripple mdui-ripple-white">关于我们</a>
                <a href="{{ route('login') }}" class="mdui-btn mdui-ripple mdui-ripple-white">登录</a>
            @else
                <a href="{{ route('main') }}" class="mdui-ripple mdui-ripple-white">Light App Engine</a>
                <a href="{{ route('projects.index') }}" class="mdui-ripple mdui-ripple-white">项目管理</a>
                <a href="{{ route('lxd.index') }}" class="mdui-ripple mdui-ripple-white">容器管理</a>
                <a href="{{ route('doing') }}" class="mdui-ripple mdui-ripple-white">Windows 工作站</a>
                <a href="https://f.lightart.top/t/knowledge-base" class="mdui-btn mdui-ripple mdui-ripple-white">知识库</a>
                <a href="" class="mdui-ripple mdui-ripple-white">剩余积分: {{ Auth::user()->balance }}</a>
                <a onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                    class="mdui-ripple mdui-ripple-white">退出登录</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endguest
        </div>
    </div>
    <div class="mdui-container mdui-p-a-2 pjax-container">
        <a id="pre_btn" href="{{ url()->previous() }}" class="mdui-btn mdui-ripple"><i
                style="position: relative; top: -1px;margin-right: 2px"
                class="mdui-icon material-icons">arrow_back</i>返回</a>
        @yield('content')
    </div>
    <script src="/js/mdui.js"></script>
    <!-- JavaScripts -->
    <script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery.pjax/1.9.6/jquery.pjax.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script src="/js/scrollspy.js"></script>
    <script>
        $(document).pjax('a', '.pjax-container')

        $("#pre_btn").hide()
        $(document).on('pjax:clicked', function() {
            $("#pre_btn").fadeIn()
        })

        if ($.cookie('at') == 'appbar' || $.cookie('at') == undefined) {
            $("html, body").animate({
                scrollTop: '0px'
            }, 10)
        } else {
            try {
                $("html, body").animate({
                    scrollTop: ($('#' + $.cookie('at')).offset().top - 55) + 'px'
                }, 10)
            } catch (e) {
                console.log('Element not found.');
            }

        }


        $('.scroll_listen').on('scrollSpy:enter', function() {
            console.log('at:' + $(this).attr('id'));
            $.cookie('at', $(this).attr('id'), {
                expires: 7,
                path: '/'
            })
        });

        $('.scroll_listen').on('scrollSpy:exit', function() {
            console.log('exit:' + $(this).attr('id'));
            $.cookie('at', 'appbar', {
                expires: 7,
                path: '/'
            })
        });

        $('.scroll_listen').scrollSpy()
    </script>
    @yield('script')
    <script>
        @if (session('status'))
            mdui.snackbar({
            message: '{{ session('status') }}',
            position: 'top'
            });
        @endif
    </script>
</body>

</html>
