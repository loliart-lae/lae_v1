@extends('layouts.app')

@section('title', 'VNC')

@section('content')

    <script>
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

    <div class="mdui-typo-display-1">{{ $data['name'] }}</div>

    <iframe class="mdui-shadow-24" style="width: 100%; height: 69vh; border-radius:  5px;margin-top:3px" allowfullscreen
        src="https://{{ $data['host'] }}:8006/?console=kvm&novnc=1&vmid={{ $data['vm_id'] }}&node={{ $data['node'] }}"
        frameborder="0" scrolling="no" width="1024px" height="1024px"></iframe>

@endsection
