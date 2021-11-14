@extends('layouts.app')

@section('title', $user->name)

@section('content')
<style>
    .ae-title>h1,
    h2,
    h3,
    h4,
    h5 {
        font-weight: 400 !important;
        font-size: 20px !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .ae-title>p {
        font-size: 15px !important;
        margin: 0;
        padding: 0;
    }
</style>
<div>
    <div class="mdui-row mdui-typo">
        <div class="mdui-col-xs-12 mdui-col-sm-5">
            <img class="mdui-img-circle mdui-hoverable animate__bounceIn mdui-center"
                src="{{ config('app.gravatar_url') }}/{{ md5(strtolower($user->email)) }}?s=192">

        </div>

        <div class="mdui-col-xs-12 mdui-col-sm-7">
            <div class="mdui-typo-display-1">{{ $user->name }}</div>

            <p>积分: <span>{{ $user->balance }}</span></p>

            <div class="mdui-textfield">
                <label class="mdui-textfield-label">签名</label>
                <input class="mdui-textfield-input" type="text" readonly name="bio" value="{{ $user->bio }}" />
            </div>

            <div class="mdui-textfield">
                <label class="mdui-textfield-label">网站</label>
                <input class="mdui-textfield-input" type="url" readonly name="website" value="{{ $user->website }}"
                    placeholder="http(s)://" />
            </div>
        </div>


    </div>

    <div class="mdui-row">
        <div class="mdui-col-xs-12">
            <ul class="mdui-list">
                @php($created_at = null)
                @foreach ($status as $s)
                @if ($s->created_at->toDateString() != $created_at)
                <div class="mdui-typo">
                    <h3>{{ $s->created_at->toDateString() }}</h3>
                </div>
                @php($created_at = $s->created_at->toDateString())

                @endif
                <li class="mdui-list-item mdui-ripple smoove" data-move-x="-{{ rand(100, 200) }}px">
                    <div class="mdui-list-item-avatar" style="padding:5px;position:absolute;top:5px;overflow:hidden">{{
                        $s->created_at->diffForHumans() }}</div>
                    <div class="mdui-list-item-icon" style="min-width: 50px;height: 65px;color:transparent"></div>

                    <a href="@auth{{ route('status.show', $s->id) }}@else{{ route('timeRiver.show', $s->id) }}@endauth"
                        class="mdui-list-item-content ae-title mdui-typo" id="log_{{ $s->id }}">{{ $s->content }}
                    </a>
                </li>
                <div id="log_{{ $s->id }}"></div>
                <textarea id="log_{{ $s->id }}_content" style="display:none;">{!! e($s->content) !!}</textarea>
                <script>
                    $(function() {
                                var log_view
                                $('#log_{{ $s->id }}').html(null)
                                log_view = editormd.markdownToHTML("log_{{ $s->id }}", {
                                    markdown: $('#log_{{ $s->id }}_content').html(),
                                    tocm: true,
                                    emoji: true,
                                    taskList: true,
                                })
                            })
                </script>
                @endforeach
            </ul>

            {{ $status->links() }}
        </div>
    </div>

</div>

<script>
    $('.smoove').smoove({
        offset: '3%'
    })
</script>

@endsection
