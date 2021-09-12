@extends('layouts.app')

@section('title', '下载')

@section('content')
    <div class="mdui-typo-display-2">下载</div><br>
    <div class="mdui-typo-headline-opacity">{{ $data->name }}</div>
    <br />
    <div class="mdui-typo">
        <p>你正在通过 LAE 下载来自 “{{ $data->projectName }}” 项目的文件。</p>
        <p>你将要下载的文件类型为：{{ $data->mimetype }}，
            <br />此文件大小为 {{ $data->size }} Mib，
            <br />
            下载需要消耗 {{ $data->cost }} 账户积分，
            下载后你还剩余 {{ $data->left }} 账户积分。
        </p>


        @if ($data->left < 0)
            <button disabled class="mdui-btn mdui-color-theme-accent mdui-ripple">你的帐户中没有足够的积分来下载</button>
        @else
            <a href="#" class="mdui-btn mdui-color-theme-accent mdui-ripple" onclick="window.open('{{ route('download.download', $data->fileName) }}');">下载 {{ $data->name }}</a>
        @endif
    </div>

@endsection