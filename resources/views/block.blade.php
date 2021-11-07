@extends('layouts.app')

@section('title', '封神榜')

@section('content')
    <div class="mdui-row">
        <div class="mdui-typo-display-2">今日一时爽</div>
        <div class="mdui-typo-headline-opacity mdui-p-t-1">次日封神榜</div>
    </div>
    <div class="mdui-p-t-3">
        <div class="mdui-table-fluid" style="border-radius: 8px;">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>用户 ID</th>
                        <th>头像</th>
                        <th>用户名</th>
                        <th>邮箱</th>
                        <th>封禁原因</th>
                        <th>封禁时间</th>
                        <th style="overflow: visible; text-align: right;">
                            {{ $users->links('vendor.pagination.table') }}
                        </th>
                    </tr>
                </thead>

                <tbody class="mdui-typo">
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                {{ $user->id }}
                            </td>

                            <td style="width: 60px">
                                <img class="avatar mdui-img-circle"
                                    src="{{ config('app.gravatar_url') }}/{{ md5(strtolower($user->email)) }}" />
                            </td>

                            <td nowrap>
                                {{ $user->name }}
                            </td>

                            <td nowrap>
                                {{ $user->email }}
                            </td>

                            <td>
                                {{ $user->block_reason }}
                            </td>

                            <td nowrap>
                                {{ $user->block_at ?? '未知' }}
                            </td>

                            <td></td>
                        </tr>
                    @endforeach
                </tbody>

                <div class="mdui-dialog" id="search">
                    <div class="mdui-textfield">
                        <input class="mdui-textfield-input" type="text" placeholder="User Name" />
                    </div>
                </div>
            </table>
        </div>
    </div>

@endsection
