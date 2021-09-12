<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBalanceLog extends Model
{
    use HasFactory;
    protected $table = 'user_balance_log';

    public function cost($user_id, $value, $reason = null) {
        $user = new User();
        $user_balance = $user->where('id', $user_id)->first()->balance;
        $current_balance = $user_balance - $value;

        if ($current_balance <= 0) {
            return false;
        }

        $user->where('id', $user_id)->update(['balance' => $current_balance]);

        $this->user_id = $user_id;
        $this->method = 'cost';
        $this->value = $value;
        $this->reason = $reason;

        $this->save();

        return true;
    }

    public function charge($user_id, $value, $reason = null) {

        $user = new User();
        $user_balance = $user->where('id', $user_id)->first()->balance;

        $current_balance = $user_balance + $value;

        $user->where('id', $user_id)->update(['balance' => $current_balance]);

        $this->user_id = $user_id;
        $this->method = 'charge';
        $this->value = $value;
        $this->reason = $reason;

        $this->save();

        return true;

    }

    static public function getBalance() {
        return Auth::user()->balance;
    }
}
