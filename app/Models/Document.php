<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;

class Document extends Model
{
    use HasFactory, Searchable;


    /**
     * Get the index name for the model.
     *
     * @return string
     */
    // public function searchableAs()
    // {
    //     return 'documents_index';
    // }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(DocumentComment::class);
    }

    public function likes()
    {
        return $this->hasMany(DocumentLike::class);
    }

    public function tags()
    {
        return $this->hasMany(DocumentTag::class);
    }
}
