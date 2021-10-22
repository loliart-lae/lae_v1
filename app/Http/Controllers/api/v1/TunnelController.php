<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Tunnel;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\ProjectMembersController;
use App\Models\Server;

class TunnelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tunnels = Tunnel::whereHas('member', function ($query) {
            $query->where('user_id', AUth::id());
        })->orderBy('project_id')->get();

        return response()->json([
            'status' => 1,
            'data' => $tunnels
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Server $server)
    {
        $servers = $server->where('type', 'tunnel')->get();
        return response()->json([
            'status' => 1,
            'data' => $servers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Tunnel $tunnel)
    {
        $this->validate($request, array(
            'name' => 'required',
            'protocol' => 'required',
            'local_address' => 'required',
            'project_id' => 'required',
            'server_id' => 'required'
        ));

        if (!ProjectMembersController::userInProject($request->project_id)) {
            return response()->json([
                'status' => 0,
                'msg' => 'Project not authenticated.'
            ]);
        }

        if (!ServerController::existsTunnel($request->server_id)) {
            return response()->json([
                'status' => 0,
                'msg' => 'Server not found.'
            ]);
        }

        $project_balance = Project::where('id', $request->project_id)->firstOrFail()->balance;
        if ($project_balance <= 1) {
            return response()->json([
                'status' => 0,
                'msg' => 'Your project has less than one balance.'
            ]);
        }

        if ($request->protocol == 'http' || $request->protocol == 'https') {
            $request->remote_port = 0;
            $this->validate($request, array(
                "custom_domain" => 'required',
            ));
        } elseif ($request->protocol == 'tcp' || $request->protocol == 'udp') {
            $request->custom_domain = null;
            $this->validate($request, array(
                "remote_port" => 'required',
            ));

            if ($request->remote_port < 1025 || $request->remote_port >= 65535) {
                return response()->json([
                    'status' => 0,
                    'msg' => 'Remote port must be between 1025 and 65535.'
                ]);
            }
        } else if ($request->protocol == 'xtcp') {
            $request->custom_domain = null;
            $request->remote_port = null;

            $this->validate($request, array(
                "sk" => 'required|alpha_dash|min:3|max:15',
            ));
        } else {
            return response()->json([
                'status' => 0,
                'msg' => 'Protocol not supported.'
            ]);
        }


        // 检查本地地址是否合法
        if (strpos($request->local_address, ':') == false) {
            return response()->json([
                'status' => 0,
                'msg' => 'Local address must contain a colon.'
            ]);
        }

        // 检测端口是否被占用
        if ($request->remote_port != null) {
            // 不为null
            if ($tunnel->where('remote_port', $request->remote_port)->where('server_id', $request->server_id)->exists()) {
                return response()->json([
                    'status' => 0,
                    'msg' => 'Remote port is already assigned on this server.'
                ]);
            }
        } else {
            // 反之，检查域名是否被占用
            if ($request->protocol != 'xtcp') {
                if ($tunnel->where('custom_domain', $request->custom_domain)->where('server_id', $request->server_id)->where('protocol', $request->protocol)->exists()) {
                    return response()->json([
                        'status' => 0,
                        'msg' => 'There is already a same protocol domain on this server.'
                    ]);
                }
            }
        }

        \App\Http\Controllers\TunnelController::create_tunnel($request);

        return response()->json([
            'status' => 1,
            'data' => [
                'name' => $request->name,
                'protocol' => $request->protocol,
                'local_address' => $request->local_address,
                'remote_port' => $request->remote_port,
                'custom_domain' => $request->custom_domain
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, \App\Http\Controllers\TunnelController $tunnelController)
    {
        // 莫名好用
        try {
            return $tunnelController->show($id);
        } catch (\Exception $e) {
            return 'tunnel not found.';
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\App\Http\Controllers\TunnelController::deleteTunnel($id)) {
            return response()->json([
                'status' => 1,
                'data' => $id
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'msg' => 'Tunnel does not exist.'
            ]);
        }
    }
}
