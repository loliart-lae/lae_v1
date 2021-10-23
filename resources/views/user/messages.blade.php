@extends('layouts.app')

@section('title', '历史消息')

@section('content')
    <div class="mdui-typo-display-1 mdui-m-t-5">历史消息</div>
    <div class="mdui-table-fluid mdui-m-t-1">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>内容</th>
                    <th>通知时间</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($messages as $message)
                    <tr>
                        <td nowrap>{{ $message->id }}</td>
                        <td nowrap>{{ $message->content }}</td>
                        <td nowrap>{{ $message->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $messages->links() }}

@endsection
