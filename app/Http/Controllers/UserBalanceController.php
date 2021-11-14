<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\UserBalanceLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class UserBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->limit(100)->get();
        return view('user.charge', compact('orders'));
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
            return redirect()->back()->with('status', '请不要这样，先生');
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
        $order->payment = $request->payment;
        $order->balance = $request->balance;
        $order->save();
        $order_id = date("YmdHms") . Auth::id() . $order->id;
        $order->where('id', $order->id)->update(['order_id' => $order_id]);

        $data = array(
            "mid" => config('billing.mid'),
            "payId" => $order_id,
            "param" => Auth::id(),
            "type" => $pay_type,     // 微信支付传入1 支付宝支付传入2 支付宝当面付传入4
            "price" => $request->balance,
            "notifyUrl" => config('billing.notify'),
            "returnUrl" => config('billing.return'),
            "isHtml" => 0,
        );

        // 加密参数获取签名,签名顺序以及计算方式为 md5(mid+payId+param+type+price+商户密钥)
        $sign = md5(config('billing.mid') . $data['payId'] . $data['param'] . $data['type'] . $data['price'] . config('billing.key'));
        $data["sign"] = $sign;

        // API下单请求参数
        $response = Http::post(config('billing.api_url') . '/' . config('billing.pay_method'), [
            'isHtml' => 0,
            'mid' => config('billing.mid'),
            'payId' => $data['payId'],
            'type' => $data['type'],
            'sign' => $sign,
            'param' => $data['param'],
            'price' => $data['price'],
            'notifyUrl' => $data['notifyUrl'],
            'returnUrl' => $data['returnUrl'],
        ])->json();

        $order->where('id', $order->id)->update([
            'cloud_id' => $response['data']['orderId']
        ]);

        return response()->json([
            'status' => $response['code'],
            'data' => [
                'price' => $response['data']['reallyPrice'],
                'url' => $response['data']['payUrl'],
                'order_id' => $response['data']['orderId']
            ]
        ]);
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
                $userBalanceLog->charge($order_data->user_id, $order_data->balance * config('billing.exchange_rate'));

                // 标记订单为成功
                $order_where->update([
                    'status' => 'paid'
                ]);

                return 'success';
            }
        }
    }

    public function thankyou(Request $request, Order $order)
    {
        return view('thankyou');
    }

    public function check(Request $request, Order $order, UserBalanceLog $userBalanceLog)
    {
        // 检查订单是否存在
        $order_where = $order->where('cloud_id', $request->order_id);
        $order_data = $order_where->first();

        if (is_null($order_data)) {
            return response()->json(['status' => 0]);
        }

        $response = Http::get(config('billing.api_url') . '/checkOrder', [
            'orderId' => $request->order_id
        ])->json();

        // dd($response);

        if ($response['code'] == 1) {
            if ($order_data->status == 'paid') {
                // 处理已支付的情况
            } else {
                $order_where->update([
                    'status' => 'paid'
                ]);
                $userBalanceLog->charge(Auth::id(), $order_data->balance * config('billing.exchange_rate'));
            }

            return response()->json(['status' => 1]);
        } else {
            return response()->json(['status' => 0]);
        }
    }
}
