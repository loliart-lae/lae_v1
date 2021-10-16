@extends('layouts.app')

@section('title', '新的快捷访问')

@section('content')
    <div class="mdui-typo-display-2">创建快捷访问</div>
    <p>快捷访问可以设置访问网址时的跳转的URI。</p>

    <form method="post" action="{{ route('fastVisit.store') }}">
        @csrf
        <x-choose-project-form />
        <div class="mdui-row mdui-p-t-4 mdui-p-b-2 mdui-p-l-1">
            <span class="mdui-typo-headline">选择域名</span>
        </div>

        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>域名</th>
                        <th>广告奖励</th>
                        <th>选择</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 1)
                    @foreach ($domains as $domain)
                        <tr>
                            <td nowrap="nowrap">{{ $i++ }}</td>
                            <td nowrap="nowrap">{{ $domain->domain }}</td>
                            <td nowrap="nowrap">{{ $domain->balance }}</td>

                            <td nowrap="nowrap">
                                <label class="mdui-radio">
                                    <input type="radio" value="{{ $domain->id }}" name="domain_id"
                                        @if ($i == 2) checked @endif required />
                                    <i class="mdui-radio-icon"></i>

                                </label>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
            <span class="mdui-typo-headline">名称</span>
            <p>名称用于辨别。</p>
            <div class="mdui-textfield mdui-textfield-floating-label">
                <label class="mdui-textfield-label">名称</label>
                <input class="mdui-textfield-input" type="text" name="name" value="{{ old('name') }}" required />
            </div>
        </div>

        <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
            <span class="mdui-typo-headline">目标地址</span>
            <p>访问入口后，跳转到哪里？</p>
            <div class="mdui-textfield">
                <label class="mdui-textfield-label">地址</label>
                <input class="mdui-textfield-input" type="text" name="uri" placeholder="https://" value="{{ old('uri') }}"
                    required />
                <div class="mdui-textfield-helper">如果不添加协议且不开启广告，则可能会无法正常使用。</div>
            </div>
        </div>

        <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
            <span class="mdui-typo-headline">广告开关</span>
            
            <div class="mdui-p-t-2">
                <span>如果启用，你的入口将不会立即跳转。</span>
                <br />
                启用广告：<label class="mdui-switch">
                    <input type="checkbox" name="enable_ad" value="1" checked />
                    <i class="mdui-switch-icon"></i>
                </label>
            </div>
        </div>

        <div class="mdui-row mdui-p-y-2">
            <button type="submit" class="mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple">新建</button>
        </div>
        
        <div class="mdui-typo" style="text-align: right;margin-top: 10px"><small class="mdui-clearfix">
            此功能完全免费。<br />
            启用广告后所产生的积分收益将会发放到“访问入口”对应的“项目”中。
        </small></div>
    </form>

@endsection
