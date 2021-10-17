@extends('layouts.app')

@section('title', '新建 字段')

@section('content')
    <div class="mdui-typo-display-2">新建字段</div>

    <form method="post" action="{{ route('field.store') }}">
        @csrf
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">名称</label>
            <input class="mdui-textfield-input" type="text" name="name" />
        </div>

        <div class="mdui-textfield">
            <textarea class="mdui-textfield-input" rows="8" placeholder="content" name="content"></textarea>
        </div>

        {{-- <div class="mdui-row-md-4 mdui-m-b-2">
            <div class="mdui-col">
                <label class="mdui-checkbox">
                    <input type="checkbox" name="is_public" value="1" />
                    <i class="mdui-checkbox-icon"></i>
                    是否公开
                </label>
            </div>
        </div> --}}

        <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--new-field-store">新建</button>
    </form>

@endsection
