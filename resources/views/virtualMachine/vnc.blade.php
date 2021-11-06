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
        }

    </style>
</head>

<body>
    <script>
        mdui.alert('如果无法成功登录VNC，请尝试刷新页面(不是用指示器刷新)。')
        // 设置 cookie
        $.ajax({
            type: "POST",
            xhrFields: {
                withCredentials: true
            },
            crossDomain: true,
            url: 'https://sd.zz.pve.awa.im:2083/cookie.php',
            data: {
                domain: "sd.zz.pve.awa.im",
                ticket: '{!! $data['ticket'] !!}'
            },
            success: function(data, status) {
                // 虽然不知道为什么要刷新，但是不刷新就是有问题
                let cookie_name = 'ae_vnc_{{ $data['vm_id'] }}'
                if (!$.cookie(cookie_name)) {
                    $.cookie(cookie_name, true);
                    window.location.reload();
                }
            }
        });
    </script>

    <iframe allowfullscreen
        src="https://{{ $data['host'] }}:8006/?console=kvm&novnc=1&vmid={{ $data['vm_id'] }}&node={{ $data['node'] }}"
        frameborder="0" scrolling="no"></iframe>

</body>

</html>
