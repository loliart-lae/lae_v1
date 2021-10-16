<?php

namespace App\Jobs;

use App\Models\EasyPanelVirtualHost;
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
        $lxdServers = Server::where('type', 'container')->get();
        $easyPanels = Server::where('type', 'easypanel')->get();

        // 重新计算服务器剩余空间
        foreach ($lxdServers as $server) {
            $used_disk = 5;
            $memory = 1024;
            $lxdContainers = LxdContainer::with(['template', 'server', 'forward'])->where('server_id', $server->id)->get();
            foreach ($lxdContainers as $lxd) {
                $used_disk += $lxd->template->disk;
                $memory += $lxd->template->mem;
            }
            $free_disk = $server->disk - $used_disk;
            $free_mem = $server->mem - $memory;

            Server::where('id', $server->id)->update([
                'free_disk' => $free_disk,
                'free_mem' => $free_mem
            ]);
        }

        foreach ($easyPanels as $server) {
            $used_disk = 5;
            $memory = 1024;
            $easyPanelVirtualHosts = EasyPanelVirtualHost::with(['template', 'server'])->where('server_id', $server->id)->get();
            foreach ($easyPanelVirtualHosts as $easyPanelVirtualHost) {
                $used_disk += $easyPanelVirtualHost->db_quota + $easyPanelVirtualHost->web_quota;
            }
            $free_disk = $server->disk - $used_disk;

            Server::where('id', $server->id)->update([
                'free_disk' => $free_disk,
            ]);
        }

    }
}
