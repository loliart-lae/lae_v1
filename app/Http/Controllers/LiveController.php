<?php

namespace App\Http\Controllers;

use LVR\Colour\Hex;
use Illuminate\Http\Request;
use App\Models\LiveTimePeriod;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LiveTimePeriod $liveTimePeriod)
    {
        $lives = $liveTimePeriod->whereBetween('created_at', [Carbon::today()->toDateTimeString(), Carbon::tomorrow()->toDateTimeString()])->with('user')->get();
        return view('live.index', compact('lives'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('live.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, LiveTimePeriod $liveTimePeriod)
    {
        $this->validate($request, [
            'name' => 'required|unique:App\Models\LiveTimePeriod,name',
            'start_at' => 'required',
            'end_at' => 'required',
        ]);
        // dd($request->end_at);
        // dd(Carbon::parse($request->end_at)->toTimeString());
        if (!Carbon::parse($request->start_at)->isToday() && !Carbon::parse($request->end_at)->isToday()) {
            return redirect()->back()->with('status', '不在时间内。');
        }

        // 检测时间差
        $minutes = Carbon::parse($request->start_at)->diffInMinutes(Carbon::parse($request->end_at), false);
        if (!$minutes || $minutes > 90) {
            return redirect()->back()->with('status', '单个节目时长不能大于 90 分钟。');
        }

        // 检测当前区间是否被占用
        $liveTimePeriod_where = $liveTimePeriod->where('end_at', '>', Carbon::parse($request->end_at)->toTimeString());
        // dd($liveTimePeriod_where->get());
        if ($liveTimePeriod_where->exists()) {
            return redirect()->back()->with('status', '这个时间段已被安排了。');
        }

        $liveTimePeriod->name = $request->name;
        $liveTimePeriod->token = UuidV4::uuid4()->toString();
        $liveTimePeriod->start_at = $request->start_at;
        $liveTimePeriod->end_at = $request->end_at;
        $liveTimePeriod->user_id = Auth::id();
        $liveTimePeriod->save();

        return redirect()->route('live.index')->with('status', '时间段安排成功。');

        // dd(Carbon::parse($request->start_at)->isToday());
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
        $liveTimePeriod = new LiveTimePeriod();
        $live = $liveTimePeriod->where('id', $id)->firstOrFail();
        if ($live->user_id == Auth::id()) {
            return view('live.edit', compact('live'));
        }
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
        $liveTimePeriod = new LiveTimePeriod();
        $live = $liveTimePeriod->where('id', $id)->firstOrFail();
        if ($live->user_id == Auth::id()) {
            $liveTimePeriod->where('id', $id)->update([
                'name' => $request->name,
            ]);
            return redirect()->back()->with('status', '已修改安排。');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $liveTimePeriod = new LiveTimePeriod();
        $liveTimePeriod = $liveTimePeriod->where('id', $id)->firstOrFail();
        if ($liveTimePeriod->user_id == Auth::id()) {
            $liveTimePeriod->where('id', $id)->delete();
            return redirect()->route('live.index')->with('status', '删除成功。');
        }
    }

    public function auth(Request $request)
    {
        if ($request->name != 'aeTimeRiver') {
            return response()->json(['status' => 403]);
        }

        if ($request->route('key') != config('app.streaming_validate_password')) {
            return response()->json(['status' => 403]);
        }

        $liveTimePeriod = new LiveTimePeriod();
        $liveTimePeriod_where = $liveTimePeriod->where('token', $request->token)->whereBetween('created_at', [Carbon::today()->toDateTimeString(), Carbon::tomorrow()->toDateTimeString()]);
        $liveTimePeriod_data = $liveTimePeriod_where->first();

        if (is_null($liveTimePeriod_data)) {
            return response()->json(['status' => 403]);
        }
        // 验证是否在当前时间段
        $now = Carbon::now();
        $end_at = Carbon::parse($liveTimePeriod_data->end_at);
        if ($now->gt($end_at)) {
            return response()->json(['status' => 403]);
        }

        if ($request->app != config('app.streaming_application')) {
            return response()->json(['status' => 403]);
        }

        // if ($request->app != config('app.streaming_application')) {
        //     abort(403, 'application not found');
        // }

        if ($request->call == 'publish') {
            $liveTimePeriod_where->update([
                'status' => 1,
                'ip' => $request->addr
            ]);
        } elseif ($request->call == 'publish_done') {
            $liveTimePeriod_where->update([
                'status' => 0,
                'ip' => $request->addr
            ]);
        }

        return response()->json(['status' => 200]);
    }

    public function disconnect()
    {
        // $response = Http::withBasicAuth('taylor@laravel.com', 'secret')->post();
    }
}