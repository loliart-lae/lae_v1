@extends('layouts.app')

@section('title', '项目管理')

@section('content')
    <div class="mdui-typo-display-2">我所在的项目</div>

    <a href="{{ route('projects.create') }}"
        class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--goto-create-project">新建项目</a>
    <a href="{{ route('invites.list') }}"
        class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--goto-invites-list">
        @if ($invites > 0)
            {{ $invites }} 个
        @else
            没有
        @endif
        待处理的邀请
    </a>
    <div class="mdui-row mdui-m-t-2">

        <div id="masonry" class="mdui-row">
            @foreach ($projects as $project)
                <div class="mdui-col-sm-6 pull">
                    <div>
                        <h3 id="proj-{{ $project->project->id }}" class="scroll_listen mdui-text-color-theme">
                            {{ $project->project->name }}</h3>
                        <div class="mdui-card mdui-hoverable">
                            <div class="mdui-card-primary">
                                <div class="mdui-card-primary-title">{{ $project->project->name }}</div>
                                <div class="mdui-card-primary-subtitle">{{ $project->project->description }}</div>
                                <div class="mdui-card-primary-subtitle">项目积分：{{ $project->project->balance }}
                                    @if ($project->project->balance < 100) !积分过少! @endif</div>
                                <div class="mdui-card-primary-subtitle">创建时间：{{ $project->project->created_at }}</div>
                            </div>
                            <div class="mdui-card-actions">
                                <a href="{{ route('projects.show', $project->project->id) }}"
                                    class="mdui-btn mdui-ripple umami--click--project-manage">管理</a>

                                @if ($project->project->user_id != Auth::id())
                                    <form style="display: inline;" method="POST"
                                        action="{{ route('projects.leave', $project->project->id) }}">
                                        @csrf
                                        <button onclick="if(!confirm('离开后，将不会退回任何资金，也无法撤销该操作。')) return false"
                                            class="mdui-btn mdui-ripple umami--click--project-leave">离开</button>
                                    </form>
                                @else
                                    <a href="{{ route('projects.edit', $project->project->id) }}"
                                        class="mdui-btn mdui-ripple umami--click--project-edit">修改</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        var $container = $('#masonry')

        $container.masonry({
            itemSelector: '.poll',
        })
    </script>

@endsection
