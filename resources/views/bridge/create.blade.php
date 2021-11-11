@extends('layouts.app')

@section('title', '新建 Transfer Bridge 集群')

@section('content')
    <div class="mdui-typo-display-1">Transfer Bridge 集群</div>
    <p>Transfer Bridge 是由LAE研发的文本信息交换集群网络。</p>
    <form method="post" action="{{ route('bridge.store') }}">
        @csrf
        <div class="mdui-panel" mdui-panel>
            <div class="mdui-panel-item">
                <div class="mdui-panel-item-header">
                    <div class="mdui-panel-item-title">选择集群所在的项目。</div>
                    <div class="mdui-panel-item-summary"></div>
                    <i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
                </div>
                <div class="mdui-panel-item-body">
                    <x-choose-project-form />
                    <div class="mdui-panel-item-actions">
                        <span class="mdui-btn mdui-ripple" mdui-panel-item-close>选好了。</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">集群名称</label>
            <input class="mdui-textfield-input" type="text" name="name" />
            <div class="mdui-textfield-helper">键入一个集群名称来标识这个集群。</div>
        </div>

        <button type="submit"
            class="mdui-m-t-1 mdui-btn mdui-color-theme-accent mdui-ripple umami--click--new-bridge">新建</button>
    </form>
@endsection
