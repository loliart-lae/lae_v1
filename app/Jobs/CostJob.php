<?php

namespace App\Jobs;

use App\Models\Server;
use App\Models\Tunnel;
use App\Models\Forward;
use App\Models\Message;
use App\Models\Project;
use App\Jobs\SendEmailJob;
use App\Models\StaticPage;
use App\Models\LxdContainer;
use App\Models\RemoteDesktop;
use Illuminate\Bus\Queueable;
use App\Models\VirtualMachine;
use App\Models\PterodactylServer;
use App\Models\ServerBalanceCount;
use Illuminate\Support\Facades\DB;
use App\Models\EasyPanelVirtualHost;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Http\Controllers\PterodactylController;
use App\Http\Controllers\VirtualMachineController;

class CostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', '1024M');
        DB::connection()->disableQueryLog();

        // 挨个获取容器并计算扣费

        $lxdContainers = LxdContainer::with(['template', 'server', 'forward', 'project'])->get();
        // $project = new Project();
        $forward = new Forward();
        $remote_desktops = RemoteDesktop::with(['server', 'project'])->where('status', 'active')->get();
        $tunnels = Tunnel::with(['server', 'project'])->where('protocol', '!=', 'xtcp')->get();
        // $server = new Server();
        $staticPages = StaticPage::with(['server', 'project'])->where('status', 'active')->get();
        $easyPanelVirtualHosts = EasyPanelVirtualHost::with(['server', 'project', 'template'])->where('status', 'active')->get();
        $pterodactylServers = PterodactylServer::with(['template'])->get();
        $vms = VirtualMachine::with(['template', 'server', 'project'])->get();

        foreach ($lxdContainers as $lxd) {
            // 金额
            $project_id = $lxd->project->id;

            if ($lxd->status == 'off') {
                $need_pay = $lxd->server->price + $lxd->template->price + (count($lxd->forward) * $lxd->server->forward_price) * 0.9;
            }

            $need_pay = $lxd->server->price + $lxd->template->price + (count($lxd->forward) * $lxd->server->forward_price);

            // if ($current_project_balance - $need_pay >= 99.50 || $current_project_balance - $need_pay <= 100) {
            //     // 积分不足，提醒用户
            //     // User email
            //     $email = User::where('id', $project_where->first()->user_id)->first()->email;
            //     dispatch(new SendEmailJob($email, '项目积分不足，诺要继续使用，请保持您的项目积分充足'))->onQueue('mail');
            // }

            if (!Project::cost($project_id, $need_pay)) {
                // 扣费失败，删除容器
                // $forward_where = $forward->where('lxd_id', $lxd->id);

                // 删除转发
                foreach ($lxd->forward as $lxd_forward) {

                    // 删除SQL

                    $forward->where('lxd_id', $lxd->id)->delete();
                    $config = [
                        'forward_id' => $lxd_forward->id,
                        'inst_id' => $lxd->id,
                        'method' => 'forward_delete',
                        'to' => $lxd_forward->to,
                        'token' => $lxd->server->token,
                        'address' => $lxd->server->address,
                        'user' => $lxd->project->user_id,
                    ];
                    dispatch(new LxdJob($config));
                }

                // 删除容器

                LxdContainer::where('id', $lxd->id)->delete();
                $config = [
                    'inst_id' => $lxd->id,
                    'method' => 'delete',
                    'address' => $lxd->server->address,
                    'token' => $lxd->server->token,
                    'user' => $lxd->project->user_id,
                    'server_id' => $lxd->server->id,
                ];
                dispatch(new LxdJob($config));
                Message::send('容器 ' . $lxd->name . ' 因为积分不足而自动删除。', $lxd->project->user_id);
            } else {
                $serverBalanceCount = new ServerBalanceCount();
                $serverBalanceCount->server_id = $lxd->server_id;
                $serverBalanceCount->user_id = $lxd->project->user_id;
                $serverBalanceCount->value = $need_pay;
                $serverBalanceCount->save();
            }
        }

        // 获取远程桌面并计费
        foreach ($remote_desktops as $remote_desktop) {
            // 金额
            $project_id = $remote_desktop->project->id;

            $need_pay = $remote_desktop->server->price;

            if (!Project::cost($project_id, $need_pay)) {
                // 扣费失败，删除账号

                RemoteDesktop::where('id', $remote_desktop->id)->delete();
                $config = [
                    'inst_id' => $remote_desktop->id,
                    'method' => 'delete',
                    'address' => $remote_desktop->server->address,
                    'username' => $remote_desktop->username,
                    'token' => $remote_desktop->server->token,
                ];

                dispatch(new RemoteDesktopJob($config))->onQueue('remote_desktop');;
                Message::send('共享的 Windows 远程桌面' . $remote_desktop->username . ' 因为积分不足而自动删除。', $remote_desktop->project->user_id);
            } else {
                $serverBalanceCount = new ServerBalanceCount();
                $serverBalanceCount->server_id = $remote_desktop->server_id;
                $serverBalanceCount->user_id = $remote_desktop->project->user_id;
                $serverBalanceCount->value = $need_pay;
                $serverBalanceCount->save();
            }
        }

        // 获取Frp Tunnel 并计费
        foreach ($tunnels as $tunnel) {
            // 金额
            $project_id = $tunnel->project->id;

            $need_pay = $tunnel->server->price;
            if (!is_null($tunnel->ping) || (new \Illuminate\Support\Carbon)->diffInSeconds((new \Illuminate\Support\Carbon)->parse($tunnel->ping), false) > -70) {
                $need_pay /= 1.5;
            }


            if (!Project::cost($project_id, $need_pay)) {
                // 扣费失败，删除账号
                Tunnel::where('id', $tunnel->id)->delete();
                Message::send('穿透隧道' . $tunnel->name . ' 因为积分不足而自动删除。', $tunnel->project->user_id);
            } else {
                $serverBalanceCount = new ServerBalanceCount();
                $serverBalanceCount->server_id = $tunnel->server_id;
                $serverBalanceCount->value = $need_pay;
                $serverBalanceCount->user_id = $tunnel->project->user_id;
                $serverBalanceCount->save();
            }
        }

        // 获取 StaticPage 并计费
        foreach ($staticPages as $staticPage) {
            // 金额
            $project_id = $staticPage->project->id;

            if ($staticPage->used_disk < 10) {
                $need_pay = 0;
            } else {
                $need_pay = $staticPage->used_disk * $staticPage->server->price * 0.01;
            }


            if (!Project::cost($project_id, $need_pay)) {
                // 扣费失败，删除主机
                StaticPage::where('id', $staticPage->id)->delete();

                // 调度删除任务
                $config = [
                    'method' => 'delete',
                    'inst_id' => $staticPage->id,
                    'address' => $staticPage->server->address,
                    'token' => $staticPage->server->token
                ];
                dispatch(new StaticPageJob($config));
                Message::send('静态页面' . $staticPage->name . ' 因为积分不足而自动删除。', $staticPage->project->user_id);
            } else {
                $serverBalanceCount = new ServerBalanceCount();
                $serverBalanceCount->server_id = $staticPage->server_id;
                $serverBalanceCount->value = $need_pay;
                $serverBalanceCount->user_id = $staticPage->project->user_id;
                $serverBalanceCount->save();
            }
        }

        // 获取 EasyPanel 并计费
        foreach ($easyPanelVirtualHosts as $easyPanelVirtualHost) {
            // 金额
            $project_id = $easyPanelVirtualHost->project->id;

            $need_pay = $easyPanelVirtualHost->server->price + $easyPanelVirtualHost->template->price;

            if (!Project::cost($project_id, $need_pay)) {
                // 扣费失败，删除主机
                EasyPanelVirtualHost::where('id', $easyPanelVirtualHost->id)->delete();

                // 调度删除任务
                $config = [
                    'method' => 'del_vh',
                    'name' => $easyPanelVirtualHost->name,
                    'inst_id' => $easyPanelVirtualHost->id,
                    'address' => $easyPanelVirtualHost->server->address,
                    'token' => $easyPanelVirtualHost->server->token
                ];
                dispatch(new EasyPanelJob($config));
                Message::send('EasyPanel 主机' . $easyPanelVirtualHost->name . ' 因为积分不足而自动删除。', $easyPanelVirtualHost->project->user_id);
            } else {
                $serverBalanceCount = new ServerBalanceCount();
                $serverBalanceCount->server_id = $easyPanelVirtualHost->server_id;
                $serverBalanceCount->value = $need_pay;
                $serverBalanceCount->user_id = $easyPanelVirtualHost->project->user_id;
                $serverBalanceCount->save();
            }
        }

        // 获取 Pterodactyl 并计费
        foreach ($pterodactylServers as $pterodactylServer) {
            // 金额
            $project_id = $pterodactylServer->project_id;

            $need_pay = $pterodactylServer->template->price;

            if (!Project::cost($project_id, $need_pay)) {
                // 扣费失败，删除服务器
                $pterodactylController = new PterodactylController();
                $pterodactylController->deleteServerById($pterodactylServer->server_id);
                Message::send('游戏服务器 ' . $pterodactylServer->name . ' 因为积分不足而自动删除。', $project_id);
            } else {
                $serverBalanceCount = new ServerBalanceCount();
                $serverBalanceCount->server_id = config('app.pterodactyl_server_id');
                $serverBalanceCount->value = $need_pay;
                $serverBalanceCount->user_id = $pterodactylServer->project->user_id;
                $serverBalanceCount->save();
            }
        }

        // 获取 Virtual Machine 并计费
        foreach ($vms as $vm) {
            // 金额
            $project_id = $vm->project_id;

            $need_pay = $vm->template->price + $vm->server->price;

            if (!Project::cost($project_id, $need_pay)) {
                // 扣费失败，删除服务器
                $VirtualMachineController = new VirtualMachineController();
                $VirtualMachineController->deleteVm($vm->id);
                $VirtualMachineController->deleteUser($vm->server_id, $vm->user_id);
                Message::send('虚拟机 ' . $vm->name . ' 因为积分不足而自动删除。', $project_id);
            } else {
                $serverBalanceCount = new ServerBalanceCount();
                $serverBalanceCount->server_id = $vm->server_id;
                $serverBalanceCount->value = $need_pay;
                $serverBalanceCount->user_id = $vm->project->user_id;
                $serverBalanceCount->save();
            }
        }
    }
}