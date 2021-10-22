@extends('layouts.app')

@section('title', '编辑 ' . $field->name)

@section('content')
    <div class="mdui-typo-display-2">编辑 {{ $field->name }}</div>

    <form method="post" action="{{ route('field.update', $field->id) }}">
        @csrf
        @method('PUT')
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">名称</label>
            <input class="mdui-textfield-input" type="text" name="name" value="{{ $field->name }}" />
        </div>

        <div class="mdui-textfield">
            <textarea class="mdui-textfield-input" rows="8" placeholder="content"
                name="content">{{ $field->content }}</textarea>
        </div>

        {{-- <div class="mdui-row-md-4 mdui-m-b-2">
            <div class="mdui-col">
                <label class="mdui-checkbox">
                    <input type="checkbox" name="is_public" value="1" @if ($field->is_public) checked @endif />
                    <i class="mdui-checkbox-icon"></i>
                    是否公开
                </label>
            </div>
        </div> --}}


        <button type="submit"
            class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--new-field-update">更新</button>
    </form>

@endsection
