<div class="mdui-row mdui-p-t-4 mdui-p-b-2 mdui-p-l-1">
    <span class="mdui-typo-headline">选择地区服务器</span>
    <p class="mdui-typo-subheading">地区服务器影响着访问速度以及连通性，稳定性，以及基础价格。</p>
</div>

<div class="mdui-table-fluid">
    <table class="mdui-table mdui-table-hoverable">
        <thead>
            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>基础价格(积分/分钟)</th>
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
                        <td nowrap="nowrap">{{ $i++ }}</td>
                        <td nowrap="nowrap">{{ $server->name }}</td>
                        <td nowrap="nowrap">{{ $server->price }}</td>
                        <td nowrap="nowrap">{{ $server->network_limit }} Mbps</td>
                        <td>{{ number_format(($server->price * 44640) / config('billing.exchange_rate'), 2) }} 元
                            / 月</td>

                        <td>
                            <label class="mdui-radio">
                                <input type="radio" value="{{ $server->id }} " name="server_id"
                                    @if ($i == 2) checked @endif required />
                                <i class="mdui-radio-icon"></i>

                            </label>
                        </td>

                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="mdui-text-center">服务器均已售罄</td>
                </tr>
            @endif

        </tbody>
    </table>
</div>
