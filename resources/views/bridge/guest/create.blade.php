@extends('layouts.app')

@section('title', '客户机')

@section('content')
    <div class="mdui-typo-display-1">Transfer Bridge Guest</div>
    <p>组中的客户机可以广播数据到当前组中，也可以指名组的ID进行跨组广播。</p>
    <form method="post" action="{{ route('bridge.guest.store', $bridge->id) }}">
        @csrf
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">客户机名称</label>
            <input class="mdui-textfield-input" type="text" name="name" value="{{ old('name') }}" />
        </div>

        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">客户机识别码</label>
            <input class="mdui-textfield-input" type="text" name="unique_id" value="{{ old('unique_id') }}" />
            <div class="mdui-textfield-helper">识别码必须唯一。</div>
        </div>

        <div>
            <h1 class="mdui-typo-title">这个客户机属于哪个组？</h1>
            <select class="mdui-select mdui-m-t-2" name="group_id" mdui-select>
                @foreach ($bridge->groups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit"
            class="mdui-m-t-1 mdui-btn mdui-color-theme-accent mdui-ripple umami--click--new-bridge-guest">创建</button>
    </form>
@endsection
