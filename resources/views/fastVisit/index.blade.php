@extends('layouts.app')

@section('title', '快捷访问')

@section('content')
    <div class="mdui-typo-display-2">快捷访问</div>
    <p>快捷访问可以设置访问网址时的跳转的URI。</p>

    <a class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--goto-new-fastVisit" href="{{ route('fastVisit.create') }}">新建入口</a>
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
                        <td nowrap="nowrap"><a class="can_copy umami--click--fastVisit-copy"
                                data-clipboard-text="https://{{ $fastVisit->domain->domain }}/v/{{ $fastVisit->slug }}">{{ $fastVisit->slug }}</a>
                        </td>
                        <td nowrap>{{ $fastVisit->domain->domain }}</td>
                        <td nowrap="nowrap">{{ $fastVisit->uri }}</td>
                        <td nowrap="nowrap" class="umami--click--toggle-ad">
                            @if ($fastVisit->show_ad)
                                <label class="mdui-switch">
                                    <input id="check_ad_{{ $fastVisit->id }}"
                                        onclick="toggleAd({{ $fastVisit->id }},{{ $fastVisit->project->id }})"
                                        class="ad_{{ $fastVisit->id }}" type="checkbox" checked />
                                    <i class="mdui-switch-icon"></i>
                                </label>
                            @else
                                <label class="mdui-switch">
                                    <input onclick="toggleAd({{ $fastVisit->id }},{{ $fastVisit->project->id }})"
                                        class="ad_{{ $fastVisit->id }}" type="checkbox" />
                                    <i class="mdui-switch-icon"></i>
                                </label>
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

    <script>
        new ClipboardJS('.can_copy')

        $('.can_copy').click(function() {
            mdui.snackbar({
                message: '链接短语 已复制到剪切板。',
                position: 'right-bottom'
            })
        })

        // Update
        function toggleAd(id, project_id) {
            $.ajax({
                type: 'PUT',
                url: '{{ url()->current() }}' + '/' + id,
                data: {
                    'project_id': project_id
                },
                dataType: 'json',
                success: function(data) {
                    $('#check_ad_' + id).prop("checked", data.message);
                }
            })
        }
    </script>

@endsection
