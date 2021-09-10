@extends('layouts.app')

@section('title', '新建容器')

@section('content')
    <h1 class="mdui-text-color-theme">新建容器</h1>
    <p>在当前项目中新建一个容器。新建容器系统当前只支持Ubuntu 20.04。</p>
    <br />
    <form method="post" action="{{ route('lxd.store') }}">
        @csrf
        <input type="hidden" name="project_id" value="{{ Request::route('project_id') }}" />
        <span class="mdui-typo-headline">选择地区服务器</span>&nbsp;<span
            class="mdui-typo-subheading">地区服务器影响着访问速度已经连通性,稳定性,以及基础价格。</span>
        <br />
        <br />

        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
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
                            <td>{{ $server->price }}</td>

                            <td>
                                <label class="mdui-radio">
                                    <input type="radio" value="{{ $server->id }}" name="server_id" @if ($i == 2) checked @endif />
                                    <i class="mdui-radio-icon"></i>
                                    选择
                                </label>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <br />
        <br />
        <br />
        <span class="mdui-typo-headline">选择容器模板</span>&nbsp;<span class="mdui-typo-subheading">容器模板影响着计费。计费每 1 分钟进行一次。</span>
        <br />
        <br />
        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>CPU</th>
                        <th>内存</th>
                        <th>价格</th>
                        <th>选择</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 1)
                    @foreach ($templates as $template)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $template->name }}</td>
                            <td>{{ $template->cpu }}</td>


                            <td>{{ $template->mem }}</td>
                            <td>{{ $template->price }}</td>
                            <td>
                                <label class="mdui-radio">
                                    <input type="radio" value="{{ $template->id }}" name="template_id" @if ($i == 2) checked @endif />
                                    <i class="mdui-radio-icon"></i>
                                    选择
                                </label>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <br /> <br />
        <span class="mdui-typo-headline">最后，设置容器名称</span>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">名称</label>
            <input class="mdui-textfield-input" type="text" name="name" />
        </div>

        <br /> <br />

        <button type="submit" class="mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple">新建</button>

        <br /><br />
        <div class="mdui-typo" style="text-align: right;margin-top: 10px"><small class="mdui-clearfix">注意：每分钟价格 = 地区服务器基础价格 + 容器模板价格 + 端口转发</small></div>
    </form>
@endsection
