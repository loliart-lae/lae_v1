@extends('layouts.app')

@section('title', '积分历史')

@section('content')
    <div class="mdui-typo-display-1 mdui-m-t-5">积分历史</div>
    <div class="mdui-table-fluid mdui-m-t-1">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>类型</th>
                    <th>值</th>
                    <th>原因</th>
                    <th>发生时间</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($balanceLogs as $balanceLog)
                    <tr>
                        <td nowrap>{{ $balanceLog->id }}</td>
                        <td nowrap>
                            @if ($balanceLog->method == 'charge')
                            充值
                            @else
                            扣除
                            @endif
                        </td>
                        <td nowrap>{{ $balanceLog->value }}</td>
                        <td nowrap>{{ $balanceLog->reason }}</td>
                        <td nowrap>{{ $balanceLog->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $balanceLogs->links() }}

@endsection
