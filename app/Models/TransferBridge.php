<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferBridge extends Model
{
    use HasFactory;

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function member()
    {
        return $this->hasMany(ProjectMember::class, 'project_id', 'project_id');
    }

    public function groups()
    {
        return $this->hasMany(TransferBridgeGroup::class, 'transfer_bridge_id', 'id');
    }

    public function defaultGroup()
    {
        return $this->hasOne(TransferBridgeGroup::class, 'transfer_bridge_id', 'default_group_id');
    }

    public function permissions()
    {
        return $this->hasOne(TransferBridgePermission::class, 'transfer_bridge_id', 'id');
    }

    public function guests()
    {
        return $this->hasManyThrough(TransferBridgeGuest::class, TransferBridgeGroup::class, 'id', 'transfer_bridge_id', 'id');
    }

    public function statistic()
    {
        return $this->hasMany(TransferBridgeStatistic::class, 'transfer_bridge_id', 'id');
    }
}