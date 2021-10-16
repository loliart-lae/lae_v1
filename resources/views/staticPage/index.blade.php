@extends('layouts.app')

@section('title', '静态主机托管')

@section('content')
    <div class="mdui-typo-display-2">静态主机托管</div>

    <div class="mdui-row mdui-p-b-2 mdui-p-l-1">
        <a href="{{ route('staticPage.create') }}" class="mdui-btn mdui-color-theme-accent mdui-ripple">新建 静态托管</a>
    </div>

    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>内部 ID</th>
                    <th>名称</th>
                    <th>域名</th>
                    <th>FTP 用户名与密码</th>
                    <th>存储用量</th>
                    <th>服务器</th>
                    <th>连接与解析</th>
                    <th>积分/分钟</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody class="mdui-typo">
                @php($i = 1)
                @php($project_id = 0)
                @foreach ($staticPages as $staticPage)
                    @if ($staticPage->project->id != $project_id)
                        @php($project_id = $staticPage->project->id)
                        <tr>
                            <td colspan="12" class="mdui-text-center">
                                <a
                                    href="{{ route('projects.show', $staticPage->project->id) }}">{{ $staticPage->project->name }}</a>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td nowrap="nowrap">{{ $i++ }}</td>
                        <td nowrap="nowrap">{{ $staticPage->id }}</td>
                        <td nowrap="nowrap">{{ $staticPage->name }}</td>
                        <td nowrap="nowrap"><a href="https://{{ $staticPage->domain }}"
                                target="_blank">{{ $staticPage->domain }}</a></td>
                        <td nowrap="nowrap"><a
                                onclick="mdui.alert('FTP 用户名: ' + '{{ $staticPage->ftp_username }}' + '<br /> FTP 密码:' + '{{ $staticPage->ftp_password }}' + '<br /><br />' + '连接地址与“连接与解析”相同。')">显示</a>
                        </td>
                        <td nowrap="nowrap">{{ $staticPage->used_disk }} M</td>
                        <td nowrap="nowrap">{{ $staticPage->server->name }}</td>
                        <td nowrap="nowrap">{{ $staticPage->server->domain }}</td>
                        <td nowrap="nowrap">
                            @if ($staticPage->used_disk < 10)
                                0
                            @else
                                {{ $staticPage->used_disk * $staticPage->server->price }}
                            @endif
                        </td>

                        <td nowrap="nowrap">
                            @if ($staticPage->status == 'active')
                                <a href="#"
                                    onclick="if (confirm('删除后，这个站点的数据将会全部丢失，并且网站将无法访问。')) { $('#f-{{ $i }}').submit() }">删除</a>|<a href="#"
                                    onclick="if (confirm('备份时间依据站点大小而定。')) { $('#f-bak-{{ $i }}').submit() }">备份</a>
                            @elseif ($staticPage->status == 'pending')
                                <div class="mdui-progress">
                                    <div class="mdui-progress-indeterminate"></div>
                                </div>
                            @elseif ($staticPage->status == 'backup')
                                正在备份
                            @else
                                {{ $staticPage->status }}
                            @endif
                        </td>

                    </tr>
                    <form id="f-{{ $i }}" method="post"
                        action="{{ route('staticPage.destroy', $staticPage->id) }}">@csrf
                        @method('DELETE')</form>
                    <form id="f-bak-{{ $i }}" method="post"
                        action="{{ route('staticPage.backup', $staticPage->id) }}">@csrf</form>
                @endforeach
                @if ($i > 10)
                    <tr>
                        <td colspan="12" class="mdui-text-center">
                            <a href="{{ route('staticPage.create') }}">这个其实也可以搞Hexo之类</a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

@endsection
