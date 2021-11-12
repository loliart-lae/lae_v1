@extends('layouts.app')

@section('title', '流媒体节目表')

@section('content')
    <div class="mdui-typo-display-1">流媒体节目表</div>

    <div class="mdui-m-t-1">
        <a href="{{ route('live.create') }}"
            class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--goto-create-live">新建 节目安排</a>
    </div>

    <div class="mdui-m-t-3">
        <div class="mdui-table-fluid">
            <table class="mdui-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>名称</th>
                        <th>开始时间</th>
                        <th>结束时间</th>
                        <th class="mdui-table-col-numeric">-</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 0)
                    @foreach ($lives as $live)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $live->name }}</td>
                            <td>{{ $live->start_at }}</td>
                            <td>{{ $live->end_at }}</td>

                            <td class="mdui-typo">
                                @if ($live->user_id == Auth::id())
                                    <a href="{{ route('live.edit', $live->id) }}">修改</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
