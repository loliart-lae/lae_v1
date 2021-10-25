@extends('layouts.app')

@section('title', '新建 Easypanel 虚拟主机')

@section('content')
    <div class="mdui-typo-display-2">新建 EasyPanel 虚拟主机</div>

    <form method="post" action="{{ route('easyPanel.store') }}">
        @csrf
        <x-choose-project-form />

        <x-choose-server-form type="easypanel" />

        <div class="mdui-row mdui-p-t-4 mdui-p-b-2 mdui-p-l-1">
            <span class="mdui-typo-headline">选择配置模板</span>
            <p class="mdui-typo-subheading">配置模板影响着计费以及性能，计费每 1 分钟进行一次。</p>
        </div>
        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>类型</th>
                        <th>空间容量</th>
                        <th>数据库容量</th>
                        <th>速度限制</th>
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
                            <td nowrap>
                                @if ($template->is_cdn)
                                    CDN
                                @else
                                    虚拟主机
                                @endif
                            </td>
                            <td nowrap>{{ $template->web_quota }} M</td>


                            <td nowrap>{{ $template->db_quota }} M</td>
                            <td nowrap>{{ $template->speed_limit }} Mbps</td>
                            <td nowrap>{{ $template->price }}</td>
                            <td nowrap>
                                {{ number_format(($template->price * 44640) / config('billing.exchange_rate'), 2) }} 元 / 月
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

        <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
            <span class="mdui-typo-headline">你想给这个虚拟主机起什么名字？</span>
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
                带宽均为共享带宽，如带宽有调整，将会即时生效。<br />
                禁止用于违法犯罪用途，否则直接删除账号并不保留任何数据。
            </small></div>
    </form>
@endsection
