@extends('layouts.app')

@section('title', __('lxd.new'))

@section('content')
    <div class="mdui-typo-display-2">__('lxd.new')</div>

    <form method="post" action="{{ route('lxd.store') }}">
        @csrf

        <div class="mdui-tab mdui-tab-scrollable mdui-m-t-1" id="page-tab" mdui-tab>
            <a href="#choose-project" class="mdui-ripple">选择项目</a>
            <a href="#choose-server" class="mdui-ripple">选择服务器</a>
            <a href="#choose-image" class="mdui-ripple">选择镜像</a>
            <a href="#choose-template" class="mdui-ripple">选择容器模板</a>
            <a href="#choose-account" class="mdui-ripple">设定密码与名称</a>
        </div>

        <div id="choose-project">
            <x-choose-project-form />
        </div>

        <div id="choose-server">
            <div class="mdui-row mdui-p-t-4 mdui-p-b-2 mdui-p-l-1">
                <span class="mdui-typo-headline">选择地区服务器</span>
                <p class="mdui-typo-subheading">地区服务器影响着访问速度以及连通性，稳定性，以及基础价格。</p>
            </div>

            <div class="mdui-table-fluid">
                <table class="mdui-table mdui-table-hoverable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th>基础价格(积分/分钟)</th>
                            <th>转发价格(积分/分钟)</th>
                            <th>带宽限制</th>
                            <th>月预估</th>
                            <th>选择</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($servers) > 0)
                            @php($i = 1)
                            @foreach ($servers as $server)
                                <tr>
                                    <td nowrap>{{ $i++ }}</td>
                                    <td nowrap>{{ $server->name }}</td>
                                    <td nowrap>{{ $server->price }}</td>
                                    <td nowrap>{{ $server->forward_price }}</td>
                                    <td nowrap>{{ $server->network_limit }} Mbps</td>
                                    <td nowrap>
                                        {{ number_format(($server->price * 44640) / config('billing.exchange_rate'), 2) }}
                                        元 /
                                        月</td>

                                    <td>
                                        <label class="mdui-radio">
                                            <input type="radio" value="{{ $server->id }} " name="server_id"
                                                @if ($i == 2) checked @endif required />
                                            <i class="mdui-radio-icon"></i>

                                        </label>
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="mdui-text-center">服务器均已售罄</td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>

        <div id="choose-image">
            <div class="mdui-row mdui-p-t-4 mdui-p-b-2 mdui-p-l-1">
                <span class="mdui-typo-headline">选择镜像</span>
                <p class="mdui-typo-subheading">不同镜像拥有着不同操作系统以及操作方式。</p>
            </div>
            <div class="mdui-table-fluid">
                <table class="mdui-table mdui-table-hoverable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>镜像</th>
                            <th>源</th>
                            <th>选择</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @foreach ($images as $image)
                            <tr>
                                <td nowrap>{{ $i++ }}</td>
                                <td nowrap>{{ $image->name }}</td>
                                <td nowrap>{{ $image->image }}</td>

                                <td>
                                    <label class="mdui-radio">
                                        <input type="radio" value="{{ $image->id }}" name="image_id"
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

        <div id="choose-template">
            <div class="mdui-row mdui-p-t-4 mdui-p-b-2 mdui-p-l-1">
                <span class="mdui-typo-headline">选择容器模板</span>
                <p class="mdui-typo-subheading">容器模板影响着计费，计费每 1 分钟进行一次。</p>
            </div>
            <div class="mdui-table-fluid">
                <table class="mdui-table mdui-table-hoverable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th>CPU</th>
                            <th>内存</th>
                            <th>硬盘</th>
                            <th>积分/分钟</th>
                            <th>月预估</th>
                            <th>选择</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @foreach ($templates as $template)
                            <tr>
                                <td nowrap>{{ $i++ }}</td>
                                <td nowrap>{{ $template->name }}</td>
                                <td nowrap>{{ $template->cpu }}</td>


                                <td nowrap>{{ $template->mem }} M</td>
                                <td nowrap>{{ $template->disk }} G</td>
                                <td nowrap>{{ $template->price }}</td>
                                <td nowrap>
                                    {{ number_format(($template->price * 44640) / config('billing.exchange_rate'), 2) }}
                                    元 /
                                    月
                                </td>

                                <td>
                                    <label class="mdui-radio">
                                        <input type="radio" value="{{ $template->id }}" name="template_id"
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

        <div id="choose-account">
            <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
                <span class="mdui-typo-headline">Root 密码</span>
                <p>只允许字母、数字，短破折号（-）和下划线（_），可到容器内再次修改。</p>
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">密码</label>
                    <input class="mdui-textfield-input" type="password" name="password" value="{{ old('password') }}"
                        required />
                </div>
            </div>

            <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
                <span class="mdui-typo-headline">最后，设置容器名称</span>
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">名称</label>
                    <input class="mdui-textfield-input" type="text" name="name" value="{{ old('name') }}" required />
                </div>
            </div>

            <div class="mdui-row mdui-p-y-2">
                <button type="submit"
                    class="mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple umami--click--lxd-new">新建</button>
            </div>

            <div class="mdui-typo" style="text-align: right;margin-top: 10px"><small class="mdui-clearfix">
                    注意：每分钟价格 = 地区服务器基础价格 + 容器模板价格 + 端口转发。<br />
                    ssh 默认用户名为 root，并且是无特权容器，不支持 Docker。<br />
                    带宽均为共享带宽，如带宽有调整，将会即时生效。<br />
                    如果你的容器配置和模板配置不符，可对容器模板进行升配/降配，再修改回去即可刷新。<br />
                    禁止将容器用于挖矿、攻击（DDOS，CC）、QEMU、网络代理等。如有发现，将直接删除用户。
                </small></div>
        </div>

    </form>
@endsection
