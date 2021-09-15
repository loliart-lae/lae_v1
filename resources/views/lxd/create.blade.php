@extends('layouts.app')

@section('title', '新建容器')

@section('content')
    <h1 class="mdui-text-color-theme">新建容器</h1>
    <p>在选定的项目中新建容器。</p>
    <br />
    <form method="post" action="{{ route('lxd.store') }}">
        @csrf
        <span class="mdui-typo-headline">选择项目</span>
        <br />
        <br />
        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>项目积分</th>
                        <th>选择</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 1)
                    @foreach ($projects as $project)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $project->project->name }}</td>
                            <td>{{ $project->project->balance }}</td>

                            <td>
                                <label class="mdui-radio">
                                    <input type="radio" value="{{ $project->project->id }}" name="project_id" @if ($i == 2) checked @endif required />
                                    <i class="mdui-radio-icon"></i>

                                </label>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <br />
        <br />
        <span class="mdui-typo-headline">选择地区服务器</span>
        <p class="mdui-typo-subheading">地区服务器影响着访问速度以及连通性，稳定性，以及基础价格。</p>
        <br />

        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>基础价格</th>
                        <th>转发价格</th>
                        <th>带宽限制</th>
                        <th>选择</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 1)
                    @foreach ($servers as $server)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $server->name }}</td>
                            <td>{{ $server->price }}</td>
                            <td>{{ $server->forward_price }}</td>
                            <td>{{ $server->network_limit }} Mbps</td>

                            <td>
                                <label class="mdui-radio">
                                    <input type="radio" value="{{ $server->id }} "name="server_id" @if ($i == 2) checked @endif required />
                                    <i class="mdui-radio-icon"></i>

                                </label>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <br />
        <br />
        <br />
        <span class="mdui-typo-headline">选择镜像</span>
        <p class="mdui-typo-subheading">不同镜像拥有着不同操作系统以及操作方式。</p>
        <br />
        <div class="mdui-table-fluid">
            <table class="mdui-table mdui-table-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>镜像</th>
                        <th>源</th>
                        <th>选择</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 1)
                    @foreach ($images as $image)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $image->name }}</td>
                            <td>{{ $image->image }}</td>

                            <td>
                                <label class="mdui-radio">
                                    <input type="radio" value="{{ $image->id }}" name="image_id" @if ($i == 2) checked @endif required />
                                    <i class="mdui-radio-icon"></i>

                                </label>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <br />
        <br />
        <br />
        <span class="mdui-typo-headline">选择容器模板</span>
        <p class="mdui-typo-subheading">容器模板影响着计费，计费每 1 分钟进行一次。</p>
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
                        <th>消耗积分</th>
                        {{-- <th>月付预估</th> --}}
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
                            {{-- <td>{{ $template->price * 44640 / 100 }} 元 / 月</td> --}}
                            <td>
                                <label class="mdui-radio">
                                    <input type="radio" value="{{ $template->id }}" name="template_id" @if ($i == 2) checked @endif required />
                                    <i class="mdui-radio-icon"></i>

                                </label>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <br /> <br />
        <span class="mdui-typo-headline">Root 密码</span>
        <p>只允许字母、数字，短破折号（-）和下划线（_），可到容器内再次修改。</p>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">密码</label>
            <input class="mdui-textfield-input" type="password" name="password" value="{{ old('password') }}" required />
        </div>

        <br /> <br />
        <span class="mdui-typo-headline">最后，设置容器名称</span>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">名称</label>
            <input class="mdui-textfield-input" type="text" name="name" value="{{ old('name') }}" required />
        </div>

        <br /> <br />

        <button type="submit" class="mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple">新建</button>

        <br /><br />
        <div class="mdui-typo" style="text-align: right;margin-top: 10px"><small class="mdui-clearfix">注意：每分钟价格 = 地区服务器基础价格 + 容器模板价格 + 端口转发。<br />Linux 容器默认用户名为 root，并且是无特权容器，不支持 Docker。<br />带宽均为共享带宽，如果带宽有调整，将会即时生效。<br />如果你的容器配置和模板配置不符，可以对容器模板进行升配/降配，然后再修改回去即可刷新。</small></div>
    </form>
@endsection
