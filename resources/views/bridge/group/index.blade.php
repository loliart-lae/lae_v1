@extends('layouts.app')

@section('title', '新建客户机')

@section('content')
    <div class="mdui-typo-display-1">客户机</div>
    <p>Transfer Bridge 是由LAE研发的文本信息交换集群网络。</p>
    <div>
        <a href="{{ route('bridge.groups.create', $bridge->id) }}"
            class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--goto-create-bridge-group">新建 分发组</a>

        <a href="{{ route('bridge.guest.create', $bridge->id) }}"
            class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--goto-create-bridge-guest">新建 客户机</a>
    </div>
    <ul class="mdui-list">

        @foreach ($bridge->groups as $group)
            <ul class="mdui-list" mdui-collapse="{accordion: true}">
                <li class="mdui-collapse-item">
                    <div class="mdui-collapse-item-header mdui-list-item mdui-ripple">
                        <i class="mdui-list-item-icon mdui-icon material-icons">group</i>
                        <div class="mdui-list-item-content">{{ $group->name }}</div>
                        <a href="{{ route('bridge.groups.edit', [$bridge->id, $group->id]) }}"
                            class="mdui-btn mdui-btn-icon mdui-ripple mdui-m-r-1" style="border-radius: 50px !important">
                            <i class="mdui-list-item-icon mdui-icon material-icons">settings</i>
                        </a>
                        <a href="{{ route('bridge.groups.edit', [$bridge->id, $group->id]) }}"
                            class="mdui-btn mdui-btn-icon mdui-ripple mdui-m-r-1" style="border-radius: 50px !important">
                            <i class="mdui-list-item-icon mdui-icon material-icons">open_in_new</i>
                        </a>

                        <i class="mdui-collapse-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
                    </div>
                    <ul class="mdui-collapse-item-body mdui-list mdui-list-dense">
                        @foreach ($group->guests as $guest)
                            <a href="{{ route('bridge.guest.edit', [$bridge->id, $group->id]) }}">
                                <li class="mdui-list-item mdui-ripple">{{ $guest->name }} #{{ $guest->unique_id }}</li>
                            </a>
                        @endforeach
                    </ul>
                </li>
            </ul>
        @endforeach

    </ul>

    <script>
        mdui.mutation()
    </script>
@endsection
