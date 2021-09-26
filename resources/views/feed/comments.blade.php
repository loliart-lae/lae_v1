@extends('layouts.app')

@section('title', '回复')

@section('content')
    <div class="mdui-typo">
        <div class="mdui-card" style="margin-top: 5px">
            <div class="mdui-card-header">
                <img class="mdui-card-header-avatar"
                    src="{{ config('app.gravatar_url') }}/{{ md5($status->user->email) }}" />
                <div class="mdui-card-header-title">{{ $status->user->name }} <small> /
                        {{ $status->created_at->diffForHumans() }}</small></div>
                <div class="mdui-card-header-subtitle">{{ $status->user->bio ?? '咕噜咕噜咕噜' }}</div>
            </div>
            <div class="mdui-card-content">{!! nl2br(e($status->content)) !!}</div>
            <div class="mdui-card-actions">
                <button id="status_{{ $status->id }}" onclick="toggleLike({{ $status->id }})"
                    class="mdui-btn mdui-ripple mdui-btn-icon">
                    @if (is_null($status->like))
                        <i class="mdui-icon material-icons" style="color: unset">star_border</i>
                    @elseif ($status->like->is_liked)
                        <i style="color:#36a6e8" class="mdui-icon material-icons">star</i>
                    @else
                        <i class="mdui-icon material-icons" style="color: unset">star_border</i>
                    @endif
                </button>
                <button onclick="return false" class="mdui-btn mdui-ripple">@php($replies = count($status->replies))
                    @if ($replies > 0) {{ $replies }}条 @else 没有 @endif 回复</button>

                @can('destroy', $status)
                    <form style="display: initial;" action="{{ route('status.destroy', $status->id) }}" method="POST"
                        onsubmit="return confirm('确定要删除吗？删除后动态将会永远被埋没到长河中。');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="mdui-btn mdui-ripple">删除</button>
                    </form>
                @endcan
            </div>
        </div>

    </div>

    <div class="mdui-typo">
        @php($i = 0)
        <h1>@if ($replies > 0) {{ $replies }} 条@endif 回复</h1>
        <div class="mdui-row">
            @foreach ($status_replies as $status_reply)
                @php($i++)
                <div class="mdui-col-xs-12 mdui-p-y-2">
                    <div class="mdui-col-xs-2 mdui-col-sm-1">
                        <img class="mdui-img-circle mdui-center"
                            src="{{ config('app.gravatar_url') }}/{{ md5($status_reply->user->email) }}">
                    </div>
                    <div class="mdui-col-xs-11">
                        {{ $i }}. {{ $status_reply->user->name }} 说：
                        <br />
                        {{ $status_reply->content }}
                    </div>
                </div>
            @endforeach
        </div>
        <br /> <br />
        <div>
            {{ $status_replies->links() }}
        </div>

        <form id="replyForm" method="POST" action="{{ route('status.reply', $status->id) }}">
            @csrf
            @method('PUT')
            <div class="mdui-textfield">
                <textarea class="mdui-textfield-input" rows="4" name="content" placeholder="保持友善～" maxlength="140"
                    required></textarea>
            </div>
            <button type="submit" class="mdui-btn mdui-ripple mdui-color-theme">回复</button>
        </form>

    </div>




    <script>
        function toggleLike(id) {
            $.ajax({
                type: 'PUT',
                url: `{{ route('status.like') }}?id=${id}`,
                data: {
                    'toggle': 'toggle'
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        $('#status_' + id).css('color', '#36a6e8')
                        $('#status_' + id).html(`<i class="mdui-icon material-icons">star</i>`)
                    } else {
                        $('#status_' + id).css('color', 'unset')
                        $('#status_' + id).html(`<i class="mdui-icon material-icons">star_border</i>`)
                    }
                },
                error: function(data) {
                    mdui.snackbar({
                        message: '暂时无法点赞。',
                        position: 'bottom'
                    })
                }
            })
        }
    </script>

@endsection
