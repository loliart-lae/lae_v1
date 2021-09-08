<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    public function forward()
    {
        return $this->hasMany(Forward::class, 'id', 'lxd_id');
    }
}
