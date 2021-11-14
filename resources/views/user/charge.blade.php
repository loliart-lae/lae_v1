@extends('layouts.app')

@section('title', '账户充值')

@section('content')
<div class="mdui-typo-display-1">充值</div>

<p>汇率：1 人民币 = 100 积分</p>
<form method="post" action="{{ route('billing.store') }}">
    @csrf
    <br /> <br />
    <span class="mdui-typo-headline">输入充值金额</span>
    <div class="mdui-textfield">
        <label class="mdui-textfield-label">输入 RMB</label>
        <input class="mdui-textfield-input umami--input--charge-balance" type="number" name="balance" value="1"
            required />
    </div>

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

    <br /> <br />
    <p>注意：积分是虚拟物品，不支持退款。请合理充值。</p>
    <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--do-charge">充值</button>
</form>

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
                    <span class="material-icons material-icons-outlined" mdui-tooltip="{content: '未支付', position: 'left'}">
                        pending
                    </span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
