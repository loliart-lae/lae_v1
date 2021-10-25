@extends('layouts.app')

@section('title', '对方还未处理的邀请')

@section('content')
    <div class="mdui-typo-display-2">对方还未处理的邀请</div>

    <br />

    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>项目名称</th>
                    <th>邀请人</th>
                    <th>对方名称</th>
                    <th>状态</th>
                    <th>发出时间</th>
                </tr>
            </thead>
            <tbody>
                @php($i = 1)
                @foreach ($invites as $invite)
                    <tr>
                        <td nowrap>{{ $i++ }}</td>
                        <td nowrap>{{ $invite->project->name }}</td>
                        <td nowrap>{{ $invite->user->name }}</td>
                        <td nowrap>{{ $invite->invite_user->name }}</td>
                        @if ($invite->status == 2)
                            <td nowrap>已拒绝</td>
                        @elseif ($invite->status == 1)
                            <td nowrap>已同意</td>
                        @else
                            <td nowrap>未回应</td>
                        @endif
                        <td nowrap>{{ $invite->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
