@extends('layouts.app')

@section('title', '新建 静态托管')

@section('content')
    <div class="mdui-typo-display-2">新建 静态托管</div>

    <p>在选定的项目中新建 共享的 Windows 远程桌面账号。</p>
    <br />
    <form method="post" id="f-buy-shared-windows" action="{{ route('staticPage.store') }}">
        @csrf
        <x-choose-project-form />

        <br />
        <br />
        <span class="mdui-typo-headline">选择 静态托管 服务器</span>
        <p class="mdui-typo-subheading">服务器地区影响着访问速度以及连通性，稳定性，以及基础价格。</p>
        <br />

        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>带宽</th>
                        <th>选择</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 1)
                    @foreach ($servers as $server)
                        <tr>
                            <td nowrap="nowrap">{{ $i++ }}</td>
                            <td nowrap="nowrap">{{ $server->name }}</td>
                            <td nowrap="nowrap">{{ $server->network_limit }} Mbps</td>
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
        <span class="mdui-typo-headline">主机名称</span>
        <p>用于标识主机</p>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">名称</label>
            <input class="mdui-textfield-input" type="text" name="name" value="{{ old('name') }}" required />
        </div>

        <br /> <br />
        <span class="mdui-typo-headline">绑定域名</span>
        <p>绑定后，将自动申请签发SSL证书，需要解析。</p>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">域名</label>
            <input class="mdui-textfield-input" type="text" name="domain" value="{{ old('domain') }}" required />
        </div>

        <br /> <br />

        <button type="submit" class="mdui-m-l-1 mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple">新建</button>
        <span class="mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple"
            mdui-dialog="{target: '#sub-dialog'}">必看(使用须知)</span>


        <div class="mdui-dialog" id="sub-dialog">
            <div class="mdui-dialog-title">请仔细阅读</div>
            <div class="mdui-dialog-content">1. 请勿用于违法用途<br />
            <div class="mdui-dialog-actions">
                <button class="mdui-btn mdui-ripple" mdui-dialog-close>新建</button>
            </div>
        </div>
    </form>

    <br /><br />
    <div class="mdui-typo" style="text-align: right;margin-top: 10px"><small class="mdui-clearfix"></small></div>

    </div>
@endsection