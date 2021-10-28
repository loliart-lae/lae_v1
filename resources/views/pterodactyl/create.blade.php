@extends('layouts.app')

@section('title', '新建 游戏服务器')

@section('content')
    <div class="mdui-typo-display-2">新建 游戏服务器</div>

    <form method="post" action="{{ route('gameServer.store') }}">
        @csrf
        <x-choose-project-form />

        <div class="mdui-row mdui-p-t-4 mdui-p-b-2 mdui-p-l-1">
            <span class="mdui-typo-headline">选择镜像</span>
            {{-- <p class="mdui-typo-subheading"></p> --}}
        </div>
        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>镜像</th>
                        <th>选择</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 1)
                    @foreach ($images as $image)
                        @php($i++)
                        <tr>
                            <td nowrap>{{ $image->id }}</td>
                            <td nowrap>{{ $image->name }}</td>

                            <td>
                                <label class="mdui-radio">
                                    <input type="radio" value="{{ $image->id }}" name="image_id" @if ($i == 2) checked @endif
                                        required />
                                    <i class="mdui-radio-icon"></i>

                                </label>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mdui-row mdui-p-t-4 mdui-p-b-2 mdui-p-l-1">
            <span class="mdui-typo-headline">选择配置模板</span>
            <p class="mdui-typo-subheading">配置模板影响着计费以及服务器性能。</p>
        </div>
        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>CPU限制</th>
                        <th>内存大小</th>
                        <th>虚拟内存</th>
                        <th>硬盘空间</th>
                        <th>块IO</th>
                        <th>数据库数量</th>
                        <th>备份数量</th>
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
                            <td nowrap>{{ $template->cpu_limit }} %</td>
                            <td nowrap>{{ $template->memory }} M</td>
                            <td nowrap>{{ $template->swap }} M</td>
                            <td nowrap>{{ $template->disk_space }} M</td>
                            <td nowrap>{{ $template->io }}</td>
                            <td nowrap>{{ $template->databases }}</td>
                            <td nowrap>{{ $template->backups }}</td>
                            <td nowrap>{{ $template->price }}</td>
                            <td nowrap>
                                {{ number_format(($template->price * 44640) / config('billing.exchange_rate'), 2) }} 元 /
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


        <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
            <span class="mdui-typo-headline">给这个服务器想一个名字吧</span>
            <div class="mdui-textfield mdui-textfield-floating-label">
                <label class="mdui-textfield-label">名称</label>
                <input class="mdui-textfield-input" type="text" name="name" value="{{ old('name') }}" required />
            </div>
        </div>


        <div class="mdui-row mdui-p-y-2">
            <button type="submit"
                class="mdui-m-l-1 mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple umami--click--new-game-server">新建</button>
        </div>
        </div>
    </form>

    <div class="mdui-typo" style="text-align: right;margin-top: 10px"><small class="mdui-clearfix">
            注意：节点由服务器随机选择，无法更改。<br />
            一些配置参数可以在创建服务器后修改，比如修改服务器软件版本。<br />
        </small></div>

@endsection
