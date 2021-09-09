<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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

        $result = $user->where('id', $user_id)->update(['balance' => $current_balance]);

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
}
