@extends('layouts.app')

@section('title', '对方还未处理的邀请')

@section('content')
    <h1 class="mdui-text-color-theme">对方还未处理的邀请</h1>

    <br />

    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>项目名称</th>
                    <th>邀请人</th>
                    <th>状态</th>
                    <th>发出时间</th>
                </tr>
            </thead>
            <tbody>
                @php($i = 1)
                @foreach ($invites as $invite)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $invite->project->name }}</td>
                        <td>{{ $invite->user->name }}</td>
                        @if ($invite->status == 2)
                            <td>已拒绝</td>
                        @elseif ($invite->status == 1)
                            <td>已同意</td>
                        @else
                            <td>未回应</td>
                        @endif
                        <td>{{ $invite->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
