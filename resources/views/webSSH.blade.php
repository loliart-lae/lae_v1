@extends('layouts.appNoMenu')

@section('title', 'WebSSH')

@section('content')
    <div class="mdui-textfield">
        <label class="mdui-textfield-label">IP 或者 主机名</label>
        <input class="mdui-textfield-input" id="sshHost" name="hostname" type="text" />
    </div>

    <div class="mdui-textfield">
        <label class="mdui-textfield-label">端口</label>
        <input class="mdui-textfield-input" id="sshPort" name="port" type="text" />
    </div>

    <div class="mdui-textfield">
        <label class="mdui-textfield-label">用户名</label>
        <input class="mdui-textfield-input" id="sshUser" name="username" type="text" />
    </div>

    <div class="mdui-textfield">
        <label class="mdui-textfield-label">密码</label>
        <input class="mdui-textfield-input" id="sshPwd" name="base64Pwd" type="password" />
    </div>
    <input type="hidden" id="realPwd" name="password" />


    <br />
    <button onclick="gotoWebSSH()" class="mdui-btn mdui-color-theme-accent mdui-ripple">连接</button>

    <script>
        $('body').removeClass('mdui-theme-layout-auto')
        $('body').addClass('mdui-theme-layout-dark')
        $('#sshPwd').keyup(function() {
            $('#realPwd').val(Base64.encode($('#sshPwd').val()))
        });

        function gotoWebSSH() {
            let hostname = $('#sshHost').val()
            let port = $('#sshPort').val()
            let username = $('#sshUser').val()
            let password = $('#realPwd').val()

            window.location =
                `https://webssh.lightart.top/?hostname=${hostname}&port=${port}&username=${username}&password=${password}`
        }
    </script>

@endsection
