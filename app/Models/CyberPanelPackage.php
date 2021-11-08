<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CyberPanelPackage extends Model
{
    use HasFactory;

    public function server()
    {
        return $this->belongsTo(Server::class, 'server_id', 'id');
    }
}