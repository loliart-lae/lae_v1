@extends('layouts.app')

@section('title', '新建项目')

@section('content')
    <div class="mdui-typo-display-2">新建项目</div>
    <p>在你新建项目之后，你需要在你的项目中充值积分，它在项目中共享。</p>
    <form method="post" action="{{ route('projects.store') }}">
        @csrf
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">名称</label>
            <input class="mdui-textfield-input" type="text" name="name" />
        </div>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">介绍</label>
            <input class="mdui-textfield-input" type="text" name="description" />
        </div>
        <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--new-project">新建</button>
    </form>
@endsection
