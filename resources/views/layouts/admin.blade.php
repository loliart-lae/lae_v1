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
            <a href="{{ route('admin.index') }}" class="mdui-ripple mdui-ripple-white">总览</a>
            <a href="{{ route('users.index') }}" class="mdui-ripple mdui-ripple-white">用户管理</a>
            <a href="{{ route('balance.index') }}" class="mdui-ripple mdui-ripple-white">应用容器</a>
            <a href="{{ route('balance.index') }}" class="mdui-ripple mdui-ripple-white">Windows 远程桌面</a>
            <a href="{{ route('balance.index') }}" class="mdui-ripple mdui-ripple-white">穿透隧道</a>
            <a href="{{ route('balance.index') }}" class="mdui-ripple mdui-ripple-white">快捷访问</a>
            <a href="{{ route('balance.index') }}" class="mdui-ripple mdui-ripple-white">文档中心</a>
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
    <script>
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        })
        $(document).pjax('a', '.pjax-container')
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
