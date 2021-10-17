@extends('layouts.app')

@section('title', '字段')

@section('content')
    <div class="mdui-typo-display-2">字段</div>

    <a href="{{ route('field.create') }}" class="mdui-btn mdui-color-theme-accent mdui-ripple">新建 字段</a>
    <br /><br />

    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>创建时间</th>
                    {{-- <th>开放性</th> --}}
                    <th>操作</th>
                </tr>
            </thead>
            <tbody class="mdui-typo">
                @php($i = 1)
                @foreach ($fields as $field)
                    <tr>
                        <td nowrap="nowrap">{{ $i++ }}</td>
                        <td nowrap="nowrap">{{ $field->name }}</td>
                        <td nowrap="nowrap">{{ $field->created_at }}</td>
                        {{-- <td nowrap="nowrap">
                            @if ($field->is_public)
                            已公开
                            @else
                            未公开
                            @endif
                        </td> --}}
                        <td nowrap="nowrap">
                            <a href="#"
                                onclick="if (confirm('删除后，该字段将无法访问。')) { $('#f-{{ $i }}').submit() }">删除</a>
                            |
                            <a href="{{ route('field.edit', $field->id) }}">编辑</a>
                            <form style="visibility: hidden" id="f-{{ $i }}" method="post"
                                action="{{ route('field.destroy', $field->id) }}">@csrf
                                @method('DELETE')</form>
                        </td>
                    </tr>
                @endforeach
                @if ($i > 10)
                    <tr>
                        <td colspan="3" class="mdui-text-center">
                            <a href="{{ route('field.create') }}">新建 字段</a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>


@endsection
