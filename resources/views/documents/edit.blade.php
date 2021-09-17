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
                <iframe src="{{ route('webSSH') }}" style="height: 85vh;width: 100%" frameborder="0"></iframe>
                @csrf
                <input type="text" name="title" style="background: transparent;
                width: 96%;
                border: none;
                color: white;outline:none;border-bottom: 1px solid white" id="title"
                    value="{{ $document->title }}" />
                <br />
                <input type="text" name="description" style="background: transparent;
                width: 96%;
                border: none;
                color: white;outline:none;border-bottom: 1px solid white" id="description"
                    value="{{ $document->description }}" />

                <input type="text" name="image_url" style="background: transparent;
                    width: 96%;
                    border: none;
                    color: white;outline:none;border-bottom: 1px solid white" id="image_url"
                    value="{{ $document->image_url }}" placeholder="首页图片地址" />

                自己可见：<input style="margin-top: 3px" type="radio" name="visibility" id="visibility" value="0"
                    @if (!$document->visibility) checked @endif required />
                公开：<input style="margin-top: 3px" type="radio" name="visibility" id="visibility" value="1"
                    @if ($document->visibility) checked @endif required />
                <p id="save_tip" style="padding:0;margin:0;text-align: center;margin-top: 3px">焦点位于编辑器时，Ctrl + S 才会保存
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
            editorTheme: "pastel-on-dark",

        })

        @if (session('status'))
            alert({{ session('status') }})
        @endif
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                alert({{ $error }})
            @endforeach
        @endif

        function save() {
            var date = new Date()
            var hour = date.getHours()
            var minutes = date.getMinutes()
            if (minutes < 10) {
                minutes = '0' + minutes
            }

            if (hour < 10) {
                hour = '0' + hour
            }

            if (minutes < 10) {
                minutes = '0' + minutes
            }

            var seconds = date.getSeconds()
            var format = `${hour}:${minutes}:${seconds}`
            $('#save_tip').text('正在保存...')

            // Update
            $.ajax({
                type: 'PUT',
                url: '{{ route('documents.update', $document->id) }}',
                data: {
                    'content': _mdeditor.getMarkdown(),
                    'title': $('#title').val(),
                    'description': $('#description').val(),
                    'visibility': $("input[name='visibility']:checked").val(),
                    'image_url': $('#image_url').val(),
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 'success') {
                        $('#save_tip').text('上次保存于 ' + format)
                    } else {
                        $('#save_tip').text('文档保存失败 ' + format)
                    }
                },
                error: function(data) {
                    $('#save_tip').text('保存失败。')
                }
            })
        }

        setInterval(save, 10000)

        document.onkeydown = (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                save();
                // 阻止默认事件
                e.preventDefault()
            }
        }
    </script>
</body>

</html>
