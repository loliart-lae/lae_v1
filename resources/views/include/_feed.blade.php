@if ($feed_items->count() > 0)

    <div id="masonry" class="mdui-row">

        @foreach ($feed_items as $status)
            <div class="poll mdui-col-sm-4 mdui-col-xs-12 mdui-m-t-1">
                <div class="mdui-card" style="margin-top: 5px">
                    <div class="mdui-card-header">
                        <img class="mdui-card-header-avatar"
                            src="{{ config('app.gravatar_url') }}/{{ md5($status->user->email) }}" />
                        <div class="mdui-card-header-title">{{ $status->user->name }} <small> /
                                {{ $status->created_at->diffForHumans() }}</small>
                            <div style="display: inline;
                                position: absolute;
                                right: 16px;
                                margin-top: 3px;cursor: pointer" class="follow_{{ $status->user->id }}">
                                @if ($display ?? '' != 0)
                                    @if ($status->user->id == Auth::id())
                                        <i mdui-tooltip="{content: '这是你'}"
                                            class="mdui-text-color-theme mdui-icon material-icons" onclick="$(this).addClass('animate__animated animate__tada')">account_circle</i>
                                    @elseif (in_array($status->user->id, $ids))
                                        <i onclick="toggleFollow({{ $status->user->id }})"
                                            class="mdui-text-color-theme mdui-icon material-icons" onclick="$(this).addClass('animate__animated animate__pulse animate__infinite')">favorite</i>
                                    @else
                                        <i onclick="toggleFollow({{ $status->user->id }})"
                                            class="mdui-text-color-black-secondary mdui-icon material-icons" onclick="$(this).addClass('animate__animated animate__pulse animate__infinite')>favorite</i>
                                    @endif
                                @endif

                            </div>
                        </div>
                        <div class="mdui-card-header-subtitle">{{ $status->user->bio ?? '啊吧啊吧啊吧' }}</div>
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
                        <button onclick="window.location.href = '{{ route('status.show', $status->id) }}'"
                            class="mdui-btn mdui-ripple">@php($replies = count($status->replies)) @if ($replies > 0) {{ $replies }}条 @else 没有 @endif
                            回复</button>
                        @can('destroy', $status)
                            <form style="display: initial;" action="{{ route('status.destroy', $status->id) }}"
                                method="POST" onsubmit="return confirm('确定要删除吗？删除后动态将会永远被埋没到长河中。');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="mdui-btn mdui-ripple">删除</button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <script>
        $(function() {
            var $container = $('#masonry');
            $container.masonry({
                itemSelector: '.poll',

            });
        });
    </script>
    <div class="mdui-m-t-2 mdui-m-b-4">
        {{ $feed_items->links() }}
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

        function toggleFollow(id) {
            $.ajax({
                type: 'PUT',
                url: `{{ route('user.toggleFollow') }}?id=${id}`,
                data: {
                    'toggle': 'toggle'
                },
                dataType: 'json',
                success: function(data) {
                    if (data[0] == true) {
                        $('.follow_' + id).html(
                            `<i onclick="toggleFollow(${id})"
                                            class="follow_${id} mdui-text-color-theme mdui-icon material-icons animate__heartBeat">favorite</i>`
                        )
                    } else {
                        $('.follow_' + id).html(
                            `<i onclick="toggleFollow(${id})" class="follow_${id} mdui-text-color-black-secondary mdui-icon material-icons animate__animated animate__flip">favorite</i>`
                        )
                    }

                    if (data['msg'] != undefined) {
                        mdui.snackbar({
                            message: data['msg'],
                            position: 'bottom'
                        })
                    }
                },
                error: function(data) {
                    mdui.snackbar({
                        message: '暂时无法切换关注状态。',
                        position: 'bottom'
                    })
                }
            })
        }
    </script>

@else
    <p>还没有人出现在你的时间长河中。</p>
@endif
