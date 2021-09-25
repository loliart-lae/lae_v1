<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Overtrue\LaravelFollow\Followable as FollowAble;

class User extends Authenticatable
{
    use HasFactory, Notifiable, FollowAble;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'id', 'user_id');
    }

    public function statuses()
    {
        return $this->hasMany(UserStatus::class);
    }

    public function feed()
    {
        $user_ids = $this->followers()->get()->toArray();
        array_push($user_ids, $this->id);
        return UserStatus::whereIn('user_id', $user_ids)->with('user', 'like')->orderBy('created_at', 'desc');
    }


}
