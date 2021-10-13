<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Server;

class ServerController extends Controller
{
    static public function exists($id) {
        return Server::where('id', $id)->where('type', 'container')->exists();
    }

    static public function existsRemoteDesktop($id) {
        return Server::where('id', $id)->where('type', 'windows')->exists();
    }

    static public function existsTunnel($id) {
        return Server::where('id', $id)->where('type', 'tunnel')->exists();
    }

    static public function existsStaticPage($id) {
        return Server::where('id', $id)->where('type', 'staticPage')->exists();
    }

}
