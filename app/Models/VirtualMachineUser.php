<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualMachineUser extends Model
{
    use HasFactory;

    public function member()
    {
        return $this->belongsTo(ProjectMember::class, 'project_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function vm()
    {
        return $this->hasOne(VirtualMachine::class, 'id', 'virtualMachine_id');
    }
}
