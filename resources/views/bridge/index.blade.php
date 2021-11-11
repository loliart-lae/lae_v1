@extends('layouts.app')

@section('title', 'Transfer Bridge')

@section('content')
    <div class="mdui-typo-display-1">Transfer Bridge</div>

    <div>
        <a href="{{ route('bridge.create') }}"
            class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--goto-create-bridge">新建 数据转发桥</a>
    </div>

    <div>
        <div class="mdui-row">
            @php($i = 1)
            @php($last_project_id = 0)
            @foreach ($bridges as $bridge)
                @php($i++)

                @if ($bridge->project->id !== $last_project_id)
                    @php($last_project_id = $bridge->project->id)
                    <h1 class="mdui-typo-display-1 mdui-col-xs-12 mdui-p-t-2" style="margin:0;position: relative;top:10px">
                        {{ $bridge->project->name }}</h1>
                @endif
                <div class="mdui-col-lg-6 mdui-col-md-6 mdui-col-xs-12">
                    <div class="mdui-card mdui-m-t-2 mdui-hoverable">
                        <div class="mdui-card-primary">
                            <div class="mdui-typo">
                                <div>{{ $bridge->id }}.
                                    {{ $bridge->name }}
                                </div>
                                <div>没有可以显示的内容。</div>

                            </div>
                        </div>
                        <div class="mdui-card-actions">
                            <button class="mdui-btn mdui-ripple umami--click--bridge-manage">管理</button>
                            <button class="mdui-btn mdui-ripple umami--click--bridge-broadcast">广播</button>
                            <a href="{{ route('bridge.edit', $bridge->id) }}"
                                class="mdui-btn mdui-ripple umami--click--bridge-edit">编辑</a>

                            <button
                                onclick="if (confirm('删除后，集群内机器将全部离线，集群配置也将清空，并且无法找回。')) {$('#f-{{ $i }}').submit()} else {return false}"
                                class="mdui-btn mdui-ripple umami--click--bridge-delete">销毁</button>
                            <form id="f-{{ $i }}" method="post"
                                action="{{ route('bridge.destroy', $bridge->id) }}">@csrf
                                @method('DELETE')</form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

@endsection
