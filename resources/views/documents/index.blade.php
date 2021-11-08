@extends('layouts.app')

@section('title', '文档列表')

@section('content')
    <div class="mdui-typo-display-2"><span id="doc-type"></span>文档</div>

    <button class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--document-create-dialog"
        mdui-dialog="{target: '#new_document'}">新建文档</button>
    &nbsp;&nbsp;
    @if (Request::route()->getName() != 'documents.my')
        @php($my = false)
        <a href="{{ route('documents.my') }}"
            class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--document-my">查看 我的文档</a>
        <script>
            $('#doc-type').html('社区')
        </script>
    @else
        @php($my = true)
        <a href="{{ route('documents.index') }}"
            class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--document-ommunity">查看 社区文档</a>
        <script>
            $('#doc-type').html('我的')
        </script>
    @endif
    &nbsp;&nbsp;

    <div style="width: 25%; display: inline-flex">
        <form method="get" action="{{ route('documents.search') }}">
            @csrf
            <div class="mdui-textfield mdui-textfield-expandable">
                <button onclick="return false" class="mdui-textfield-icon mdui-btn mdui-btn-icon"><i
                        class="mdui-icon material-icons-outlined">search</i></button>
                <input class="mdui-textfield-input" name="title" type="text" placeholder="Search" />

                <button type="submit" class="mdui-textfield-close mdui-btn mdui-btn-icon"><i
                        class="mdui-icon material-icons-outlined">done</i></button>

            </div>
        </form>
    </div>


    <div class="mdui-dialog" id="new_document">
        <div class="mdui-dialog-title">新建文档</div>
        <form method="post" action="{{ route('documents.store') }}">
            @csrf
            <div class="mdui-dialog-content">
                <div class="mdui-textfield">
                    <label class="mdui-textfield-label">标题</label>
                    <input class="mdui-textfield-input" name="title" type="text" />
                </div>

                <div class="mdui-textfield">
                    <label class="mdui-textfield-label">介绍</label>
                    <input class="mdui-textfield-input" name="description" type="text" />
                </div>
            </div>

            <div class="mdui-dialog-actions">
                <button type="submit" class="mdui-btn mdui-ripple" mdui-dialog-close>取消</button>
                <button type="submit" class="mdui-btn mdui-ripple">新建</button>
            </div>
        </form>
    </div>

    <br /><br />
    <div class="container">
        <div class="mdui-row" id="masonry">
            @foreach ($documents as $document)
                <div class="mdui-col-sm-4">
                    <div class="mdui-card mdui-m-t-1 mdui-hoverable">
                        <div class="mdui-card-media">
                            <img
                                src="{{ $document->image_url ?? 'https://i.loli.net/2021/09/11/mKfYd4cWSwNiLx1.jpg' }}" />
                            <div class="mdui-card-media-covered">
                                <div class="mdui-card-primary">
                                    <div class="mdui-card-primary-title">{{ $document->title }}</div>
                                    <div class="mdui-card-primary-subtitle">
                                        {{ $document->description }}<br />
                                        访问量：{{ $document->views }} <br />
                                        作者：{{ $document->user->name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mdui-card-actions">
                            @if ($document->user_id == Auth::id())
                                <button onclick="window.open('{{ route('documents.show', $document->id) }}')"
                                    class="mdui-btn mdui-ripple">浏览</button>
                                <button onclick="window.open('{{ route('documents.edit', $document->id) }}')"
                                    class="mdui-btn mdui-ripple">编辑</button>
                                @if ($my)
                                    <button onclick="updateVisibility({{ $document->id }})"
                                        id="btn_{{ $document->id }}" class="mdui-btn mdui-ripple">
                                        @if ($document->visibility)
                                            公开
                                            <script>
                                                var item_{{ $document->id }}_visibility = 0
                                            </script>
                                        @else
                                            自己可见
                                            <script>
                                                var item_{{ $document->id }}_visibility = 1
                                            </script>
                                        @endif
                                    </button>
                                    <button onclick="$('#f-{{ $document->id }}').submit()"
                                        class="mdui-btn mdui-ripple">删除</button>
                                    <form id="f-{{ $document->id }}" method="post"
                                        action="{{ route('documents.destroy', $document->id) }}">@csrf @method('DELETE')
                                    </form>
                                @endif
                            @else
                                <button onclick="window.open('{{ route('documents.show', $document->id) }}')"
                                    class="mdui-btn mdui-ripple">浏览</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <script>
        var $container = $('#masonry')

        $container.masonry({
            itemSelector: '.poll',
        })

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        })

        function updateVisibility(id) {
            $.ajax({
                type: 'PUT',
                url: id,
                data: {
                    'visibility': eval('item_' + id + '_visibility')
                },
                dataType: 'json',
                success: function(data) {
                    var vis = eval('item_' + id + '_visibility')
                    if (data.status == 'success') {
                        mdui.snackbar({
                            message: '可见性切换成功。',
                            position: 'bottom'
                        });
                        if (vis == 1) {
                            eval('item_' + id + '_visibility = 0')
                            $('#btn_' + id).html('公开')
                        } else {
                            eval('item_' + id + '_visibility = 1')
                            $('#btn_' + id).html('自己可见')
                        }

                    } else {
                        mdui.snackbar({
                            message: '可见性切换失败。',
                            position: 'bottom'
                        });
                    }
                },
                error: function(data) {
                    mdui.snackbar({
                        message: '暂时无法切换可见性。',
                        position: 'bottom'
                    });
                }
            })
        }
    </script>

    {{ $documents->links() }}

@endsection
