<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VNC</title>

    <style>
        html,
        body,
        iframe {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background: rgb(26, 26, 26);
        }

    </style>
    <script src="https://cdn.bootcss.com/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
</head>

<body>
    <script>
        // 设置 cookie
        $.ajax({
            type: "POST",
            xhrFields: {
                withCredentials: true
            },
            crossDomain: true,
            url: '{{ $data['domain'] }}',
            data: {
                domain: "sd.zz.pve.awa.im",
                ticket: {!! '"' . $data['ticket'] . '",' !!}
            },
            success: function() {
                let html = `<iframe allowfullscreen
        src="https://{{ $data['host'] }}:8006/?console=kvm&novnc=1&vmid={{ $data['vm_id'] }}&node={{ $data['node'] }}"
        frameborder="0" scrolling="no"></iframe>`
                $('body').append(html)

            },
            error: function() {
                document.write(`<h1 style="color: white;text-align: center;">暂时无法打开VNC</h1>`)
            }

        });
    </script>

</body>

</html>
