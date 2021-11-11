<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferBridgeGuest extends Model
{
    use HasFactory;

    public function permissions()
    {
        return $this->hasOne(TransferBridgePermission::class, 'transfer_bridge_group_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(TransferBridgeGroup::class, 'transfer_bridge_group_id');
    }

    public function bridge()
    {
        return $this->hasOneThrough(TransferBridge::class, TransferBridgeGroup::class, 'transfer_bridge_id', 'id');
    }

    public function statistic()
    {
        return $this->hasMany(TransferBridgeStatistic::class, 'transfer_bridge_guest_id', 'id');
    }
}