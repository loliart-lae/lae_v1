@extends('layouts.app')

@section('title', '编辑安排')

@section('content')
    <div class="mdui-typo-display-1">编辑安排</div>
    <p>编辑安排的演出</p>
    <form method="post" action="{{ route('live.update', $live->id) }}">
        @csrf
        @method('PUT')
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">演出标题</label>
            <input class="mdui-textfield-input" type="text" name="name" value="{{ $live->name }}" />
        </div>

        <div class="mdui-textfield">
            <label class="mdui-textfield-label">串流地址</label>
            <input class="mdui-textfield-input" type="text" readonly
                value="{{ config('app.streaming_proto') }}://{{ config('app.domain') }}:{{ config('app.streaming_port') }}/{{ config('app.streaming_application') }}" />
        </div>

        <div class="mdui-textfield">
            <label class="mdui-textfield-label">串流密钥</label>
            <input class="mdui-textfield-input" type="text" readonly value="aeTimeRiver?token={{ $live->token }}" />
            <div class="mdui-textfield-helper">请不要泄漏给他人。</div>
        </div>


        <button type="submit"
            class="mdui-m-t-2 mdui-btn mdui-color-theme-accent mdui-ripple umami--click--update-live">确认安排</button>
    </form>

    <form method="post" class="mdui-m-t-3" action="{{ route('live.destroy', $live->id) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="mdui-btn mdui-color-red mdui-ripple umami--click--delete-live">删除安排</button>

    </form>

@endsection
