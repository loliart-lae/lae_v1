@extends('layouts.app')

@section('title', '审计记录')

@section('content')
    <div class="mdui-typo-display-2">审计记录</div>

    <br />

    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>成员</th>
                    <th>信息</th>
                    <th>时间</th>
                </tr>
            </thead>
            <tbody>
                @php($i = 1)
                @foreach ($activities as $activity)
                    <tr>
                        <td nowrap>{{ $i++ }}</td>
                        <td nowrap>
                            @if (is_null($activity->user))
                                系统提醒
                            @else
                                {{ $activity->user->name }}
                            @endif
                        </td>
                        <td nowrap>{{ $activity->msg }}</td>
                        <td nowrap>{{ $activity->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        {{ $activities->links() }}
    </div>

@endsection
