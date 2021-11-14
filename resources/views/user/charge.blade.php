@extends('layouts.app')

@section('title', '账户充值')

@section('content')
    <style>
        #charge-dialog-image img {
            border-radius: 8px
        }

    </style>
    <div class="mdui-typo-display-1">充值</div>

    <p>比例: 1 人民币 = {{ config('billing.exchange_rate') }} 积分</p>
    <br /> <br />
    <span class="mdui-typo-headline">输入充值金额</span>
    <div class="mdui-textfield">
        <label class="mdui-textfield-label">输入 RMB</label>
        <input class="mdui-textfield-input umami--input--charge-balance" type="number" id="balance" value="1" required />
    </div>

    <div class="mdui-m-t-1">
        <label class="mdui-radio" class="umami--click--use-wechat-pay">
            <input type="radio" name="payment" value="wechat" checked />
            <i class="mdui-radio-icon"></i>
            微信支付
        </label>
        &nbsp;&nbsp;&nbsp;
        <label class="mdui-radio" class="umami--click--use-alipay">
            <input type="radio" name="payment" value="alipay" />
            <i class="mdui-radio-icon"></i>
            支付宝
        </label>
    </div>


    <br /> <br />
    <p>注意：积分是虚拟物品，不支持退款。请合理充值。</p>
    <button onclick="charge()" class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--do-charge">充值</button>

    <div class="mdui-typo-display-1 mdui-m-t-3">充值记录</div>
    <div class="mdui-table-fluid mdui-m-t-1">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>订单号</th>
                    <th>渠道</th>
                    <th>金额</th>
                    <th>发起时间</th>
                    <th>状态</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td nowrap>{{ $order->order_id }}</td>
                        <td nowrap>
                            @if ($order->payment == 'alipay')
                                支付宝
                            @else
                                微信支付
                            @endif
                        </td>
                        <td nowrap>
                            {{ $order->balance }} 元
                        </td>
                        <td nowrap>{{ $order->created_at }}</td>

                        <td nowrap>
                            @if ($order->status == 'paid')
                                <span class="material-icons material-icons-outlined mdui-text-color-green"
                                    mdui-tooltip="{content: '已支付', position: 'left'}">
                                    task_alt
                                </span>
                            @else
                                <span onclick="checkOrder('{{ $order->cloud_id }}')"
                                    class="material-icons material-icons-outlined"
                                    mdui-tooltip="{content: '未支付', position: 'left'}">
                                    pending
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mdui-dialog" id="charge-dialog">
        <div class="mdui-dialog-content">
            <div class="mdui-dialog-title" id="charge-dialog-title"></div>
            <div style="width:256px;height:256px;border-radius:5px" id="charge-dialog-image"></div>
        </div>
        <div class="mdui-dialog-actions">
            <button class="mdui-btn mdui-ripple" id="charge-dialog-confirm" mdui-dialog-confirm>验证</button>
        </div>
    </div>

    <script>
        function charge() {
            $('#charge-dialog-title').html(null)
            $('#charge-dialog-image').html(null)
            var charge_dialog = new mdui.Dialog('#charge-dialog')

            $.ajax({
                url: '{{ route('billing.store') }}',
                type: 'POST',
                data: {
                    'payment': $("input[name='payment']:checked").val(),
                    'balance': $('#balance').val(),
                },
                success: function(data) {
                    if (!data.status) {
                        mdui.snackbar({
                            'message': '暂时无法充值。',
                            'position': 'right-bottom'
                        })
                        return false
                    }

                    charge_dialog.open()

                    $('#charge-dialog-title').html('请支付' + data.data.price + '元，不能多也不能少。')

                    new QRCode(document.getElementById('charge-dialog-image'), data.data.url);

                    $('#charge-dialog-confirm').attr('onclick', `checkOrder("${data.data.order_id}")`)
                },
                error: function() {
                    mdui.snackbar({
                        'message': '暂时无法充值。',
                        'position': 'right-bottom'
                    })
                }
            })
        }


        function checkOrder(id) {
            $.ajax({
                url: '{{ route('billing.check') }}',
                type: 'POST',
                data: {
                    'order_id': id
                },
                success: function(data) {
                    if (data.status) {
                        mdui.snackbar({
                            'message': '订单已支付。',
                            'position': 'right-bottom'
                        })
                        $.pjax.reload('.pjax-container')
                    } else {
                        mdui.snackbar({
                            'message': '订单可能还未支付。',
                            'position': 'right-bottom'
                        })
                    }
                },
                error: function() {
                    mdui.snackbar({
                        'message': '此时无法更新订单状态。',
                        'position': 'right-bottom'
                    })
                }
            })
        }
    </script>

@endsection
