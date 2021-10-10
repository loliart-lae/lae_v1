<?php

namespace App\Models;

use App\Models\User;
use App\Models\LxdContainer;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
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
        try {
            DB::beginTransaction();
            $proj_balance = self::where('id', $project_id)->lockForUpdate()->first()->balance;
            $current_balance = $proj_balance - $value;

            if ($current_balance <= 0) {
                return false;
            }

            self::where('id', $project_id)->update(['balance' => $current_balance]);
            DB::commit();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public static function charge($project_id, $value)
    {
        try {
            DB::beginTransaction();
            $proj_balance = self::where('id', $project_id)->lockForUpdate()->first()->balance;
            $current_balance = $proj_balance + $value;
            self::where('id', $project_id)->update(['balance' => $current_balance]);
            DB::commit();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
