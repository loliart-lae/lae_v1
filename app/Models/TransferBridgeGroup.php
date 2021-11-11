<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferBridgeGroup extends Model
{
    use HasFactory;

    public function TransferBridge()
    {
        return $this->belongsTo(TransferBridge::class, 'id', 'transfer_bridge_id');
    }

    public function guests()
    {
        return $this->hasMany(TransferBridgeGuest::class, 'transfer_bridge_group_id', 'id');
    }

    public function permissions()
    {
        return $this->hasOne(TransferBridgePermission::class, 'transfer_bridge_group_id', 'id');
    }


    public function statistic()
    {
        return $this->hasMany(TransferBridgeStatistic::class, 'transfer_bridge_group_id', 'id');
    }
}
