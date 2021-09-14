@extends('layouts.app')

@section('title', '新建 Tunnel 隧道')

@section('content')
    <h1 class="mdui-text-color-theme">新建 Tunnel 隧道</h1>
    <p>在选定的项目中新建 Tunnel 隧道</p>
    <br />
    <form method="post" action="{{ route('tunnels.store') }}">
        @csrf
        <span class="mdui-typo-headline">选择项目</span>
        <br />
        <br />
        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>项目积分</th>
                        <th>选择</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 1)
                    @foreach ($projects as $project)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $project->project->name }}</td>
                            <td>{{ $project->project->balance }}</td>

                            <td>
                                <label class="mdui-radio">
                                    <input type="radio" value="{{ $project->project->id }}" name="project_id"
                                        @if ($i == 2) checked @endif required />
                                    <i class="mdui-radio-icon"></i>

                                </label>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <br />
        <br />
        <span class="mdui-typo-headline">选择 Tunnel 隧道 服务器</span>
        <p class="mdui-typo-subheading">Tunnel 隧道服务器影响着访问速度以及连通性，稳定性，以及基础价格。</p>
        <br />

        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>共享带宽</th>
                        <th>基础价格</th>
                        <th>选择</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 1)
                    @foreach ($servers as $server)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $server->name }}</td>
                            <td>{{ $server->network_limit }} Mbps</td>
                            <td>{{ $server->price }}</td>


                            <td>
                                <label class="mdui-radio">
                                    <input type="radio" value="{{ $server->id }} " name="server_id"
                                        @if ($i == 2) checked @endif required />
                                    <i class="mdui-radio-icon"></i>

                                </label>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <br /> <br />
        <span class="mdui-typo-headline">隧道的名称</span>
        <p>只允许字母、数字，短破折号（-）和下划线（_）,至少 3 位，最多 15 位。该名称用于标识。</p>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">隧道名称</label>
            <input class="mdui-textfield-input" type="text" name="name" value="{{ old('name') }}" required />
        </div>

        <br /> <br />
        <span class="mdui-typo-headline">隧道协议</span>
        <p>根据您的使用场景以及应用选择。</p>
        <select name="protocol" class="mdui-select" mdui-select required>
            <option value="http">HTTP - 适合不加密，明文传输的网页浏览业务。</option>
            <option value="https">HTTPS - 适合加密，对安全性较强的业务。</option>
            <option value="tcp">TCP - 即时通讯或者游戏等对可靠性要求较高的业务。</option>
            <option value="udp">UDP - 适合数据可靠性较低的业务。</option>
        </select>
        <br /> <br />
        <br /> <br />

        <div class="mdui-row">
            <div class="mdui-col-xs-6">
                <span class="mdui-typo-headline">内网地址</span>
                <p>被映射主机的地址，比如 127.0.0.1:80</p>
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">内网地址</label>
                    <input class="mdui-textfield-input" type="text" name="local_address" value="{{ old('local_address') }}"
                        required />
                </div>

                <span class="mdui-typo-headline">公网端口</span>
                <p>将内网地址的端口映射为，如果是 HTTP/HTTPS 协议，则该项可以不填写。</p>
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">公网端口</label>
                    <input class="mdui-textfield-input" type="text" name="remote_port" value="{{ old('remote_port') }}" />
                </div>
            </div>
            <div class="mdui-col-xs-6">
                <span class="mdui-typo-headline">域名</span>
                <p>仅在 HTTP 与 HTTPS 中生效。</p>
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">域名</label>
                    <input class="mdui-textfield-input" type="text" name="custom_domain" value="{{ old('custom_domain') }}" />
                </div>
            </div>
        </div>

        <br /> <br />

        <button type="submit" class="mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple">新建</button>

        <br /><br />
        <div class="mdui-typo" style="text-align: right;margin-top: 10px"><small class="mdui-clearfix">注意：每分钟价格 =
                地区服务器基础价格</small></div>
    </form>
@endsection
