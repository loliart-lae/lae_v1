<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CyberPanelSite extends Model
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

    public function package()
    {
        return $this->hasOne(CyberPanelPackage::class, 'id', 'package_id');
    }
}