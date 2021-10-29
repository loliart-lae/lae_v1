@extends('layouts.app')

@section('title', '新建 静态托管')

@section('content')
    <div class="mdui-typo-display-2">新建 静态托管</div>

    <form method="post" id="f-buy-shared-windows" action="{{ route('staticPage.store') }}">
        @csrf

        <div class="mdui-tab mdui-tab-scrollable mdui-m-t-1" mdui-tab>
            <a href="#choose-project" class="mdui-ripple">选择项目</a>
            <a href="#choose-server" class="mdui-ripple">选择服务器</a>
            <a href="#choose-domain" class="mdui-ripple">设定域名</a>
            <a href="#choose-name" class="mdui-ripple">设定名称</a>
        </div>

        <div id="choose-project">
            <x-choose-project-form />
        </div>

        <div id="choose-server">
            <div class="mdui-row mdui-p-t-4 mdui-p-b-2 mdui-p-l-1">
                <span class="mdui-typo-headline">选择 静态托管 服务器</span>
                <p class="mdui-typo-subheading">服务器地区影响着访问速度以及连通性，稳定性，以及基础价格。</p>
            </div>

            <div class="mdui-table-fluid">
                <table class="mdui-table mdui-table-hoverable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th>带宽</th>
                            <th>解析地址</th>
                            <th>每 Mb 价格</th>
                            <th>选择</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @foreach ($servers as $server)
                            <tr>
                                <td nowrap>{{ $i++ }}</td>
                                <td nowrap>{{ $server->name }}</td>
                                <td nowrap>{{ $server->network_limit }} Mbps</td>
                                <td nowrap>{{ $server->domain }}</td>
                                <td nowrap>{{ $server->price }}</td>
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
        </div>

        <div id="choose-domain">
            <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
                <span class="mdui-typo-headline">绑定域名</span>
                <p>绑定后，将自动申请签发SSL证书，需要解析。</p>
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">域名</label>
                    <input class="mdui-textfield-input" type="text" name="domain" value="{{ old('domain') }}" required />
                </div>
            </div>
        </div>

        <div id="choose-name">
            <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
                <span class="mdui-typo-headline">主机名称</span>
                <p>用于标识主机。</p>
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">名称</label>
                    <input class="mdui-textfield-input" type="text" name="name" value="{{ old('name') }}" required />
                </div>
            </div>

            <div class="mdui-row mdui-p-y-2">
                <button type="submit"
                    class="mdui-m-l-1 mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple umami--click--new-staticPage">新建</button>
            </div>
        </div>

    </form>

    <div class="mdui-typo" style="text-align: right;margin-top: 10px"><small class="mdui-clearfix">
            当前免费额度为 10Mb，超过后将按照 每Mb * 每 Mb 价格 收费 <br />
            请不要用于违法用途，如有发现将直接删除所有数据并封禁 LAE 账号。<br />
            开设空间后将自动申请 SSL，如果失败将无法访问。<br />
        </small></div>

    </div>
@endsection
