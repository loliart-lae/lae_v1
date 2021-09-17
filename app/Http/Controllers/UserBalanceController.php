<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\UserBalanceLog;
use Illuminate\Support\Facades\Auth;

class UserBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('charge');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Order $order)
    {
        $this->validate($request, [
            'balance' => 'required|min:1|max:100|integer',
            'payment' => 'required'
        ]);
        if ($request->balance < 1 || $request->balance > 100) {
            return redirect()->back()->with('status', 'What \'s up.');
        }
        if ($request->payment == 'alipay') {
            $pay_type = 2;
        } else  if ($request->payment == 'wechat') {
            $pay_type = 1;
        } else {
            return redirect()->back()->with('status', 'Error: 支付方式错误。');
        }

        // 创建订单
        $order->user_id = Auth::id();
        $order->payment = 'wechat';
        $order->balance = $request->balance;
        $order->save();
        $order_id = date("YmdHms") . Auth::id() . $order->id;
        $order->where('id', $order->id)->update(['order_id' => $order_id]);

        $data = array(
            "mid" => config('billing.mid'),
            "payId" => $order_id,
            "param" => 'Light App Engine Billing',
            "type" => $pay_type,     //微信支付传入1 支付宝支付传入2 支付宝当面付传入4
            "price" => $request->balance,
            "notifyUrl" => config('billing.notify'),
            "returnUrl" => config('billing.return'),
            "isHtml" => 1,
        );

        //加密参数获取签名,签名顺序以及计算方式为 md5(mid+payId+param+type+price+商户密钥)
        $sign = md5(config('billing.mid') . $data['payId'] . $data['param'] . $data['type'] . $data['price'] . config('billing.key'));
        $data["sign"] = $sign;

        //API下单请求参数拼接
        $pay_url = 'isHtml=' . $data['isHtml'] . "&mid=" . config('billing.mid') . "&payId=" . $data['payId'] . '&type=' . $data['type'] . '&sign=' . $sign . '&param=' . $data['param'] . "&price=" . $data['price'] . '&notifyUrl=' . $data['notifyUrl'] . '&returnUrl=' . $data['returnUrl'];

        return redirect()->to(config('billing.api_url') . '?' . $pay_url);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function notify(Request $request, Order $order, UserBalanceLog $userBalanceLog)
    {
        // 检查订单是否存在
        $order_where = $order->where('order_id', $request->payId);

        $order_data = $order->where('order_id', $request->payId)->firstOrFail();

        if (!$order_where->exists()) {
            return 'not found';
        }

        $sign = md5(config('billing.mid') . $request->payId . $request->param . $request->type . $request->price . $request->reallyPrice .  config('billing.key'));

        if ($sign != $request->sign) {
            return 'error_sign';
        } else {
            if ($order_data->status == 'paid') {
                // 不做任何操作
                return 'success';
            } else {
                // 充值
                $userBalanceLog->charge(Auth::id(), $order_data->balance * config('billing.exchange_rate'));

                // 标记订单为成功
                $order_where->update([
                    'status' => 'paid'
                ]);
                return 'success';
            }
        }
    }

    public function return(Request $request, Order $order, UserBalanceLog $userBalanceLog)
    {
        // 检查订单是否存在
        $order_where = $order->where('order_id', $request->payId);

        $order_data = $order->where('order_id', $request->payId)->firstOrFail();

        if (!$order_where->exists()) {
            return 'not found';
        }

        $sign = md5(config('billing.mid') . $request->payId . $request->param . $request->type . $request->price . $request->reallyPrice .  config('billing.key'));

        if ($sign != $request->sign) {
            return redirect()->route('billing.index')->with('status', 'Error: 订单验证失败。');
        } else {
            if ($order_data->status == 'paid') {
                // 不做任何操作
                return view('thankyou');
            } else {
                // 充值
                $userBalanceLog->charge(Auth::id(), $order_data->balance * config('billing.exchange_rate'));

                // 标记订单为成功
                $order_where->update([
                    'status' => 'paid'
                ]);
                return view('thankyou');
            }
        }
    }

    public function thankyou(Request $request, Order $order)
    {
        return view('thankyou');
    }
}
