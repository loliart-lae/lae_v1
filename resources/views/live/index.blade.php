@extends('layouts.app')

@section('title', '流媒体安排表')

@section('content')
<div class="mdui-typo-display-1">流媒体安排表</div>

<div class="mdui-m-t-1">
    <a href="{{ route('live.create') }}"
        class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--goto-create-live">新建 安排</a>
</div>

<div class="mdui-m-t-3">
    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-typo">
            <thead>
                <tr>
                    <th>#</th>
                    <th>名称</th>
                    <th>出演</th>
                    <th>开始时间</th>
                    <th>结束时间</th>
                </tr>
            </thead>
            <tbody>
                @php($i = 0)
                @foreach ($lives as $live)
                <tr @if ($live->status) style="background-color: #90f1a754!important;color:white" @endif>
                    <td>{{ ++$i }}</td>
                    <td><a
                            href="@if ((new \Illuminate\Support\Carbon)->diffInMinutes((new \Illuminate\Support\Carbon)->parse($live->start_at), false) < 0)#@elseif ($live->user_id == Auth::id()) {{ route('live.edit', $live->id) }} @endif">{{
                            $live->name }}</td>

                    <td>@if (is_null($live->user->website))
                        {{ $live->user->name }}
                        @else
                        <a target="_blank" href="{{ $live->user->website }}">{{ $live->user->name }}</a>
                        @endif
                    </td>
                    <td>{{ $live->start_at }}</td>
                    <td>{{ $live->end_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
