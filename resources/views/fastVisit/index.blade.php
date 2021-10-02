@extends('layouts.app')

@section('title', '快捷访问')

@section('content')
    <div class="mdui-typo-display-2">快捷访问</div>
    <p>快捷访问可以设置访问网址时的跳转的URI。</p>

    <a class="mdui-btn mdui-color-theme-accent mdui-ripple" href="{{ route('fastVisit.create') }}">新建入口</a>
    <br />
    <br />
    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>内部 ID</th>
                    <th>名称</th>
                    <th>短语(点击复制)</th>
                    <th>域</th>
                    <th>目标地址</th>
                    <th>广告状态</th>
                    <th>有效访问量</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody class="mdui-typo">
                @php($i = 1)
                @php($project_id = 0)
                @foreach ($fastVisits as $fastVisit)
                    @if ($fastVisit->project->id != $project_id)
                        @php($project_id = $fastVisit->project->id)
                        <tr>
                            <td colspan="9" class="mdui-text-center">
                                <a
                                    href="{{ route('projects.show', $fastVisit->project->id) }}">{{ $fastVisit->project->name }}</a>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td nowrap="nowrap">{{ $i++ }}</td>
                        <td nowrap="nowrap">{{ $fastVisit->id }}</td>
                        <td nowrap="nowrap">{{ $fastVisit->name }}</td>
                        <td nowrap="nowrap"><a class="can_copy"
                                data-clipboard-text="https://{{ $fastVisit->domain->domain }}/v/{{ $fastVisit->slug }}">{{ $fastVisit->slug }}</a>
                        </td>
                        <td nowrap>{{ $fastVisit->domain->domain }}</td>
                        <td nowrap="nowrap">{{ $fastVisit->uri }}</td>
                        <td nowrap="nowrap" style="cursor: pointer"
                            onclick="toggleAd({{ $fastVisit->id }},{{ $fastVisit->project->id }})">
                            @if ($fastVisit->show_ad)
                                <a class="ad_{{ $fastVisit->id }}">已启用</a>
                            @else
                                <a class="ad_{{ $fastVisit->id }}">未启用</a>
                            @endif
                        </td>
                        <td nowrap="nowrap">{{ $fastVisit->times }}</td>
                        <td>
                            <a onclick="if (confirm('删除后，这个访问入口将无法使用。')) { $('#f-{{ $i }}').submit() }">删除</a>
                            <form id="f-{{ $i }}" method="post"
                                action="{{ route('fastVisit.destroy', $fastVisit->id) }}">
                                @csrf
                                @method('DELETE')</form>
                        </td>


                    </tr>
                @endforeach
                @if ($i > 10)
                    <tr>
                        <td colspan="11" class="mdui-text-center">
                            <a href="{{ route('fastVisit.create') }}">新建 访问入口</a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div id="animate_line" style="display: none">
        <div class="mdui-progress">
            <div class="mdui-progress-indeterminate"></div>
        </div>
    </div>

    <script>
        new ClipboardJS('.can_copy')

        $('.can_copy').click(function() {
            mdui.snackbar({
                message: '<i class="mdui-icon material-icons">content_copy</i> 已复制到剪切板。',
                position: 'right-bottom'
            })
        })

        // Update
        function toggleAd(id, project_id) {
            var animate = $('#animate_line').html()
            $('.ad_' + id).html(animate)
            mdui.mutation()
            $.ajax({
                type: 'PUT',
                url: '{{ url()->current() }}' + '/' + id,
                data: {
                    'project_id': project_id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 'success') {
                        var message = '未知'
                        if (data.message) {
                            message = '已启用'
                        } else {
                            message = '未启用'
                        }
                        $('.ad_' + id).html(message)
                        mdui.snackbar({
                            message: '广告状态已切换为 ' + message,
                            position: 'right-bottom'
                        })
                    } else {
                        mdui.snackbar({
                            message: '此时无法切换广告状态。',
                            position: 'right-bottom'
                        })
                    }
                },
                error: function(data) {
                    mdui.snackbar({
                        message: '此时无法切换广告状态。',
                        position: 'right-bottom'
                    })
                }
            })
        }
    </script>

@endsection
