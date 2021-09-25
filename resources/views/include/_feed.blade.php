@if ($feed_items->count() > 0)
    <div class="mdui-row">

        @foreach ($feed_items as $status)
            <div class="mdui-col-xs-6">
                <div class="mdui-card" style="margin-top: 5px">
                    <div class="mdui-card-header">
                        <img class="mdui-card-header-avatar"
                            src="{{ config('app.gravatar_url') }}/{{ md5($status->user->email) }}" />
                        <div class="mdui-card-header-title">{{ $status->user->name }} <small> /
                                {{ $status->created_at->diffForHumans() }}</small></div>
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
    <div class="mdui-m-t-2 mdui-m-b-4">
        {{ $feed_items->links() }}
    </div>

@else
    <p>还没有人出现在你的时间长河中。</p>
@endif
