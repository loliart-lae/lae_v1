<?php

namespace App\Models;

use App\Models\User;
use App\Models\LxdContainer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function template()
    {
        return $this->belongsTo(LxdTemplate::class, 'template_id', 'id');
    }

    public function lxd()
    {
        return $this->hasMany(LxdContainer::class, 'id', 'project_id');
    }

    public function user_in_project_member()
    {
        return $this->hasMany(ProjectMember::class, 'id', 'project_id');
    }

    public static function cost($project_id, $value)
    {
        $proj_balance = self::where('id', $project_id)->first()->balance;

        $lock = Cache::lock("proj_balance_" . $project_id, $proj_balance);

        try {
            $lock->block(5);
            $proj_balance = self::where('id', $project_id)->first()->balance;
            $current_balance = $proj_balance - $value;

            if ($current_balance <= 0) {
                return false;
            }

            self::where('id', $project_id)->update(['balance' => $current_balance]);
        } catch (LockTimeoutException $e) {
            return false;
        } finally {
            optional($lock)->release();
        }
        return true;
    }

    public static function charge($project_id, $value)
    {
        $proj_balance = self::where('id', $project_id)->first()->balance;

        $lock = Cache::lock("proj_balance_" . $project_id, $proj_balance);
        $lock->block(5);
        try {
            $proj_balance = self::where('id', $project_id)->first()->balance;
            $current_balance = $proj_balance + $value;
            self::where('id', $project_id)->update(['balance' => $current_balance]);
        }catch (LockTimeoutException $e) {
            return false;
        } finally {
            optional($lock)->release();
        }
        return true;
    }
}
