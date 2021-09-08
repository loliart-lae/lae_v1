<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LxdTemplate extends Model
{
    use HasFactory;
    protected $table = 'lxd_templates';

    public function template() {
        return $this->hasOne(LxdTemplate::class, 'id', 'template_id');
    }
}
