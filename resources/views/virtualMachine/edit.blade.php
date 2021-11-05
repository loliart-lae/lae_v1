@extends('layouts.app')

@section('title', '编辑虚拟机')

@section('content')
    <div class="mdui-typo-display-1">编辑 {{ $virtualMachine->name }}</div>

    <form method="post" action="{{ route('virtualMachine.update', $virtualMachine->id) }}">
        @csrf
        @method('PUT')
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">名称</label>
            <input class="mdui-textfield-input" type="text" name="name" value="{{ $virtualMachine->name }}" required />
        </div>

        <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--update">保存</button>
    </form>

@endsection
