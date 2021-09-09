<?php

namespace App\Jobs;

use App\Models\Server;
use App\Models\Forward;
use App\Models\Project;
use App\Models\LxdContainer;
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

        $lxdContainers = LxdContainer::with(['template', 'server', 'forward', 'project'])->where('status', 'running')->where('status', 'resizing')->get();
        $project = new Project();
        $forward = new Forward();
        // $server = new Server();


        foreach ($lxdContainers as $lxd) {
            // 金额
            $project_id = $lxd->project->id;
            $project_where = $project->where('id', $project_id);

            $need_pay = $lxd->server->price + $lxd->template->price + (count($lxd->forward) * $lxd->server->forward_price);

            $current_project_balance = $project_where->firstOrFail()->balance;

            if ($current_project_balance - $need_pay >= 95 || $current_project_balance - $need_pay <= 100) {
                // 积分不足，提醒用户
                dispatch(new SendEmailJob(User::find($project_where->user_id)->email, '项目积分不足，诺要继续使用，请保持您的项目积分充足'));
            }

            if ($current_project_balance - $need_pay <= 0) {
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
                ];
                dispatch(new LxdJob($config));
            } else {
                $config = [
                    'inst_id' => $lxd->id,
                    'method' => 'start',
                    'address' => $lxd->server->address,
                    'token' => $lxd->server->token,
                ];
                dispatch(new LxdJob($config));
                $project_where->update(['balance' => $current_project_balance - $need_pay]);
            }


        }
    }
}
