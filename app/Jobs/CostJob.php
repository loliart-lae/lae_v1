<?php

namespace App\Jobs;

use App\Models\Server;
use App\Models\Forward;
use App\Models\Project;
use App\Jobs\SendEmailJob;
use App\Models\LxdContainer;
use App\Models\RemoteDesktop;
use Illuminate\Bus\Queueable;
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
        $project = new Project();
        $forward = new Forward();
        $remote_desktops = RemoteDesktop::with(['server', 'project'])->where('status', 'active')->get();
        // $server = new Server();


        foreach ($lxdContainers as $lxd) {
            // 金额
            $project_id = $lxd->project->id;
            $project_where = $project->where('id', $project_id);

            $need_pay = $lxd->server->price + $lxd->template->price + (count($lxd->forward) * $lxd->server->forward_price);

            $current_project_balance = $project_where->firstOrFail()->balance;

            // if ($current_project_balance - $need_pay >= 99.50 || $current_project_balance - $need_pay <= 100) {
            //     // 积分不足，提醒用户
            //     // User email
            //     $email = User::where('id', $project_where->first()->user_id)->first()->email;
            //     dispatch(new SendEmailJob($email, '项目积分不足，诺要继续使用，请保持您的项目积分充足'))->onQueue('mail');
            // }

            if ($current_project_balance - $need_pay < 0) {
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
                ];
                dispatch(new LxdJob($config));
            } else {
                $config = [
                    'inst_id' => $lxd->id,
                    'method' => 'start',
                    'address' => $lxd->server->address,
                    'token' => $lxd->server->token,
                    'user' => $lxd->project->user_id,
                ];
                dispatch(new LxdJob($config));

                // 扣费
                $project_where->update(['balance' => $current_project_balance - $need_pay]);
            }
        }

        // 获取远程桌面并计费
        foreach ($remote_desktops as $remote_desktop) {
            // 金额
            $project_id = $remote_desktop->project->id;
            $project_where = $project->where('id', $project_id);

            $need_pay = $remote_desktop->server->price;

            $current_project_balance = $project_where->firstOrFail()->balance;

            if ($current_project_balance - $need_pay < 0) {
                // 扣费失败，删除账号

                RemoteDesktop::where('id', $remote_desktop->id)->delete();
                $config = [
                    'inst_id' => $remote_desktop->id,
                    'method' => 'delete',
                    'address' => $remote_desktop->server->address,
                    'token' => $remote_desktop->server->token,
                ];
                dispatch(new RemoteDesktopJob($config));
            } else {
                // 扣费
                $project_where->update(['balance' => $current_project_balance - $need_pay]);
            }
        }
    }
}
