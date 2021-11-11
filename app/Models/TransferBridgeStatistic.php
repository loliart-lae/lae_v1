<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferBridgeStatistic extends Model
{
    use HasFactory;

    public function group()
    {
        return $this->belongsTo(TransferBridgeGroup::class, 'transfer_bridge_group_id');
    }
}