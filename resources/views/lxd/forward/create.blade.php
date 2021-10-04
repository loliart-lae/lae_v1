@extends('layouts.app')

@section('title', '新建转发')

@section('content')
    <div class="mdui-typo-display-2">新建转发</div>

    <p>端口转发 功能会将您的业务端口暴露至公网，请注意您的业务安全。</p>
    <form method="post" action="{{ route('forward.store', Request::route('lxd_id')) }}">
        @csrf
        <div class="mdui-row">
            <div class="mdui-col-xs-3">
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">容器内端口</label>
                    <input class="mdui-textfield-input" type="number" name="from" value="{{ old('from') }}" required />
                </div>
            </div>
            <div class="mdui-col-xs-3">
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">输出端口</label>
                    <input class="mdui-textfield-input" type="number" name="to" value="{{ old('to') }}" required />
                </div>
            </div>
            <div class="mdui-col-xs-3">
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">原因</label>
                    <input class="mdui-textfield-input" type="text" name="reason" value="{{ old('reason') }}" required />
                </div>
            </div>
            <div class="mdui-col-xs-3">
                <button type="submit" style="margin-top: 34px;"
                    class="mdui-btn mdui-color-theme-accent mdui-ripple">新建</button>
            </div>

        </div>



    </form>
@endsection
