<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no" />

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
    <script src="/js/mdui.js"></script>
    <script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery.pjax/1.9.6/jquery.pjax.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

    <script src="/editor.md/lib/marked.min.js"></script>
    <script src="/editor.md/editormd.min.js"></script>
</head>

<body class="mdui-appbar-with-toolbar mdui-theme-primary-blue mdui-theme-accent-blue mdui-theme-layout-auto">
    <div class="mdui-appbar mdui-appbar-fixed mdui-tab-centered" id="appbar">
        <div class="mdui-tab mdui-color-theme mdui-tab-scrollable mdui-tab-full-width mdui-tab-centered" mdui-tab>
            @guest
                <a href="{{ route('index') }}" class="mdui-ripple mdui-ripple-white">Light App Engine</a>
                <a href="{{ route('login') }}" class="mdui-btn mdui-ripple mdui-ripple-white">登录</a>
                <a target="_blank" href="https://f.lightart.top/t/knowledge-base"
                    class="mdui-btn mdui-ripple mdui-ripple-white">知识库</a>
                <a href="{{ route('why') }}" class="mdui-btn mdui-ripple mdui-ripple-white">为什么选择</a>
                <a href="{{ route('about_us') }}" class="mdui-btn mdui-ripple mdui-ripple-white" disabled>关于我们</a>
            @else
                <a href="{{ route('main') }}" class="mdui-ripple mdui-ripple-white">Light App Engine</a>
                <a href="{{ route('billing.index') }}" class="mdui-ripple mdui-ripple-white">剩余积分:
                    {{ Auth::user()->balance }}</a>
                <a href="{{ route('projects.index') }}" class="mdui-ripple mdui-ripple-white">项目管理</a>
                <a href="{{ route('lxd.index') }}" class="mdui-ripple mdui-ripple-white">容器管理</a>
                <a href="{{ route('remote_desktop.index') }}" class="mdui-ripple mdui-ripple-white">共享的 Windows</a>
                <a href="{{ route('tunnels.index') }}" class="mdui-ripple mdui-ripple-white">穿透隧道</a>
                <a target="_blank" href="https://f.lightart.top/" class="mdui-btn mdui-ripple mdui-ripple-white">社区</a>
                <a target="_blank" href="https://f.lightart.top/t/knowledge-base"
                    class="mdui-btn mdui-ripple mdui-ripple-white">知识库</a>
                <a onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                    class="mdui-ripple mdui-ripple-white">退出登录</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endguest
        </div>
    </div>
    <div class="mdui-container mdui-p-a-2 pjax-container">
        <div id="topic" style="margin-bottom: 10px;display: none">
            <div class="mdui-chip">
                <span class="mdui-chip-title">为了更方便的与用户们交流与提供更加实时的技术支持，我们创建了一个QQ群，群号码是：769779712</span>
                <span class="mdui-chip-delete" onclick="$.cookie('is_readed', '1', {
                                            expires: 7,
                                            path: '/'
                                        });$('#topic').hide()"><i class="mdui-icon material-icons">cancel</i></span>
            </div>
        </div>

        <a id="pre_btn" href="{{ url()->previous() }}" class="mdui-btn mdui-ripple mdui-m-b-1"><i
                style="position: relative; top: -1px;margin-right: 2px"
                class="mdui-icon material-icons">arrow_back</i>返回</a>

        @yield('content')

        <div class="mdui-typo mdui-m-t-5">
            <p class="mdui-typo-caption-opacity mdui-text-center">
                <a href="{{ route('contributes') }}">Contributors</a>
                <br />Light App Engine<br />
                Crafted with 💗 by iVampireSP.com<br />
            </p>
        </div>
    </div>


    <script>
        $.pjax.defaults.timeout = 1200
        $(document).pjax('a', '.pjax-container')

        $("#pre_btn").hide()
        $(document).on('pjax:clicked', function() {
            $("#pre_btn").fadeIn()
        })
        $(document).on("pjax:timeout", function(event) {
            mdui.snackbar({
                message: '与服务器连接时可能网络不太通畅',
                position: 'bottom'
            })

            event.preventDefault()
        })
    </script>
    @yield('script')
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
            $('#topic').show();
        } else {
            $('#topic').hide();
        }
    </script>
</body>

</html>
