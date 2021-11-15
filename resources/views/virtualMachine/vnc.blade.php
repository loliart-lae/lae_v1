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
            background: rgb(36, 36, 36);
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
                set()
            },
            error: function() {
                set()
            }

        });

        function set() {
            // 虽然不知道为什么要刷新，但是不刷新就是有问题
            let cookie_name = 'ae_vnc_{{ $data['id'] }}'
            if (!$.cookie(cookie_name)) {
                $.cookie(cookie_name, '1');
                window.location.reload();
            }
        }
    </script>

    <iframe allowfullscreen
        src="https://{{ $data['host'] }}:8006/?console=kvm&novnc=1&vmid={{ $data['vm_id'] }}&node={{ $data['node'] }}"
        frameborder="0" scrolling="no"></iframe>

</body>

</html>
