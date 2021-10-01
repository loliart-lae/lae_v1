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
                    <th>短语</th>
                    <th>域</th>
                    <th>目标地址</th>
                    <th>广告状态</th>
                    <th>有效访问量</th>
                    <th>属于项目</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody class="mdui-typo">
                <tr>
                    <td colspan="11" class="mdui-text-center">
                        <a href="{{ route('fastVisit.create') }}">新建 访问入口</a>
                    </td>
                </tr>
                @php($i = 1)
                @foreach ($fastVisits as $fastVisit)
                    <tr>
                        <td nowrap="nowrap">{{ $i++ }}</td>
                        <td nowrap="nowrap">{{ $fastVisit->id }}</td>
                        <td nowrap="nowrap">{{ $fastVisit->name }}</td>
                        <td nowrap="nowrap"><a href="#" class="can_copy"
                                data-clipboard-text="https://{{ $fastVisit->domain->domain }}/v/{{ $fastVisit->slug }}">{{ $fastVisit->slug }}</a>
                        </td>
                        <td nowrap>{{ $fastVisit->domain->domain }}</td>
                        <td nowrap="nowrap">{{ $fastVisit->uri }}</td>
                        <td nowrap="nowrap" style="cursor: pointer" onclick="$('#f-t-{{ $i }}').submit()">
                            @if ($fastVisit->show_ad)
                                <a href="#">已启用</a>
                            @else
                                <a href="#">未启用</a>
                            @endif
                            <form id="f-t-{{ $i }}" method="POST" action="{{ route('fastVisit.update', $fastVisit->id) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="project_id" value="{{ $fastVisit->project->id }}" />
                            </form>
                        </td>

                        <td nowrap="nowrap">{{ $fastVisit->times }}</td>
                        <td nowrap="nowrap"><a
                                href="{{ route('projects.show', $fastVisit->project->id) }}">{{ $fastVisit->project->name }}</a>
                        </td>
                        <td> <a href="#"
                                onclick="if (confirm('删除后，这个访问入口将无法使用。')) { $('#f-{{ $i }}').submit() }">删除</a>
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


@endsection
