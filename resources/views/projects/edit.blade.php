@extends('layouts.app')

@section('title', '编辑项目')

@section('content')
    <h1 class="mdui-text-color-theme">编辑项目</h1>
    <form method="post" action="{{ route('projects.update', $project->id) }}">
        @csrf
        @method('PUT')
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">名称</label>
            <input class="mdui-textfield-input" type="text" name="name" value="{{ $project->name }}" />
        </div>
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">介绍</label>
            <input class="mdui-textfield-input" type="text" name="description" value="{{ $project->description }}"/>
        </div>
        <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple">保存</button>
    </form>
@endsection
