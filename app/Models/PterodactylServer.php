<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PterodactylServer extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->hasOne(PterodactylUser::class, 'id', 'user_id');
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

    public function image()
    {
        return $this->belongsTo(PterodactylImage::class, 'image_id', 'id');
    }

    public function template()
    {
        return $this->belongsTo(PterodactylTemplate::class, 'template_id', 'id');
    }
}
