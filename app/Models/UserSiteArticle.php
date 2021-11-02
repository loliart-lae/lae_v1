<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class UserSiteArticle extends Model
{
    use HasFactory, Searchable;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
