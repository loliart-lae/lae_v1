@extends('layouts.app')

@section('title', '新的资源图表')

@section('content')
    <div class="mdui-typo-display-1">新的资源图表</div>

    <form method="post" action="{{ route('serverMonitor.store') }}">
        @csrf

        <div class="mdui-tab mdui-tab-scrollable mdui-m-t-1" mdui-tab>
            <a href="#choose-project" class="mdui-ripple">选择项目</a>
            <a href="#choose-public" class="mdui-ripple">开放性</a>
            <a href="#choose-name" class="mdui-ripple">设置名称</a>
        </div>


        <div id="choose-project">
            <x-choose-project-form />
        </div>

        <div id="choose-public">
            <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
                <span class="mdui-typo-headline">开放性</span>
                <p>这个资源图表别人可见吗？</p>
                <div class="mdui-row-md-4 mdui-m-b-2">
                    <div class="mdui-col">
                        <label class="mdui-checkbox">
                            <input type="checkbox" name="is_public" value="1" />
                            <i class="mdui-checkbox-icon"></i>
                            如果可见，请勾选。
                        </label>
                    </div>
                </div>
            </div>

        </div>

        <div id="choose-name">
            <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
                <span class="mdui-typo-headline">设置名称</span>
                <p>标识这个资源图表。</p>
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">名称</label>
                    <input class="mdui-textfield-input" type="text" name="name" value="{{ old('name') }}" required />
                </div>
            </div>
            <br />

            <button class="mdui-btn mdui-ripple mdui-color-theme">新建</button>
        </div>


    </form>


@endsection
