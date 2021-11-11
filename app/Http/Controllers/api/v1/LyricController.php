<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Lyric;
use App\Http\Controllers\Controller;

class LyricController extends Controller
{
    public function index()
    {
        $lyric = Lyric::inRandomOrder()->firstOrFail();
        return response()->json([
            'status' => 1,
            'content' => $lyric->content,
            'from' => $lyric->from,
            'created_at' => $lyric->created_at,
        ]);
    }
}
