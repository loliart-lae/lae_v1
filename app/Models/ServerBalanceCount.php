<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerBalanceCount extends Model
{
    use HasFactory;

    protected $dateFormat = 'Y-m-d H:i:s';

    public function server()
    {
        return $this->hasOne(Server::class, 'id', 'server_id');
    }
}
