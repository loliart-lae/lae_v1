<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forward extends Model
{
    use HasFactory;

    public function member() {
        return $this->belongsTo(ProjectMember::class, 'project_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function lxd()
    {
        return $this->hasOne(LxdContainer::class, 'id', 'lxd_id');
    }

    public function server()
    {
        return $this->hasOne(Server::class, 'id', 'server_id');
    }
}
