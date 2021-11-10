@extends('layouts.app')

@section('title', '新建 云虚拟机')

@section('content')
    <div class="mdui-typo-display-2">新建 云虚拟机</div>

    <form method="post" action="{{ route('virtualMachine.store') }}">
        @csrf

        @php($i = 1)
        <div class="mdui-tab mdui-tab-scrollable mdui-m-t-1" id="page-tab" mdui-tab>
            <a href="#choose-project" class="mdui-ripple">选择项目</a>
            <a href="#choose-server" class="mdui-ripple">选择服务器</a>
            <a href="#choose-template" class="mdui-ripple">选择模板</a>
            <a href="#choose-image" class="mdui-ripple" id="tab-image">选择镜像</a>
            <a href="#choose-set" class="mdui-ripple">基本设定</a>
        </div>

        <div id="choose-project">
            <x-choose-project-form />
        </div>

        <div id="choose-server">
            <div class="mdui-row mdui-p-t-4 mdui-p-b-2 mdui-p-l-1">
                <span class="mdui-typo-headline">选择地区服务器</span>
                <p class="mdui-typo-subheading">地区服务器影响着访问速度以及连通性，稳定性，以及基础价格。</p>
            </div>

            <div class="mdui-table-fluid">
                <table class="mdui-table mdui-table-hoverable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>名称</th>
                            <th>基础价格(积分/分钟)</th>
                            <th>转发价格(积分/分钟)</th>
                            <th>带宽限制</th>
                            <th>月预估</th>
                            <th>选择</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($servers) > 0)
                            @php($i = 1)
                            @foreach ($servers as $server)
                                <tr>
                                    <td nowrap>{{ $i++ }}</td>
                                    <td nowrap>{{ $server->name }}</td>
                                    <td nowrap>{{ $server->price }}</td>
                                    <td nowrap>{{ $server->forward_price }}</td>
                                    <td nowrap>{{ $server->network_limit }} Mbps</td>
                                    <td nowrap>
                                        {{ number_format(($server->price * 44640) / config('billing.exchange_rate'), 2) }}
                                        元 /
                                        月</td>

                                    <td>
                                        <label class="mdui-radio">
                                            <input type="radio" value="{{ $server->id }}" name="server_id"
                                                @if ($i == 2) checked @endif required />
                                            <i class="mdui-radio-icon"></i>

                                        </label>
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="mdui-text-center">服务器均已售罄</td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>

        <div id="choose-template">
            <div class="mdui-row mdui-p-t-4 mdui-p-b-2 mdui-p-l-1">
                <span class="mdui-typo-headline">选择 配置模板</span>
                <p class="mdui-typo-subheading">配置模板影响着计费，计费每 1 分钟进行一次。</p>
            </div>
            <div class="mdui-table-fluid">
                <table class="mdui-table mdui-table-hoverable">
                    <thead>
                        <tr>
                            <th>名称</th>
                            <th>CPU</th>
                            <th>内存</th>
                            <th>硬盘</th>
                            <th>硬盘读速度</th>
                            <th>硬盘写速度</th>
                            <th>网络限制</th>
                            <th>积分/分钟</th>
                            <th>月预估</th>
                            <th>选择</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 0)
                        @foreach ($templates as $template)
                            @php($i++)
                            <tr>
                                <td nowrap>{{ $template->name }}</td>
                                <td nowrap>{{ $template->cpu }}</td>
                                <td nowrap>{{ $template->memory }} M</td>
                                <td nowrap>{{ $template->disk }} G</td>
                                <td nowrap>{{ $template->disk_read }} MB/s</td>
                                <td nowrap>{{ $template->disk_write }} MB/s</td>
                                <td nowrap>{{ $template->network_limit }} MB/s</td>
                                <td nowrap>{{ $template->price }}</td>
                                <td nowrap>
                                    {{ number_format(($template->price * 44640) / config('billing.exchange_rate'), 2) }}
                                    元 /
                                    月
                                </td>

                                <td>
                                    <label class="mdui-radio">
                                        <input type="radio" value="{{ $template->id }}" name="template_id"
                                            @if ($i == 1) checked @endif required />
                                        <i class="mdui-radio-icon"></i>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div id="choose-image">
            <div class="mdui-row mdui-p-t-4 mdui-p-b-2 mdui-p-l-1">
                <span class="mdui-typo-headline">选择镜像</span>
            </div>
            <div class="mdui-table-fluid">
                <table class="mdui-table mdui-table-hoverable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>镜像</th>
                            <th>选择</th>
                        </tr>
                    </thead>
                    <tbody id="images">
                        <tr>
                            <td colspan="3" class="mdui-text-center" id="image-ask"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            document.getElementById('tab-image').addEventListener('show.mdui.tab', function() {
                $('#images').html(`<tr>
                            <td colspan="3" class="mdui-text-center" id="image-ask">正在询问镜像...</td>
                        </tr>`)
                $.ajax({
                    url: 'getImage/' + $('input[name="server_id"]:checked').val(),
                    success: function(data) {
                        $('#image-ask').remove()
                        for (let i in data) {
                            $('#images').append(`
                            <tr>
                                <td nowrap>${i}</td>
                                <td nowrap>${data[i].volid}</td>
                                <td> <label class="mdui-checkbox">
                                        <input type="checkbox" value="${i}" name="image_id[]" />
                                        <i class="mdui-checkbox-icon"></i>
                                    </label></td>
                            </tr>
                            `)
                        }
                    },
                    error: function(data) {
                        $('#image-ask').html('无法询问镜像。')
                    }
                })
            })
        </script>


        <div id="choose-set">


            <h1 class="mdui-typo-headline mdui-m-t-2">选择 BIOS</h1>
            <br />

            <div>
                <label class="mdui-radio">
                    <input type="radio" name="bios" value="0" checked />
                    <i class="mdui-radio-icon"></i>
                    SeaBIOS (Legacy)
                </label>

                <label class="mdui-radio mdui-m-l-4">
                    <input type="radio" name="bios" value="1" />
                    <i class="mdui-radio-icon"></i>
                    OVMF (UEFI)
                </label>
            </div>

            <div class="mdui-row mdui-p-t-4 mdui-p-l-1">
                <span class="mdui-typo-headline">给这台云虚拟机想个名字。</span>
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">名称</label>
                    <input class="mdui-textfield-input" type="text" name="name" value="{{ old('name') }}" required />
                </div>
            </div>


            <div class="mdui-row-md-4 mdui-m-b-2 mdui-m-t-1">
                <div class="mdui-col">
                    <label class="mdui-checkbox">
                        <input type="checkbox" name="start_after_created" value="1" />
                        <i class="mdui-checkbox-icon"></i>
                        创建后启动
                    </label>
                </div>
            </div>


            <div class="mdui-row mdui-p-y-2">
                <button type="submit"
                    class="mdui-float-right mdui-btn mdui-color-theme-accent mdui-ripple umami--click--vm-new">新建</button>
            </div>

            <div class="mdui-typo" style="text-align: right;margin-top: 10px"><small class="mdui-clearfix">
                    注意：每分钟价格 = 地区服务器基础价格 + 云虚拟机模板价格。<br />
                    带宽均为共享带宽，如带宽有调整，将会即时生效。<br />
                    禁止将云虚拟机用于挖矿、攻击（DDOS，CC）、QEMU等。如有发现，将直接删除用户。
                    IO限制（磁盘限速，网络限速）可能不会到达理论值（如果服务器资源占用很高）
                </small></div>
        </div>


    </form>
@endsection
