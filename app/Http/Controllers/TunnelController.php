<?php

namespace App\Http\Controllers;

use Ramsey\Uuid\Uuid;
use App\Models\Server;
use App\Models\Tunnel;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\ProjectMember;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Nonstandard\UuidV6;
use Illuminate\Support\Facades\Auth;

class TunnelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tunnels = Tunnel::with(['server'])->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->get();

        return view('tunnel.index', compact('tunnels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Project $project, ProjectMember $member, Server $server)
    {
        // 列出项目
        $projects = $member->where('user_id', Auth::id())->with('project')->get();

        // 选择服务器
        $servers = $server->where('type', 'tunnel')->get();

        return view('tunnel.create', compact('servers', 'projects'));
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
            "name" => 'required',
            "protocol" => 'required',
            "local_address" => 'required',
            'project_id' => 'required',
            'server_id' => 'required'
        ));

        if (!ProjectMembersController::userInProject($request->project_id)) {
            return redirect()->back()->with('status', '项目不存在。');
        }

        if (!ServerController::existsTunnel($request->server_id)) {
            return redirect()->back()->with('status', '服务器不存在。');
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
                return redirect()->back()->with('status', 'Error: 公网端口范围不正确，最低1025，最高65535。');
            }
        } else if ($request->protocol == 'xtcp') {
            $request->custom_domain = null;
            $request->remote_port = null;

            $this->validate($request, array(
                "sk" => 'required|alpha_dash|min:3|max:15',
            ));
        } else {
            return redirect()->back()->with('status', 'Error: 不支持的协议。');
        }


        // 检查本地地址是否合法
        if (strpos($request->local_address, ':') == false) {
            return redirect()->back()->with('status', 'Error: 内网地址校验失败。');
        }

        // 检测端口是否被占用
        if ($request->remote_port != null) {
            // 不为null
            if ($tunnel->where('remote_port', $request->remote_port)->where('server_id', $request->server_id)->exists()) {
                return redirect()->back()->with('status', 'Error: 在这个服务器上已经存在相同端口了。');
            }
        } else {
            // 反之，检查域名是否被占用
            if ($tunnel->where('custom_domain', $request->custom_domain)->where('server_id', $request->server_id)->where('protocol', $request->protocol)->exists()) {
                return redirect()->back()->with('status', 'Error: 在这个服务器上已经存在相同协议的域名了。');
            }
        }

        $tunnel->name = $request->name;
        $tunnel->protocol = $request->protocol;
        $tunnel->custom_domain = $request->custom_domain;
        $tunnel->local_address = $request->local_address;
        $tunnel->remote_port = $request->remote_port;
        $tunnel->client_token = Uuid::uuid6()->toString();
        $tunnel->server_id = $request->server_id;
        $tunnel->project_id = $request->project_id;
        $tunnel->sk = $request->sk;
        $tunnel->save();

        return redirect()->route('tunnels.index')->with('status', 'Tunnel 隧道新建成功。');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $tunnel = Tunnel::where('id', $id)->with(['server', 'project'])->firstOrFail();

        if (!ProjectMembersController::userInProject($tunnel->project->id)) {
            return redirect()->to('/')->with('status', '你没有合适的权限。');
        }

        $address = $tunnel->server->address;

        $ini = <<<EOF
[common]
server_addr = $address
server_port = 1024
user = lightart.top
token = lightart.top

EOF;

        $id = $tunnel->id;
        $name = $tunnel->name;
        $client_token = $tunnel->client_token;
        $protocol = $tunnel->protocol;
        $local = explode(':', $tunnel->local_address);
        $local_ip = $local[0];
        $local_port = $local[1];
        $remote_port = $tunnel->remote_port;
        $ini .= PHP_EOL . <<<EOF

#------ Copy tunnel start ------
# $name at server {$tunnel->server->id} by project {$tunnel->project->id}
[{$tunnel->server->id}|$id|$client_token]
type = $protocol
local_ip = $local_ip
local_port = $local_port
EOF . PHP_EOL;

        if ($protocol == 'tcp' || $protocol == 'udp') {
            $ini .= <<<EOF
remote_port = $remote_port
EOF;
        } elseif ($protocol == 'http' || $protocol == 'https') {
            $ini .= <<<EOF
custom_domains = {$tunnel->custom_domain}
EOF;
        }

        $ini .= PHP_EOL . '#------ Copy tunnel end --------' . PHP_EOL;

        return response($ini, 200)->header('Content-Type', 'text/plain');
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
        $tunnel = Tunnel::where('id', $id)->with(['server', 'project'])->firstOrFail();
        if (ProjectMembersController::userInProject($tunnel->project->id)) {
            // 删除
            Tunnel::where('id', $id)->delete();
        }
        return redirect()->back()->with('status', 'Tunnel 隧道已删除。');
    }

    public function auth(Request $request, Tunnel $tunnel)
    {

        if ($request->op == 'Login') {
            if ($request->content['user'] == 'lightart.top' || $request->content['user'] == 'lightart_top_visitor') {
                // 存在
                return response()->json(array(
                    "reject" => false,
                    "unchange" => true,
                ));
            } else {
                return response()->json(array(
                    "reject" => true,
                    "reject_reason" => "用户不被允许。",
                    "unchange" => true,
                ));
            }
        } else if ($request->op == 'NewProxy') {
            Log::debug($request);
            if ($request->content['user']['user'] == 'lightart_top_visitor') {
                // 协议检测
                if ($request->content['proxy_type'] !== 'xtcp') {
                    return response()->json(array(
                        "reject" => true,
                        "reject_reason" => "不允许的访问协议。",
                        "unchange" => true,
                    ));
                }

                // 占用检测
                // 分割字符串 // proxy_type // $request->user['user]
                $client = explode('|', $request->content['proxy_name']);
                // 0: 服务器ID 1: 隧道ID
                $sid = explode('.', $client[0])[1];
                $tid = $client[1];
                $token = $client[2];
                // 检查是否存在
                $tunnel_where = $tunnel->where('server_id', $sid)->where('id', $tid);
                if ($tunnel_where->where('client_token', $token)->exists()) {
                    return response()->json(array(
                        "reject" => true,
                        "reject_reason" => "你不能占用常规协议。",
                        "unchange" => true,
                    ));
                }

                return response()->json(array(
                    "reject" => false,
                    "unchange" => true,
                ));

            } else {
                // 分割字符串 // proxy_type // $request->user['user]
                $client = explode('|', $request->content['proxy_name']);
                // 0: 服务器ID 1: 隧道ID
                $sid = explode('.', $client[0])[1];
                $tid = $client[1];
                $token = $client[2];
                // 检查是否存在
                $tunnel_where = $tunnel->where('server_id', $sid)->where('id', $tid);
                if ($tunnel_where->where('client_token', $token)->exists()) {
                    // 检查端口之类的是否相等
                    $tunnel_info = $tunnel_where->firstOrFail();
                    if ($request->content['proxy_type'] == 'tcp' || $request->content['proxy_type'] == 'udp') {
                        if ($request->content['proxy_type'] == $tunnel_info->proxy_type || $request->content['remote_port'] != $tunnel_info->remote_port || $tunnel_info->remote_port < 1024) {
                            return response()->json(array(
                                "reject" => true,
                                "reject_reason" => 'tunnel ' . $request->content['proxy_name'] . ' config mismatch',
                                "unchange" => true,
                            ));
                        }
                    } elseif ($request->content['proxy_type'] == 'http' || $request->content['proxy_type'] == 'https') {
                        if ($request->content['proxy_type'] == $tunnel_info->proxy_type || $request->content['custom_domains'][0] != $tunnel_info->custom_domain) {
                            return response()->json(array(
                                "reject" => true,
                                "reject_reason" => 'tunnel ' . $request->content['proxy_name'] . ' config mismatch',
                                "unchange" => true,
                            ));
                        }
                    }

                    return response()->json(array(
                        "reject" => false,
                        "unchange" => true,
                    ));
                } else {
                    return response()->json(array(
                        "reject" => true,
                        "reject_reason" => "隧道不存在",
                        "unchange" => true,
                    ));
                }
            }
        }
    }
}
