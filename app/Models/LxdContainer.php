<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LxdContainer extends Model
{
    use HasFactory;
    protected $table = 'lxd_containers';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function member() {
        return $this->hasMany(ProjectMember::class, 'project_id', 'project_id');
    }

    public function server() {
        return $this->hasOne(Server::class, 'id', 'server_id');
    }

    public function template() {
        return $this->hasOne(LxdTemplate::class, 'id', 'template_id');
    }

    public function forward() {
        return $this->hasMany(Forward::class, 'lxd_id', 'id');
    }
}
