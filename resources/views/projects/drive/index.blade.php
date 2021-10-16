@extends('layouts.app')

@section('title', '网络存储')

@section('content')
    <h1 class="mdui-text-color-theme">{{  $project_name  }} 中的网络存储</h1>
    <br />

    <a href="{{ route('storage.index', Request::route('project_id')) }}" class="mdui-btn mdui-color-theme-accent mdui-ripple">存储主页</a>
    &nbsp;&nbsp;
    <a href="{{ route('projects.show', Request::route('project_id')) }}" class="mdui-btn mdui-color-theme-accent mdui-ripple">项目主页</a>


    <h2>{{ Request::get('path') }}</h2>

    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>内部 ID</th>
                    <th>文件名称</th>
                    <th>类型</th>
                    <th>大小</th>
                    <th>当前可选择的操作</th>
                </tr>
            </thead>
            <tbody class="mdui-typo">
                <tr>
                    <td colspan="11" class="mdui-text-center">
                        <a class="umami--click--storage-new" href="{{ route('storage.create', Request::route('project_id')) }}?path={{ $path ?? '' }}">新建文件夹
                            或者 上传文件</a>
                    </td>
                </tr>
                @php($i = 1)
                @foreach ($drive as $file)
                    <tr>
                        <td nowrap="nowrap">{{ $i++ }}</td>
                        <td nowrap="nowrap">{{ $file->id }}</td>
                        <td nowrap="nowrap">@if (is_null($file->mimetype))
                            <a href="{{ route('storage.show', Request::route('project_id')) }}?path={{ $file->path }}">{{ $file->name }}</a>
                            @else
                            <a href="#" onclick="window.open('{{ route('download.view', $file->fileName) }}');">{{ $file->name }}</a>
                @endif
                </td>
                <td nowrap="nowrap">
                    @if (!is_null($file->mimetype))
                        {{ $file->mimetype }}
                    @else
                        文件夹
                    @endif
                </td>
                <td nowrap="nowrap">
                    @if (!is_null($file->mimetype))
                        {{ $file->size }} MiB
                    @else
                        {{ __('📁') }}
                    @endif
                </td>
                <td nowrap="nowrap"><a title="{{ __('Your all data will be lost.') }}" href="#"
                        onclick="$('#delete_item_{{ $file->id }}').submit()">删除</a>
                    @if (!is_null($file->mimetype))
                        <span> | <a href="#"
                                onclick="window.open('{{ route('download.view', $file->fileName) }}');">下载</a>
                    @endif
                    <form id="delete_item_{{ $file->id }}" method="POST"
                        action="{{ route('storage.destroy', [Request::route('project_id'), $file->id]) }}">
                        @csrf
                        @method("DELETE")
                    </form>
                </td>



                </tr>
                @endforeach
                @if ($i > 10)
                    <tr>
                        <td colspan="6" class="mdui-text-center">
                            <a
                                href="{{ route('storage.create', Request::route('project_id')) }}?path={{ $path ?? '' }}">新建文件夹
                                或者 上传文件</a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

@endsection
