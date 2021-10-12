<?php

namespace App\Models;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBalanceLog extends Model
{
    use HasFactory;
    protected $table = 'user_balance_log';

    public function cost($user_id, $value, $reason = null)
    {
        $user = new User();
        $user_balance = $user->where('id', $user_id)->first()->balance;
        $lock = Cache::lock("user_balance_" . $user_id, $user_balance);

        try {
            $lock->block(5);

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
        } catch (LockTimeoutException $e) {
            return false;
        } finally {
            optional($lock)->release();
        }
        return true;
    }

    public function charge($user_id, $value, $reason = null)
    {
        $user = new User();
        $user_balance = $user->where('id', $user_id)->first()->balance;

        $lock = Cache::lock("user_balance_" . $user_id, $user_balance);
        try {
            $lock->block(5);

            $current_balance = $user_balance + $value;

            $user->where('id', $user_id)->update(['balance' => $current_balance]);

            $this->user_id = $user_id;
            $this->method = 'charge';
            $this->value = $value;
            $this->reason = $reason;

            $this->save();
        } catch (LockTimeoutException $e) {
            return false;
        } finally {
            optional($lock)->release();
        }
        return true;
    }

    static public function getBalance()
    {
        return Auth::user()->balance;
    }
}
