@extends('layouts.admin')

@section('title', '用户管理')

@section('content')
    <div class="mdui-typo-display-2">用户管理</div>

    <div class="mdui-p-t-3">
        <div class="mdui-table-fluid" style="border-radius: 8px;">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>头像</th>
                        <th>用户名</th>
                        <th>签名</th>
                        <th style="overflow: visible; text-align: right;">
                            {{ $users->links('vendor.pagination.table') }}

                            <button id="user-search" class="mdui-btn mdui-btn-icon" mdui-tooltip="{content: '搜索', delay: 300}">
                                <i class="mdui-icon material-icons-outlined">search</i>
                            </button>

                            <div id="user-search-menu" class="mdui-menu" style="transform-origin: 100% 0px; position: absolute; top: 44px; left: 428px;">
                                <div class="mdui-textfield">
                                    <label class="mdui-textfield-label">用户名</label>
                                    <input class="mdui-textfield-input" type="text" autocomplete="off" name="user_name">
                                </div>

                                <div class="mdui-textfield">
                                    <label class="mdui-textfield-label">邮箱</label>
                                    <input class="mdui-textfield-input" type="text" autocomplete="off" name="user_email">
                                </div>

                                <button class="mdui-btn mdui-btn-raised mdui-color-theme" mdui-dialog="{target: '#search'}">搜索</button>
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody class="mdui-typo">
                    @foreach ($users as $user)
                    <tr>
                        <td style="width: 60px;">
                            <img class="avatar" src="{{ config('app.gravatar_url') }}/{{ md5(strtolower($user->email)) }}" />
                        </td>

                        <td  nowrap="nowrap">
                            @if (is_null($user->website))
                                {{ $user->name }}
                            @else
                                <a target="_blank" href="{{ $user->website }}">{{ $user->name }}</a>
                            @endif
                        </td>

                        <td nowrap="nowrap">
                            {{ $user->bio ?? '未设置签名' }}
                        </td>

                        <td style="text-align: right; width: 154px;">
                            <a class="mdui-btn mdui-btn-icon mdui-text-color-theme-icon" mdui-tooltip="{content: '编辑', delay: 300}">
                                <i class="mdui-icon material-icons-outlined">edit</i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

                <div class="mdui-dialog" id="search">
                    <div class="mdui-textfield">
                        <input class="mdui-textfield-input" type="text" placeholder="User Name"/>
                    </div>
                </div>
            </table>
        </div>
    </div>

    <script>
        var $ = mdui.$;
        var tab = new mdui.Tab('#search');

        $('#search').on('open.mdui.dialog', function () {
            tab.handleUpdate();
        });
    </script>

    <style type="text/css">
        .avatar {
            float: left;
            width: 28px;
            height: 28px;
            margin-left: -5px;
            border-radius: 50%;
        }

        .mdui-menu {
            position: fixed;
            z-index: 99999;
            display: block;
            box-sizing: border-box;
            width: 168px;
            margin: 0;
            padding: 8px 0;
            overflow-y: auto;
            color: rgba(0,0,0,.87);
            font-size: 16px;
            list-style: none;
            background-color: #fff;
            border-radius: 8px;
            transform: scale(0);
            visibility: hidden;
            opacity: 0;
            transition-timing-function: cubic-bezier(0,0,.2,1);
            transition-duration: .3s;
            transition-property: transform,opacity,visibility;
            will-change: transform,opacity,visibility;
            -webkit-overflow-scrolling: touch;
            box-shadow: 0 5px 5px -3px rgb(0 0 0 / 20%), 0 8px 10px 1px rgb(0 0 0 / 14%), 0 3px 14px 2px rgb(0 0 0 / 12%);
        }

        .mdui-textfield {
            padding-top: 16px;
            padding-bottom: 4px;
            overflow: visible;
        }

        .mdui-textfield-label {
            float: left;
            width: 128px;
            height: 36px;
            margin-right: 8px;
            font-size: 13px;
            line-height: 36px;
            transform: inherit;
        }
    </style>

@endsection
