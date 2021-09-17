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
    <script src="/js/mdui.js?bypasscache=202109150854"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-base64@3.7.1/base64.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery.pjax/1.9.6/jquery.pjax.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

</head>

<body class="mdui-theme-primary-blue mdui-theme-accent-blue mdui-theme-layout-auto">
    <div class="mdui-container-fluid">
        @yield('content')
    </div>

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
    </script>
</body>

</html>
