@extends('layouts.app')

@section('title', '项目管理')

@section('content')
    <h1 class="mdui-text-color-theme">我所在的项目</h1>
    <a href="{{ route('projects.create') }}" class="mdui-btn mdui-color-theme-accent mdui-ripple">新建项目</a> &nbsp; <a href="{{ route('invites.list') }}" class="mdui-btn mdui-color-theme-accent mdui-ripple">邀请列表</a>
    <div class="mdui-row mdui-m-t-2">
        <div class="mdui-col-xs-6">

            @foreach ($projects as $project)
                <div>
                    <h3 id="proj-{{ $project->project->id }}" class="scroll_listen mdui-text-color-theme">{{ $project->project->name }}</h3>
                    <div class="mdui-card">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">{{ $project->project->name }}</div>
                            <div class="mdui-card-primary-subtitle">{{ $project->project->description }}</div>
                            <div class="mdui-card-primary-subtitle">项目积分：{{ $project->project->balance }} @if ($project->project->balance < 100) !积分过少! @endif</div>
                            <div class="mdui-card-primary-subtitle">创建时间：{{ $project->project->created_at }}</div>
                        </div>
                        <div class="mdui-card-actions">
                            <a href="{{ route('projects.show', $project->project->id) }}"
                                class="mdui-btn mdui-ripple">管理</a>

                            @if ($project->project->user_id != Auth::id())
                                <form style="display: inline;" method="POST" action="{{ route('projects.leave', $project->project->id)}}">
                                    @csrf
                                    <button onclick="if(!confirm('离开后，将不会退回任何资金，也无法撤销该操作。')) return false" class="mdui-btn mdui-ripple">离开</button>
                                </form>
                            @else
                                <a href="{{ route('projects.edit', $project->project->id) }}"
                                    class="mdui-btn mdui-ripple">修改</a>
                            @endif
                        </div>
                    </div>
                </div>

            @endforeach

        </div>
    </div>

@endsection

