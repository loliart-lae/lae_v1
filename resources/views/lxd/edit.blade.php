@extends('layouts.app')

@section('title', '更改项目模板')

@section('content')
    <h1 class="mdui-text-color-theme">更改项目模板</h1>
    <br />
    <form method="post" action="{{ route('lxd.update', $id) }}">
        @csrf
        @method('PUT')

        <br />
        <span class="mdui-typo-headline">选择容器模板</span>
        <p class="mdui-typo-subheading">容器模板影响着计费。计费每1分钟进行一次。</p>
        <br />
        <br />
        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>CPU</th>
                        <th>内存</th>
                        <th>硬盘</th>
                        <th>价格</th>
                        <th>月付预估</th>
                        <th>选择</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 1)
                    @foreach ($templates as $template)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $template->name }}</td>
                            <td>{{ $template->cpu }}</td>


                            <td>{{ $template->mem }} M</td>
                            <td>{{ $template->disk }} G</td>
                            <td>{{ $template->price }}</td>
                            <td>{{ $template->price * 44640 }} 元 / 月</td>
                            <td>
                                <label class="mdui-radio">
                                    <input type="radio" value="{{ $template->id }}" name="template_id" @if ($template->id == $selected_template) checked @endif required />
                                    <i class="mdui-radio-icon"></i>

                                </label>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <br /><br />
        <button type="submit" class="mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple">修改</button>


    </form>
@endsection
