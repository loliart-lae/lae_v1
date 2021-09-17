<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentLike extends Model
{
    use HasFactory;
    protected $table = 'document_likes';

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
