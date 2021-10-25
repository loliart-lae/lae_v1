@extends('layouts.app')

@section('title', '待处理的项目邀请')

@section('content')
    <div class="mdui-typo-display-2">待处理的项目邀请</div>

    <br />

    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>项目名称</th>
                    <th>邀请人</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @php($i = 1)
                @foreach ($invites as $invite)
                    <tr>
                        <td nowrap>{{ $i++ }}</td>
                        <td nowrap>{{ $invite->project->name }}</td>
                        <td nowrap>{{ $invite->user->name }}</td>
                        @if ($invite->status == false)
                            <form id="f_{{ $invite->id }}_accept" method="post"
                                action="{{ route('invites.accept', $invite->id) }}">
                                @csrf
                                @method('POST')
                            </form>
                            <form id="f_{{ $invite->id }}_deny" method="post"
                                action="{{ route('invites.deny', $invite->id) }}">
                                @csrf
                                @method('POST')
                            </form>
                            <td nowrap><a class="mdui-text-color-theme umami--click--invite-accept" style="text-decoration: none"
                                    href="javascript: $('#f_{{ $invite->id }}_accept').submit();">同意</a> 或者 <a
                                    class="mdui-text-color-theme umami--click--accept-deny" style="text-decoration: none"
                                    href="javascript: $('#f_{{ $invite->id }}_deny').submit();">拒绝</a></td>
                        @endif

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
