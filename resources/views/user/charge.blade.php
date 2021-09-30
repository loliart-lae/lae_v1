@extends('layouts.app')

@section('title', '账户充值')

@section('content')
    <div class="mdui-typo-display-2">充值</div>

    <p>汇率：1 人民币 = 100 积分</p>
    <form method="post" action="{{ route('billing.store') }}">
        @csrf
        <br /> <br />
        <span class="mdui-typo-headline">输入充值金额</span>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">输入 RMB</label>
            <input class="mdui-textfield-input" type="number" name="balance" value="{{ old('balance') }}" required />
        </div>

        <label class="mdui-radio">
            <input type="radio" name="payment" value="wechat" checked />
            <i class="mdui-radio-icon"></i>
            微信支付
        </label>
        &nbsp;&nbsp;&nbsp;
        <label class="mdui-radio">
            <input type="radio" name="payment" value="alipay" />
            <i class="mdui-radio-icon"></i>
            支付宝
        </label>

        <br /> <br />
        <p>注意：积分是虚拟物品，不支持退款。请合理充值。</p>
        <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple">充值</button>

    </form>

@endsection
