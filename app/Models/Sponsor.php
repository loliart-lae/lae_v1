<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    public function sponsors()
    {
        return $this->hasMany(Sponsor::class);
    }

    public function SponsorAds()
    {
        return $this->hasMany(Sponsor::class)->with('sponsors');
    }
}
