<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no" />

    <meta name="theme-color" content="#2196F3" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{!! csrf_token() !!}" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdui@1.0.1/dist/css/mdui.min.css"
        integrity="sha384-cLRrMq39HOZdvE0j6yBojO4+1PrHfB7a9l5qLcmRm/fiWXYY+CndJPmyu5FV/9Tw" crossorigin="anonymous" />
    {!! editor_css() !!}

    <title>{{ $document->title }} - {{ config('app.name') }}</title>
    <!-- JavaScripts -->
    <script src="/js/mdui.js?bypasscache=202109150854"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-base64@3.7.1/base64.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery.pjax/1.9.6/jquery.pjax.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

</head>

<body class="mdui-theme-primary-blue mdui-theme-accent-blue mdui-theme-layout-auto">
    <div class="mdui-container-fluid pjax-container">
        <div class="mdui-row" style="display: none" id="editor">
            <div class="mdui-col-xs-12 mdui-col-sm-8">
                <div id="mdeditor">
                    <textarea class="form-control" name="content"
                        style="display:none;">{{ $document->content }}</textarea>
                </div>
            </div>
            <div class="mdui-col-xs-6 mdui-col-sm-4">
                <iframe src="{{ route('webSSH') }}" style="height: 91vh;width: 100%" frameborder="0"></iframe>
                <p style="margin:0;padding:0">
                    {{ $document->title }} <br /> {{ $document->description }}
                    <br />
                    @csrf
                    <label for="useful">觉得这份文档对您有用吗？</label>
                    <input type="checkbox" id="useful" onchange="toggleUseful()" @if($is_liked ?? 0) checked @endif  />
                </p>
            </div>
        </div>


    </div>

    {!! editor_js() !!}
    <script>
        $('body').removeClass('mdui-theme-layout-auto')
        $('body').addClass('mdui-theme-layout-dark')
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        })
        $('#editor').fadeIn()
        var _mdeditor
        //修正emoji图片错误
        editormd.emoji = {
            path: "//staticfile.qnssl.com/emoji-cheat-sheet/1.0.0/",
            ext: ".png"
        };
        _mdeditor = editormd({
            id: "mdeditor",
            width: "100%",
            height: '98vh',
            saveHTMLToTextarea: true,
            emoji: true,
            taskList: true,
            tex: true,
            toc: true,
            tocm: false,
            codeFold: true,
            flowChart: false,
            sequenceDiagram: false,
            path: "/vendor/editor.md/lib/",
            imageUpload: true,
            imageFormats: ["jpg", "gif", "png"],
            imageUploadURL: "/laravel-editor-md/upload/picture?_token={{ csrf_token() }}&from=laravel-editor-md",
            theme: "dark",
            previewTheme: "dark",
            editorTheme: "pastel-on-dark"
        })

        function toggleUseful() {
            $.ajax({
                type: 'PUT',
                url: '{{ route('documents.like', $document->id) }}',
                data: {
                    'content': _mdeditor.getMarkdown(),
                    'title': $('#title').val(),
                    'description': $('#description').val(),
                    'visibility': $("input[name='visibility']:checked").val(),
                    'image_url': $('#image_url').val(),
                },
                dataType: 'json'
            })
        }

        @if (session('status'))
            alert({{ session('status') }})
        @endif
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                alert({{ $error }})
            @endforeach
        @endif
    </script>
</body>

</html>
