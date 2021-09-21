<?php

namespace App\Jobs;

use App\Models\Server;
use App\Models\LxdContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CalcServerJob implements ShouldQueue
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

        // 重新计算服务器剩余空间
        $lxdServers = Server::where('type', 'container')->get();

        foreach ($lxdServers as $server) {
            $used_disk = 5;
            $memory = 1024;
            $lxdContainers = LxdContainer::with(['template', 'server', 'forward', 'project'])->where('status', 'running')->where('server_id', $server->id)->get();
            foreach ($lxdContainers as $lxd) {
                $used_disk += $lxd->template->disk;
                $memory += $lxd->template->mem;
            }
            Server::where('id', $server->id)->update([
                'free_disk' => $used_disk,
                'free_num' => $memory
            ]);
        }
    }
}
