<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerMonitorData extends Model
{
    use HasFactory;

    public function monitor()
    {
        return $this->belongsTo(Monitor::class, 'monitor_id', 'id');
    }

    public function member() {
        return $this->hasMany(ProjectMember::class, 'project_id', 'project_id');
    }
}
