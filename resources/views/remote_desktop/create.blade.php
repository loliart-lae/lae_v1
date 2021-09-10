@extends('layouts.app')

@section('title', '新建共享的Windows 远程桌面账户')

@section('content')
    <h1 class="mdui-text-color-theme">新建共享的 Windows 远程桌面账号</h1>
    <p>在选定的项目中新建 共享的 Windows 远程桌面账号。</p>
    <br />
    <form method="post" action="{{ route('remote_desktop.store') }}">
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
                                    <input type="radio" value="{{ $project->project->id }}" name="project_id" @if ($i == 2) checked @endif required />
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
        <span class="mdui-typo-headline">选择 共享的 Windows 远程桌面 服务器</span>
        <p class="mdui-typo-subheading">共享的 Windows 远程桌面 服务器影响着访问速度以及连通性，稳定性，以及基础价格。</p>
        <br />

        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>CPU</th>
                        <th>内存</th>
                        <th>带宽</th>
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
                            <td>{{ $server->cpu }}</td>
                            <td>{{ $server->mem }}</td>
                            <td>{{ $server->network_limit }} Mbps</td>
                            <td>{{ $server->price }}</td>


                            <td>
                                <label class="mdui-radio">
                                    <input type="radio" value="{{ $server->id }} "name="server_id" @if ($i == 2) checked @endif required />
                                    <i class="mdui-radio-icon"></i>

                                </label>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <br /> <br />
        <span class="mdui-typo-headline">用户名</span>
        <p>只允许字母、数字，短破折号（-）和下划线（_）,至少3位，最多15位</p>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">用户名</label>
            <input class="mdui-textfield-input" type="text" name="username" value="{{ old('username') }}" required />
        </div>

        <br /> <br />
        <span class="mdui-typo-headline">密码</span>
        <p>只允许字母、数字，短破折号（-）和下划线（_）。</p>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">密码</label>
            <input class="mdui-textfield-input" type="password" name="password" value="{{ old('password') }}" required />
        </div>

        <br /> <br />

        <button type="submit" class="mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple">新建</button>

        <br /><br />
        <div class="mdui-typo" style="text-align: right;margin-top: 10px"><small class="mdui-clearfix">注意：每分钟价格 = 地区服务器基础价格<br />共享的 Windows 远程桌面 没有管理员账号，如需安装软件请前往社区中发帖。一些软件可用绿色版免安装。</small></div>
    </form>
@endsection
