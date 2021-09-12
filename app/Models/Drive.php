<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drive extends Model
{
    use HasFactory;
    protected $table = 'drive_file_cache';

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

    public function folders()
    {
        return $this->hasMany(self::class, 'id', 'parent_id');
    }

    public function childFolders()
    {
        return $this->hasMany(self::class, 'id', 'parent_id')->with('folders');
    }

}
