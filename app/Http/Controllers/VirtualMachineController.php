<?php

namespace App\Http\Controllers;

use Proxmox\Nodes;
use Proxmox\Access;
use Proxmox\Cluster;
use Proxmox\Storage;
use App\Models\Server;
use Illuminate\Support\Str;
use Proxmox\Request as Pve;
use Illuminate\Http\Request;
use App\Models\VirtualMachine;
use App\Models\VirtualMachineUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\VirtualMachineTemplate;

class VirtualMachineController extends Controller
{

    // 这段控制器代码部分借鉴于 ProKVM
    // 如：依据配置获取节点，获取虚拟机存储位置以及镜像等
    // 非常感谢！

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $virtualMachines = VirtualMachine::with(['template', 'server'])->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->get();

        return view('virtualMachine.index', compact('virtualMachines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Server $server, VirtualMachineTemplate $virtualMachineTemplate)
    {
        // 选择服务器
        $servers = $server->where('free_mem', '>=', '4096')->where('free_disk', '>=', '20')->where('type', 'pve')->get();

        // 列出模板
        $templates = $virtualMachineTemplate->orderBy('price')->get();

        return view('virtualMachine.create', compact('servers', 'templates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Cluster $cluster, Nodes $nodes, VirtualMachine $virtualMachine, VirtualMachineUser $virtualMachineUser, Access $access)
    {
        $this->validate($request, [
            'server_id' => 'required',
            'image_id' => 'required',
            'name' => 'required|max:10',
            'template_id' => 'required',
            'start_after_created' => 'nullable|boolean',
            'bios' => 'boolean|required',
        ]);

        if (!ProjectMembersController::userInProject($request->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        $template = $this->validServer($request->server_id, $request->template_id);
        if (!$template) {
            return redirect()->back()->with('status', '服务器上没有更多的资源了。');
        }

        $vlan = Server::where('id', $request->server_id)->firstOrFail()->external;
        $this->login($request->server_id);

        $image = $this->checkImage($request->server_id, $request->image_id);
        if (!$image) {
            return redirect()->back()->with('status', '找不到镜像。');
        }

        if ($request->bios) {
            $bios = 'ovmf';
        } else {
            $bios = 'seabios';
        }

        // 获取 VMID
        $response = $cluster->nextVmid();
        $next_vmid = $response->data;

        // 选择节点
        $response = $cluster->Resources('node');
        $node_name = $response->data[0]->node;

        $storage_name = $this->getVmStorage($request->server_id);

        if ($request->start_after_created) {
            $status = 1;
        } else {
            $status = 0;
        }

        $virtualMachine->name = $request->name;
        $virtualMachine->template_id = $request->template_id;
        $virtualMachine->project_id = $request->project_id;
        $virtualMachine->server_id = $request->server_id;
        $virtualMachine->status = $status;
        $virtualMachine->node = $node_name;
        $virtualMachine->vm_id = $next_vmid;
        $virtualMachine->bios = $request->bios;
        $virtualMachine->save();

        $virtualMachineUser->username = 'ae-' . $virtualMachine->id;
        $virtualMachineUser->password = Str::random();
        $virtualMachineUser->save();

        $virtualMachine->where('id', $virtualMachine->id)->update([
            'user_id' => $virtualMachineUser->id,
        ]);

        $access->createUser([
            'userid' => $virtualMachineUser->username . '@pve',
            'password' => $virtualMachineUser->password,
        ]);

        $create = $nodes->createQemu($node_name, [
            'vmid' => $next_vmid,
            'name' => 'ae-' . $virtualMachine->id,
            'description' => '这个虚拟机是由 Open App Engine 创建的。创建者是: ' . Auth::user()->name . ', 邮箱: ' . Auth::user()->email . ', 项目ID: ' . $request->project_id . ', 创建时间: ' . $virtualMachine->created_at . '。',
            'scsihw' => 'lsi',
            'ostype' => 'other',
            'cores' => $template->cpu,
            'sockets' => 1,
            'numa' => 0,
            'memory' => $template->memory,
            'scsi0' => $storage_name . ':' . $template->disk,
            'ide2' => $image . ',media=cdrom',
            'net0' => 'virtio,bridge=' . $vlan . ',firewall=1',
            'kvm' => 0,
            'start' => $status,
            'bios' => $bios
        ]);

        if (is_null($create->data)) {
            $virtualMachine->where('id', $virtualMachine->id)->delete();

            $access->deleteUser($virtualMachineUser->username . '@pve');
            $virtualMachineUser->where('id', $virtualMachineUser->id)->delete();

            ProjectActivityController::save($request->project_id, '尝试创建虚拟机:' . $request->name . '但是失败了，因为服务器出现了问题。');
            return redirect()->route('virtualMachine.index')->with('status', '无法创建虚拟机。');
        } else {
            // 创建 ACL
            $access->updateAcl([
                'path' => '/vms/' . $next_vmid,
                'roles' => 'PVEVMUser',
                'users' => $virtualMachineUser->username . '@pve'
            ]);

            // 减去配额
            $server_data = Server::where('id', $request->server_id)->where('type', 'pve')->firstOrFail();
            $template_data = VirtualMachineTemplate::where('id', $request->template_id)->firstOrFail();
            Server::where('id', $request->server_id)->update([
                'free_mem' => $server_data->free_mem - $template_data->memory,
                'free_disk' => $server_data->free_disk - $template_data->disk,
            ]);

            ProjectActivityController::save($request->project_id, '创建了虚拟机: ' . $request->name . '。');
            return redirect()->route('virtualMachine.index')->with('status', '成功创建了虚拟机。');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Access $access)
    {
        $virtualMachine = new VirtualMachine();
        $virtualMachineUser = new VirtualMachineUser();

        $virtualMachine_where = $virtualMachine->where('id', $id)->with(['dash_user', 'server']);
        $virtualMachine_data = $virtualMachine_where->firstOrFail();

        if (!ProjectMembersController::userInProject($virtualMachine_data->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        $this->login($virtualMachine_data->server_id);

        // 登录到子账号
        $user = [
            'username' => $virtualMachine_data->dash_user->username,
            'password' => $virtualMachine_data->dash_user->password,
            'realm' => 'pve'
        ];

        $ticket = $access->createTicket($user);
        $ticket = $ticket->data->ticket;

        $data = [
            'host' => $virtualMachine_data->server->address,
            'vm_id' => $virtualMachine_data->vm_id,
            'node' => $virtualMachine_data->node,
            'ticket' => $ticket,
            'name' => $virtualMachine_data->name
        ];

        ProjectActivityController::save($virtualMachine_data->project_id, '进入了虚拟机: ' . $virtualMachine_data->name . '的控制台。');


        return view('virtualMachine.vnc', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $virtualMachine = new VirtualMachine();

        $virtualMachine_where = $virtualMachine->where('id', $id)->with(['dash_user', 'server']);
        $virtualMachine = $virtualMachine_where->firstOrFail();

        if (!ProjectMembersController::userInProject($virtualMachine->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        return view('virtualMachine.edit', compact('virtualMachine'));
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
            'name' => 'required|max:10',
            'image_id' => 'nullable',
            'remove_cd_rom' => 'boolean',
        ]);

        $virtualMachine = new VirtualMachine();
        $virtualMachine_where = $virtualMachine->where('id', $id);
        $virtualMachine_data = $virtualMachine_where->firstOrFail();

        $project_id = $virtualMachine_data->project_id;

        if (!ProjectMembersController::userInProject($project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        if ($request->remove_cd_rom) {
            $request->image_id = null;
        }

        $virtualMachine_where->update([
            'name' => $request->name
        ]);

        $this->changeVmImage($id, $request->image_id);

        ProjectActivityController::save($project_id, '修改了虚拟机: ' . $virtualMachine_data->name . '，新的名称为: ' . $request->name);


        return redirect()->back()->with('status', '已修改虚拟机。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $virtualMachine = new VirtualMachine();
        $virtualMachine_where = $virtualMachine->where('id', $id)->with('dash_user');
        $virtualMachine_data = $virtualMachine_where->firstOrFail();

        $project_id = $virtualMachine_data->project_id;

        if (!ProjectMembersController::userInProject($project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        if ($virtualMachine_data->status) {
            return redirect()->back()->with('status', '你必须关闭虚拟机电源才能删除。');
        }

        if ($this->deleteVm($id)) {
            ProjectActivityController::save($project_id, '删除了虚拟机 ' . $virtualMachine_data->name . '。');

            return redirect()->back()->with('status', '删除成功。');
        } else {
            return redirect()->back()->with('status', '暂时无法删除虚拟机。');
        }
    }

    public function deleteVm($id)
    {
        $virtualMachine = new VirtualMachine();
        $virtualMachine_where = $virtualMachine->where('id', $id);
        $virtualMachine_data = $virtualMachine_where->firstOrFail();

        try {
            $this->login($virtualMachine_data->server_id);
            $nodes = new Nodes();

            $nodes->deleteQemu($virtualMachine_data->node, $virtualMachine_data->vm_id);

            $this->deleteUser($virtualMachine_data->server_id, $virtualMachine_data->user_id);

            // 归还配额
            $server_data = Server::where('id', $virtualMachine_data->server_id)->where('type', 'pve')->firstOrFail();
            $template_data = VirtualMachineTemplate::where('id', $virtualMachine_data->template_id)->firstOrFail();
            Server::where('id', $virtualMachine_data->server_id)->update([
                'free_mem' => $server_data->free_mem + $template_data->memory,
                'free_disk' => $server_data->free_disk + $template_data->disk,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }

    public function deleteUser($server_id, $user_id)
    {
        $virtualMachineUser = VirtualMachineUser::where('id', $user_id);
        $virtualMachineUser_data = $virtualMachineUser->firstOrFail();
        $this->login($server_id);
        $access = new Access();
        $user_id = $virtualMachineUser_data->username . '@pve';
        $access->deleteUser($user_id);
        $virtualMachineUser->delete();
    }

    public function get_image($server_id, $json = true)
    {
        $cache_key = 'pve_server_image_cache_' . $server_id;
        if (Cache::has($cache_key)) {
            $iso = Cache::get($cache_key);
        } else {
            $cluster = new Cluster();
            $storage = new Storage();
            $nodes = new Nodes();

            $this->login($server_id);

            // 获取节点
            $response = $cluster->Resources('node');
            $node_pve = $response->data[0]->node;
            // 获取 ISO 存放位置
            $response = $storage->Storage('dir');
            $storage_local = $response->data[0]->storage;
            // 列出 ISO 列表
            $response = $nodes->listStorageContent($node_pve, $storage_local);
            $iso = $response->data;
            Cache::put($cache_key, $iso, 600);
        }

        if ($json) {
            return response()->json($iso);
        } else {
            return $iso;
        }
    }

    private function checkImage($server_id, $image_id)
    {
        $images = $this->get_image($server_id, false);
        if (array_key_exists($image_id, $images)) {
            return $images[$image_id]->volid;
        } else {
            abort(404);
        }
    }


    private function getVmStorage($server_id)
    {
        $cache_key = 'pve_server_vm_storage_name_' . $server_id;

        if (Cache::has($cache_key)) {
            $storage_name = Cache::get($cache_key);
        } else {
            $storage = new Storage();

            // 获取虚拟机存放区域
            $response = $storage->Storage('lvmthin');
            $storage_name = $response->data[0]->storage;
            Cache::put($cache_key, $storage_name);
        }

        return $storage_name;
    }

    private function validServer($server_id, $template_id)
    {
        // 验证服务器规格是否足以开设这个模板的虚拟机
        $server = Server::where('id', $server_id)->where('type', 'pve')->firstOrFail();
        $template = VirtualMachineTemplate::where('id', $template_id)->firstOrFail();
        // 服务器内存 减去 模板内存 是否大于 10G
        if (($server->free_mem - $template->memory) > 4096 && ($server->free_disk - $template->disk) > 20) {
            return $template;
        } else {
            return false;
        }
    }

    private function login($server_id)
    {
        $result = Server::where('id', $server_id)->where('type', 'pve')->firstOrFail();
        $credentials = explode('|', $result->token);
        $configure = [
            'hostname' => $result->address,
            'username' => $credentials[0],
            'password' => $credentials[1],
        ];
        Pve::Login($configure);
    }

    public function togglePower($id)
    {
        $virtualMachine = new VirtualMachine();
        $virtualMachine_where = $virtualMachine->where('id', $id);
        $virtualMachine_data = $virtualMachine_where->firstOrFail();

        $project_id = $virtualMachine_data->project_id;

        if (!ProjectMembersController::userInProject($project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }
        $this->login($virtualMachine_data->server_id);
        $nodes = new Nodes();

        $power = $virtualMachine_data->status;
        if ($power == 0) {
            $power = 1;
            $status = '开';
            // 打开虚拟机电源
            $nodes->qemuStart(
                $virtualMachine_data->node,
                $virtualMachine_data->vm_id
            );
        } elseif ($power == 1) {
            $power = 0;
            $status = '关';
            // 关闭虚拟机电源
            $nodes->qemuStop(
                $virtualMachine_data->node,
                $virtualMachine_data->vm_id
            );
        }
        $virtualMachine_where->update([
            'status' => $power
        ]);

        ProjectActivityController::save($project_id, '操作虚拟机 ' . $virtualMachine_data->name . ' 的电源状态为 ' . $status . ' 。');

        return response()->json(
            [
                'status' => 1,
                'power' => $power
            ]
        );
    }

    private function changeVmImage($id, $image_id)
    {
        $virtualMachine = new VirtualMachine();
        $virtualMachine_data = $virtualMachine->where('id', $id)->firstOrFail();
        $this->login($virtualMachine_data->server_id);
        $nodes = new Nodes();

        if (is_null($image_id)) {
            $image = 'none';
        } else {
            $image = $this->checkImage($virtualMachine_data->server_id, $image_id);
            if (!$image) {
                return redirect()->back()->with('status', '找不到镜像。');
            }
        }

        $nodes->setQemuConfig($virtualMachine_data->node, $virtualMachine_data->vm_id, [
            'ide2' => $image . ',media=cdrom',
        ]);
    }
}