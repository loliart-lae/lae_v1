@extends('layouts.app')

@section('title', '新建 CyberPanel 虚拟主机')

@section('content')
    <div class="mdui-typo-display-2">新建 CyberPanel 虚拟主机</div>

    <form method="post" action="{{ route('cyberPanel.store') }}">
        @csrf

        <div class="mdui-tab mdui-tab-scrollable" mdui-tab>
            <a href="#choose-project" class="mdui-ripple">选择项目</a>
            <a href="#choose-package" class="mdui-ripple">选择套餐包</a>
            <a href="#choose-name" class="mdui-ripple">设定名称</a>
        </div>

        <div id="choose-project">
            <x-choose-project-form />
        </div>

        <div id="choose-package">
            <div class="mdui-row mdui-p-t-4 mdui-p-b-2 mdui-p-l-1">
                <span class="mdui-typo-headline">选择套餐包</span>
            </div>
            <div class="mdui-table-fluid">
                <table class="mdui-table mdui-table-hoverable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th>域名数量</th>
                            <th>磁盘空间</th>
                            <th>流量限制</th>
                            <th>FTP 用户数量</th>
                            <th>数据库数量</th>
                            <th>邮箱数量</th>
                            <th>带宽</th>
                            <th>服务器</th>
                            <th>月预估</th>
                            <th>积分/分钟</th>
                            <th>选择</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @foreach ($packages as $package)
                            <tr>
                                <td nowrap>{{ $i++ }}</td>
                                <td nowrap>{{ $package->display_name }}</td>
                                <td nowrap>{{ $package->domains }} 个</td>
                                <td nowrap>{{ $package->disks }} M</td>
                                <td nowrap>{{ $package->bandwidth }} M</td>
                                <td nowrap>{{ $package->ftp_users }} 个</td>
                                <td nowrap>{{ $package->databases }} 个</td>
                                <td nowrap>{{ $package->mails }} 个</td>
                                <td nowrap>{{ $package->server->network_limit }} Mbps</td>
                                <td nowrap>{{ $package->server->name }}</td>
                                <td nowrap>
                                    {{ number_format(($package->price * 44640) / config('billing.exchange_rate'), 2) }} 元
                                    / 月
                                </td>
                                <td nowrap>{{ $package->price }}</td>

                                <td>
                                    <label class="mdui-radio">
                                        <input type="radio" value="{{ $package->id }}" name="package_id"
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

        <div id="choose-name">
            <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
                <span class="mdui-typo-headline">输入你的域名</span>
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">域名</label>
                    <input class="mdui-textfield-input" type="text" name="domain" value="{{ old('domain') }}" required />
                </div>
            </div>

            <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
                <span class="mdui-typo-headline">你想给这个虚拟主机起什么名字？</span>
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">名称</label>
                    <input class="mdui-textfield-input" type="text" name="name" value="{{ old('name') }}" required />
                </div>
            </div>

            <div class="mdui-row mdui-p-y-2">
                <button type="submit"
                    class="mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple umami--click--cyberpanel-new">新建</button>
            </div>
            <div class="mdui-typo" style="text-align: right;margin-top: 10px"><small class="mdui-clearfix">
                    带宽均为共享带宽，如带宽有调整，将会即时生效。<br />
                    禁止用于违法犯罪用途，否则直接删除账号并不保留任何数据。
                </small></div>
        </div>


    </form>
@endsection
