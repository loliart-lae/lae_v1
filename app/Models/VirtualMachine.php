<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualMachine extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function member()
    {
        return $this->hasMany(ProjectMember::class, 'project_id', 'project_id');
    }

    public function server()
    {
        return $this->hasOne(Server::class, 'id', 'server_id');
    }

    public function template()
    {
        return $this->hasOne(VirtualMachineTemplate::class, 'id', 'template_id');
    }

    public function dash_user()
    {
        return $this->hasOne(VirtualMachineUser::class, 'id', 'user_id');
    }
}
