@extends('layouts.app')

@section('title', '图片展廊')

@section('content')
    <div class="mdui-typo-display-2">图片展廊</div>

    <button class="mdui-btn mdui-color-theme-accent mdui-ripple" mdui-dialog="{target: '#new_document'}">新建图片</button>

    <br /><br />
    <div class="container">
        <div class="mdui-row">
            @foreach ($images as $image)
                <div class="mdui-col-sm-4">
                    <div class="mdui-card mdui-m-t-1 mdui-hoverable">
                        <div class="mdui-card-media">
                            <img src="#" />
                            <div class="mdui-card-media-covered">
                                <div class="mdui-card-primary">
                                    <div class="mdui-card-primary-title">{{ $image->name }}</div>
                                    <div class="mdui-card-primary-subtitle">
                                        读取量：{{ $image->times }} <br />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mdui-card-actions">
                            <button onclick="window.open('{{ route('documents.show', $document->id) }}')"
                                class="mdui-btn mdui-ripple">删除</button>
                            <button onclick="window.open('{{ route('documents.show', $document->id) }}')"
                                class="mdui-btn mdui-ripple">更改名称</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        })


    </script>

    {{ $images->links() }}

@endsection
