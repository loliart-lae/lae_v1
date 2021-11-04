@extends('layouts.app')

@section('title', $fastVisit->name)

@section('content')
    <div class="mdui-typo-display-2">编辑快捷访问</div>
    <p>编辑这个快捷访问的参数</p>

    <p>有效访问量: {{ $fastVisit->times }}</p>


    <form method="post" action="{{ route('fastVisit.update', $fastVisit->id) }}">
        @csrf
        @method('PUT')

        <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
            <span class="mdui-typo-headline">目标地址</span>
            <p>访问入口后，跳转到哪里？</p>
            <div class="mdui-textfield">
                <label class="mdui-textfield-label">地址</label>
                <input class="mdui-textfield-input" type="text" name="uri" placeholder="https://"
                    value="{{ $fastVisit->uri }}" required />
                <div class="mdui-textfield-helper">如果不添加协议且不开启广告，则可能会无法正常使用。</div>
            </div>
        </div>

        <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
            <span class="mdui-typo-headline">启用广告？</span>

            <div class="mdui-p-t-2">
                <span>如果启用，你的入口将不会立即跳转。</span>
                <br />
                启用广告：<label class="mdui-switch">
                    <input type="checkbox" name="enable_ad" value="1" @if ($fastVisit->show_ad) checked @endif />
                    <i class="mdui-switch-icon"></i>
                </label>
            </div>
        </div>


        <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
            <span class="mdui-typo-headline">名称</span>
            <p>名称用于辨别。</p>
            <div class="mdui-textfield">
                <label class="mdui-textfield-label">名称</label>
                <input class="mdui-textfield-input" type="text" name="name" value="{{ $fastVisit->name }}" required />
            </div>
        </div>

        <button type="submit"
            class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--fastVisit-update">修改</button>

    </form>

@endsection
