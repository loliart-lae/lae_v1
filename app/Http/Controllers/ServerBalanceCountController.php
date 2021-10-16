<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ServerBalanceCount;

class ServerBalanceCountController extends Controller
{
    //

    public function thisMonth($server_id)
    {
        $start = Carbon::now()->startOfMonth()->toDateString();
        $today = Carbon::now()->toDateString();
        if ($server_id == 0) {
            $counts_sql = ServerBalanceCount::whereBetween('created_at', [$start, $today])->with('user');
            $counts = $counts_sql->count();
            $data = $counts_sql->get();
        } else {
            $counts_sql = ServerBalanceCount::where('server_id', $server_id)->whereBetween('created_at', [$start, $today])->with('user');;
            $counts = $counts_sql->count();
            $data = $counts_sql->get();
        }

        $bypass_list = config('admin.bypass_users');
        $balance = 0;

        foreach ($data as $d) {
            if (is_null($d->user)) {
                $balance += $d->value;
            } elseif (!in_array($d->user->email, $bypass_list)) {
                $balance += $d->value;
            }
        }

        return [
            'counts' => $counts,
            'balance' => $balance
        ];
    }

    public function lastMonth($server_id)
    {
        $start = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $end = Carbon::now()->subMonth()->endOfMonth()->toDateTimeString();
        if ($server_id == 0) {
            $counts_sql = ServerBalanceCount::whereBetween('created_at', [$start, $end])->with('user');
            $counts = $counts_sql->count();
            $data = $counts_sql->get();
        } else {
            $counts_sql = ServerBalanceCount::where('server_id', $server_id)->whereBetween('created_at', [$start, $end])->with('user');
            $counts = $counts_sql->count();
            $data = $counts_sql->get();
        }

        $bypass_list = config('admin.bypass_users');
        $balance = 0;

        foreach ($data as $d) {
            if (is_null($d->user)) {
                $balance += $d->value;
            } elseif (!in_array($d->user->email, $bypass_list)) {
                $balance += $d->value;
            }
        }

        return [
            'counts' => $counts,
            'balance' => $balance
        ];
    }
}
