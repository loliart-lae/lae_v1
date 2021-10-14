<?php

namespace App\Http\Controllers\api;

use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::select(['id', 'name', 'domain', 'price', 'forward_price', 'network_limit', 'type'])->get();

        return response()->json([
            'status' => 1,
            'data' => $servers
        ]);
    }
}
