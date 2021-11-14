<?php

namespace App\Http\Controllers;

use Exception;
use Ramsey\Uuid\Uuid;
use App\Models\Server;
use App\Models\Message;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PterodactylUser;
use App\Models\PterodactylImage;
use App\Models\PterodactylServer;
use App\Models\PterodactylTemplate;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Nonstandard\UuidV6;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PterodactylController extends Controller
{

    protected $project_id, $user_id;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gameServers = PterodactylServer::with(['template', 'server', 'image', 'user'])->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->get();

        return view('pterodactyl.index', compact('gameServers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Server $server, PterodactylImage $pterodactylImage, PterodactylTemplate $pterodactylTemplate)
    {
        // 模板
        $templates = $pterodactylTemplate::orderBy('price')->get();
        // 镜像
        $images = $pterodactylImage::get();

        return view('pterodactyl.create', compact('templates', 'images'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PterodactylTemplate $pterodactylTemplate, PterodactylImage $pterodactylImage, PterodactylServer $pterodactylServer)
    {
        $this->validate($request, [
            'project_id' => 'required',
            'name' => 'required|alpha_dash',
            'image_id' => 'required',
            'template_id' => 'required'
        ]);

        $this->project_id = $request->project_id;
        if (ProjectMembersController::userInProject($this->project_id)) {
            // 检查镜像是否存在
            $image_data = $pterodactylImage->where('id', $request->image_id)->firstOrFail();
            // 检查模板是否存在
            $template_data = $pterodactylTemplate->where('id', $request->template_id)->firstOrFail();

            try {
                // 创建用户
                $user_id = $this->create_user();
                // 获取节点
                $node_id = $this->getNode($template_data->memory, $template_data->disk_space);
                // 获取 Allocation ID
                $allocation_id = $this->getNextAllocationId($node_id);
            } catch (Exception $e) {
                Log::error($e);
                return redirect()->back()->with('status', '暂时无法创建 游戏服务器，可能服务器配额已满。');
            }

            // 执行创建
            $config = (object)[
                'name' => Auth::id() . '-' . $this->project_id . '-' . $request->name,
                'egg' => $image_data->egg,
                'image' => $image_data->docker_image,
                'startup' => $image_data->startup,
                'environment' => json_decode($image_data->environment),
                'memory' => $template_data->memory,
                'swap' => $template_data->swap,
                'disk' => $template_data->disk_space,
                'io' => $template_data->io,
                'cpu' => $template_data->cpu_limit,
                'databases' => $template_data->databases,
                'backups' => $template_data->backups,
                'allocation' => $allocation_id,
            ];

            $result = $this->create_server($config);

            $pterodactylServer->name = $request->name;
            $pterodactylServer->template_id = $request->template_id;
            $pterodactylServer->image_id = $request->image_id;
            $pterodactylServer->project_id = $this->project_id;
            $pterodactylServer->server_id = $result['attributes']['id'];
            $pterodactylServer->user_id = $user_id;
            $pterodactylServer->allocation_id = $allocation_id;
            $pterodactylServer->save();

            // 通知
            ProjectActivityController::save($this->project_id, '新建了游戏服务器 ' . $request->name);

            return redirect()->route('gameServer.index')->with('status', '新建游戏服务器 成功。');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pterodactylTemplate = new PterodactylTemplate();
        $pterodactylImage = new PterodactylImage();
        $pterodactylServer = new PterodactylServer();
        $pterodactylServer_where = $pterodactylServer->where('id', $id);
        $pterodactylServer_data = $pterodactylServer_where->firstOrFail();

        if (ProjectMembersController::userInProject($pterodactylServer_data->project_id)) {
            // 模板
            $templates = $pterodactylTemplate::get();
            // 镜像
            $images = $pterodactylImage::get();

            return view('pterodactyl.edit', compact('templates', 'images', 'id', 'pterodactylServer_data'));
        }
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
        $this->validate($request, [
            'name' => 'required|alpha_dash',
            'image_id' => 'required',
            'template_id' => 'required'
        ]);

        $pterodactylServer = new PterodactylServer();
        $pterodactylServer_where =  $pterodactylServer->where('id', $id);
        // 检查服务器是否存在
        $pterodactylServer_data = $pterodactylServer_where->firstOrFail();

        if (ProjectMembersController::userInProject($pterodactylServer_data->project_id)) {
            $pterodactylImage = new PterodactylImage();
            $pterodactylTemplate = new PterodactylTemplate();

            // 检查镜像是否存在
            $image_data = $pterodactylImage->where('id', $request->image_id)->firstOrFail();
            // 检查模板是否存在
            $template_data = $pterodactylTemplate->where('id', $request->template_id)->firstOrFail();

            // 修改
            $config = (object)[
                'allocation' => $pterodactylServer_data->allocation_id,
                'egg' => $image_data->egg,
                'image' => $image_data->docker_image,
                'startup' => $image_data->startup,
                'environment' => json_decode($image_data->environment),
                'memory' => $template_data->memory,
                'swap' => $template_data->swap,
                'disk' => $template_data->disk_space,
                'io' => $template_data->io,
                'cpu' => $template_data->cpu_limit,
                'databases' => $template_data->databases,
                'backups' => $template_data->backups,
            ];

            $this->update_server($pterodactylServer_data->server_id, $config);
            $pterodactylServer_where->update([
                'name' => $request->name,
                'template_id' => $request->template_id,
                'image_id' => $request->image_id,
            ]);

            if ($pterodactylServer_data->name == $request->name) {
                $change_name_tip =  ', 新名称为: ' . $request->name;
            }

            ProjectActivityController::save($pterodactylServer_data->project_id, '编辑了游戏服务器 ' . $pterodactylServer_data->name . $change_name_tip ?? '。');


            return redirect()->back()->with('status', '游戏服务器 修改成功。');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pterodactylServer = new PterodactylServer();
        $pterodactylServer_where = $pterodactylServer->where('id', $id);
        $pterodactylServer_data = $pterodactylServer_where->with('user')->firstOrFail();
        if (ProjectMembersController::userInProject($pterodactylServer_data->project_id)) {
            // 删除 服务器
            if (!$this->delete_server($pterodactylServer_data->server_id)) {
                return redirect()->back()->with('status', '删除失败。');
            }

            $pterodactylServer_where->delete();

            // 如果这个项目中没有其他服务器，则删除这个项目的用户
            if ($pterodactylServer->where('project_id', $pterodactylServer_data->project_id)->count() == 0) {
                $this->delete_user($pterodactylServer_data->user->user_id);
            }

            ProjectActivityController::save($pterodactylServer_data->project_id, '删除了游戏服务器 ' . $pterodactylServer_data->name);

            return redirect()->back()->with('status', '游戏服务 已删除。');
        }
    }


    private function getNode($need_mem, $need_disk)
    {
        // 查找合适的Node
        $next_page = 1;
        $is_continue = true;
        $selected_id = 0;
        do {
            $nodes = Http::withToken(config('app.pterodactyl_panel_api_token'))->get(config('app.pterodactyl_panel') . '/api/application/nodes?page=' . $next_page);
            $node_data = (object)$nodes->json();

            $total_page = $node_data->meta['pagination']['total_pages'];

            if ($next_page == $total_page) {
                $is_continue = false;
            } else {
                $next_page = $node_data->meta['pagination']['current_page'] + 1;
            }

            foreach ($node_data->data as $data) {
                $mem = $data['attributes']['memory'];
                $disk = $data['attributes']['disk'];

                $current_mem = $data['attributes']['allocated_resources']['memory'];
                $current_disk = $data['attributes']['allocated_resources']['disk'];

                if (($mem - $current_mem) > $need_mem && ($disk - $current_disk) > $need_disk) {
                    $selected_id = $data['attributes']['id'];
                    return $selected_id;
                }
            }
        } while ($is_continue);
    }

    private function getNextAllocationId($node_id)
    {
        // 查找没有绑定的 Allocation
        $next_page = 1;
        $is_continue = true;
        $selected_id = 0;
        do {
            $allocations = Http::withToken(config('app.pterodactyl_panel_api_token'))->get(config('app.pterodactyl_panel') . '/api/application/nodes/' . $node_id . '/allocations?page=' . $next_page);
            $allocations_data = (object)$allocations->json();
            $total_page = $allocations_data->meta['pagination']['total_pages'];

            if ($next_page == $total_page) {
                $is_continue = false;
            } else {
                $next_page = $allocations_data->meta['pagination']['current_page'] + 1;
            }

            foreach ($allocations_data->data as $allocation) {
                if (!$allocation['attributes']['assigned']) {
                    $selected_id = $allocation['attributes']['id'];
                    return $selected_id;
                }
            }
        } while ($is_continue);
    }

    private function create_server(object $config)
    {
        $data = [
            "name" => $config->name,
            "user" => $this->user_id,
            "egg" => $config->egg,
            "docker_image" => $config->image,
            "startup" => $config->startup,
            "environment" => $config->environment,
            "limits" => [
                "memory" => $config->memory,
                "swap" => $config->swap,
                "disk" => $config->disk,
                "io" => $config->io,
                "cpu" => $config->cpu
            ],
            "feature_limits" => [
                "databases" => $config->databases,
                "backups" => $config->backups,
                "allocations" => 3,
            ],
            "allocation" => [
                "default" => $config->allocation
            ]
        ];
        try {
            $request = Http::withToken(config('app.pterodactyl_panel_api_token'))->post(config('app.pterodactyl_panel') . '/api/application/servers', $data);
        } catch (Exception $e) {
            Log::error($e);
            Log::error($request->body());
            throw new Exception($e);
        }
        return $request;
    }

    public function deleteServerById($server_id)
    {
        // try {
        //     Http::withToken(config('app.pterodactyl_panel_api_token'))->delete(config('app.pterodactyl_panel') . '/api/application/servers/' . $server_id . '/force');
        //     return true;
        // } catch (Exception $e) {
        //     unset($e);
        //     return false;
        // }

        $pterodactylServer = new PterodactylServer();
        $pterodactylServer_where = $pterodactylServer->where('server_id', $server_id);
        $pterodactylServer_data = $pterodactylServer_where->with('user')->firstOrFail();
        // 删除 服务器
        if ($this->delete_server($pterodactylServer_data->server_id)) {
            $pterodactylServer_where->delete();

            // 如果这个项目中没有其他服务器，则删除这个项目的用户
            if ($pterodactylServer->where('project_id', $pterodactylServer_data->project_id)->count() == 0) {
                $this->delete_user($pterodactylServer_data->user->user_id);
            }
        }
    }

    private function delete_server($id)
    {
        try {
            Http::withToken(config('app.pterodactyl_panel_api_token'))->delete(config('app.pterodactyl_panel') . '/api/application/servers/' . $id);
            return true;
        } catch (Exception $e) {
            unset($e);
            return false;
        }
    }

    private function create_user()
    {
        $pterodactylUser = new PterodactylUser();
        // 如果这个项目中已存在用户，则直接返回 ID
        $pterodactylUser_where = $pterodactylUser->where('project_id', $this->project_id);
        if ($pterodactylUser_where->exists()) {
            $pterodactylUser_data = $pterodactylUser_where->firstOrFail();
            $this->user_id = $pterodactylUser_data->user_id;
            return $pterodactylUser_data->id;
        }

        $pwd = Str::random(10);

        $response = Http::withToken(config('app.pterodactyl_panel_api_token'))->post(config('app.pterodactyl_panel') . '/api/application/users', [
            "email" => Str::random(10) . '@oae.com',
            "username" => Str::random(10),
            "first_name" => Str::random(5),
            "last_name" => Str::random(5),
            "password" => $pwd
        ]);

        Message::send('刚刚新建的 游戏服务器 账号的默认密码: ' . $pwd);

        $data = json_decode($response->body());
        $pterodactylUser->user_id = $data->attributes->id;
        $pterodactylUser->token = Uuid::uuid6()->toString();
        $pterodactylUser->project_id = $this->project_id;
        $pterodactylUser->save();

        $this->user_id = $pterodactylUser->user_id;
        return $pterodactylUser->id;
    }

    private function delete_user($user_id)
    {
        Http::withToken(config('app.pterodactyl_panel_api_token'))->delete(config('app.pterodactyl_panel') . '/api/application/users/' . $user_id);
        PterodactylUser::where('user_id', $user_id)->delete();
    }


    private function update_server($server_id, $config)
    {
        $build_data = [
            "allocation" => $config->allocation,
            "memory" => $config->memory,
            "swap" => $config->swap,
            "disk" => $config->disk,
            "io" => $config->io,
            "cpu" => $config->cpu,
            "feature_limits" => [
                "databases" => $config->databases,
                "backups" => $config->backups,
            ]
        ];

        $startup_data = [
            "egg" => $config->egg,
            "image" => $config->image,
            "startup" => $config->startup,
            "environment" => $config->environment,
            'skip_scripts' => false
        ];

        Http::withToken(config('app.pterodactyl_panel_api_token'))->patch(config('app.pterodactyl_panel') . '/api/application/servers/' . $server_id . '/build', $build_data);
        Http::withToken(config('app.pterodactyl_panel_api_token'))->patch(config('app.pterodactyl_panel') . '/api/application/servers/' . $server_id . '/startup', $startup_data);
    }

    public function callback($token)
    {
        $pterodactylUser = new PterodactylUser();
        $pterodactylUser_data = $pterodactylUser->where('token', $token)->firstOrFail();

        $cache_name = 'oae_pterodactyl_user_' . $pterodactylUser->id;
        if (Cache::has($cache_name)) {
            if (Cache::get($cache_name, 1) == 5) {
                // 重置Token
                $pterodactylUser->where('token', $token)->update([
                    'token' => UuidV6::uuid6()->toString(),
                ]);
                $msg = '十分钟内登录次数过多，Token已被重置。';
            } else {
                Cache::increment($cache_name);
            }
        } else {
            Cache::put($cache_name, 1, 600);
        }

        Log::debug("Cache: " . Cache::has($cache_name));

        $msg = null;

        ProjectActivityController::save($pterodactylUser_data->project_id, '游戏服务器后台登录成功。' . $msg, true);

        return response()->json([
            'user_id' => $pterodactylUser_data->user_id,
        ]);
    }
}
