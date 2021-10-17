@extends('layouts.app')

@section('title', '回复')

@section('content')
    <style>
        .editormd-html-preview>h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-top: 0 !important
        }

        .editormd-html-preview img {
            border-radius: 2px
        }

    </style>
    <div class="mdui-typo">
        <div class="mdui-card" style="margin-top: 5px;box-shadow: none;background: transparent">
            <div class="mdui-card-header">
                <img class="mdui-card-header-avatar"
                    src="{{ config('app.gravatar_url') }}/{{ md5(strtolower($status->user->email)) }}" />
                <div class="mdui-card-header-title">
                    @if (is_null($status->user->website))
                        {{ $status->user->name }}
                    @else
                        <a href="{{ $status->user->website }}">{{ $status->user->name }}</a>
                    @endif
                    <small> /
                        {{ $status->created_at->diffForHumans() }}</small>
                </div>
                <div class="mdui-card-header-subtitle">{{ $status->user->bio ?? '咕噜咕噜咕噜' }}</div>
            </div>
            <div class="mdui-card-content mdui-p-t-1">
                <div id="log_{{ $status->id }}_spinner" class="mdui-spinner"></div>
                <div id="log_{{ $status->id }}"></div>
                <textarea id="log_{{ $status->id }}_content" style="display:none;">{!! e($status->content) !!}</textarea>
                <script>
                    setTimeout(function() {
                        $('#log_{{ $status->id }}_spinner').remove();
                        var log_view
                        log_view = editormd.markdownToHTML("log_{{ $status->id }}", {
                            markdown: $('#log_{{ $status->id }}_content').html(),
                            tocm: true,
                            emoji: true,
                            taskList: true,
                        });
                    }, 500)
                </script>
            </div>
            <div class="mdui-card-actions">
                <button id="status_{{ $status->id }}" onclick="toggleLike({{ $status->id }})"
                    class="mdui-btn mdui-ripple mdui-btn-icon">
                    @if (is_null($status->like))
                        <i class="mdui-icon material-icons umami--click--like-from-comment umami--click--like"
                            style="color: unset">star_border</i>
                    @elseif ($status->like->is_liked)
                        <i style="color:#36a6e8"
                            class="mdui-icon material-icons umami--click--unlike-from-comment umami--click--unlike">star</i>
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
                        <button type="submit" class="mdui-btn mdui-ripple umami--click--status-delete">删除</button>
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
                <div class="mdui-col-xs-12 mdui-p-y-2 smoove" data-move-x="-{{ rand(100, 200) }}px">
                    <div class="mdui-col-xs-2 mdui-col-sm-1">
                        <img class="mdui-img-circle mdui-center"
                            src="{{ config('app.gravatar_url') }}/{{ md5(strtolower($status_reply->user->email)) }}">
                    </div>
                    <div class="mdui-col-xs-11">
                        {{ $i }}.
                        @if (is_null($status_reply->user->website))
                            {{ $status_reply->user->name }}
                        @else
                            <a target="_blank"
                                href="{{ $status_reply->user->website }}">{{ $status_reply->user->name }}</a>
                        @endif
                        说：@if ($status_reply->user->id == Auth::id()) <a
                                onclick="$('#statusReply-{{ $i }}').submit()" href="#"
                                class="umami--click--delete-status-reply">删除</a>
                            <form id="statusReply-{{ $i }}" style="display: none" method="post"
                                action="{{ route('status.reply.destroy', $status_reply->id) }}">@csrf @method('DELETE')
                            </form>
                        @else
                            <br />
                        @endif
                        <div id="reply_{{ $status_reply->id }}"></div>
                        <textarea id="reply_{{ $status_reply->id }}_content"
                            style="display:none;">{!! e($status_reply->content) !!}</textarea>
                        <script>
                            $(document).ready(function() {
                                var log_view
                                $('#log_{{ $status->id }}').html(null)
                                log_view = editormd.markdownToHTML("reply_{{ $status_reply->id }}", {
                                    markdown: $('#reply_{{ $status_reply->id }}_content').html(),
                                    tocm: true,
                                    emoji: true,
                                    taskList: true,
                                });
                            })
                        </script>
                    </div>
                </div>
            @endforeach
        </div>
        <br /> <br />
        <div>
            {{ $status_replies->links() }}
        </div>

        <form id="replyForm" class="mdui-m-t-5" method="POST" action="{{ route('status.reply', $status->id) }}">
            @csrf
            @method('PUT')
            <div class="mdui-textfield">
                <textarea class="mdui-textfield-input umami--input--status-reply" rows="4" name="content"
                    placeholder="保持友善～" maxlength="140" required></textarea>
            </div>
            <button type="submit"
                class="mdui-btn mdui-ripple mdui-color-theme umami--click--status-reply-confirm">回复</button>
        </form>

    </div>




    <script>
        $('.smoove').smoove({
            offset: '3%'
        })

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
