<?php

namespace App\Jobs;

use App\Models\Server;
use App\Models\Tunnel;
use App\Models\Forward;
use App\Models\Project;
use App\Jobs\SendEmailJob;
use App\Models\StaticPage;
use App\Models\LxdContainer;
use App\Models\RemoteDesktop;
use Illuminate\Bus\Queueable;
use App\Models\ServerBalanceCount;
use Illuminate\Foundation\Auth\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

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
        // 挨个获取容器并计算扣费

        $lxdContainers = LxdContainer::with(['template', 'server', 'forward', 'project'])->where('status', 'running')->get();
        // $project = new Project();
        $forward = new Forward();
        $remote_desktops = RemoteDesktop::with(['server', 'project'])->where('status', 'active')->get();
        $tunnels = Tunnel::with(['server', 'project'])->where('protocol', '!=', 'xtcp')->get();
        // $server = new Server();
        $staticPages = StaticPage::with(['server', 'project'])->where('status', 'active')->get();
        $serverBalanceCount = new ServerBalanceCount();


        foreach ($lxdContainers as $lxd) {
            // 金额
            $project_id = $lxd->project->id;

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
            } else {
                $config = [
                    'inst_id' => $lxd->id,
                    'method' => 'start',
                    'address' => $lxd->server->address,
                    'token' => $lxd->server->token,
                    'user' => $lxd->project->user_id,
                    'server_name' => $lxd->server->name,
                    'inst_name' => $lxd->name
                ];
                dispatch(new LxdJob($config));

                $serverBalanceCount->server_id = $lxd->server_id;
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
            } else {
                $serverBalanceCount->server_id = $remote_desktop->server_id;
                $serverBalanceCount->value = $need_pay;
                $serverBalanceCount->save();
            }
        }

        // 获取Frp Tunnel 并计费
        foreach ($tunnels as $tunnel) {
            // 金额
            $project_id = $tunnel->project->id;

            $need_pay = $tunnel->server->price;

            if (!Project::cost($project_id, $need_pay)) {
                // 扣费失败，删除账号
                Tunnel::where('id', $tunnel->id)->delete();
            } else {
                $serverBalanceCount->server_id = $tunnel->server_id;
                $serverBalanceCount->value = $need_pay;
                $serverBalanceCount->save();
            }
        }

        // 获取 StaticPage 并计费
        foreach ($staticPages as $staticPage) {
            // 金额
            $project_id = $staticPage->project->id;

            $need_pay = $staticPage->used_disk * $staticPage->server->price;

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
            } else {
                $serverBalanceCount->server_id = $staticPage->server_id;
                $serverBalanceCount->value = $need_pay;
                $serverBalanceCount->save();
            }
        }
    }
}
