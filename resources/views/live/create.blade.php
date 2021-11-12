@extends('layouts.app')

@section('title', '安排演出')

@section('content')
    <div class="mdui-typo-display-1">安排演出</div>
    <p>您可以安排演出到时间河中。</p>
    <form method="post" action="{{ route('live.store') }}">
        @csrf
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">演出标题</label>
            <input class="mdui-textfield-input" type="text" name="name" value="{{ old('name') }}" />
        </div>

        <div class="mdui-textfield">
            <label class="mdui-textfield-label">开始时间</label>
            <input class="mdui-textfield-input" type="time" name="start_at" value="{{ old('start_at') }}" />
        </div>

        <div class="mdui-textfield">
            <label class="mdui-textfield-label">结束时间</label>
            <input class="mdui-textfield-input" type="time" name="end_at" value="{{ old('end_at') }}" />
        </div>

        <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--new-live">安排演出</button>
    </form>
@endsection
