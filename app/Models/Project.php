<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\LxdContainer;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function template() {
        return $this->belongsTo(LxdTemplate::class, 'template_id', 'id');
    }

    public function lxd()
    {
        return $this->hasMany(LxdContainer::class, 'id', 'project_id');
    }

    public function user_in_project_member() {
        return $this->hasMany(ProjectMember::class, 'id', 'project_id');
    }
}
