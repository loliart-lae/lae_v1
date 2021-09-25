@extends('layouts.app')

@section('title', '评论系统')

@section('content')
    <div class="mdui-typo">
        {{-- <span class="mdui-typo-headline">{{ $status-> }}。}}</span> --}}
        <br />
        <span class="mdui-typo-headline-opacity hitokoto_text"></span>
        <br /><br />

        <form method="POST" action="{{ route('status.store') }}">
            @csrf
            <div class="mdui-textfield">
                <textarea class="mdui-textfield-input hitokoto_placeholder" name="content" maxlength="140" rows="4"
                    required></textarea>
                <div class="mdui-textfield-helper">此刻在想些什么？</div>
            </div>
            <button class="mdui-btn mdui-color-theme mdui-ripple">发布</button>
        </form>



        <h4>我的时间河&nbsp;|&nbsp;<a href="{{ route('global') }}">全站时间河</a></h4>
        @include('include._feed')

    </div>

    <script>
        fetch('https://v1.hitokoto.cn?c=k')
            .then(response => response.json())
            .then(data => {
                // 还是 JQ 来的方便
                $('.hitokoto_text').html(data.hitokoto)
                $('.hitokoto_placeholder').attr('placeholder', data.hitokoto)
            })
            .catch(console.error)
    </script>
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
