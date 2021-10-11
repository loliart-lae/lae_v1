<?php

namespace App\Jobs;

use App\Models\Server;
use App\Models\LxdContainer;
use App\Models\RemoteDesktop;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
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
        $windows_servers = Server::where('type', 'windows')->get();

        // 重新计算服务器剩余空间


        foreach ($lxdServers as $server) {
            $used_disk = 5;
            $memory = 1024;
            $lxdContainers = LxdContainer::with(['template', 'server', 'forward', 'project'])->where('server_id', $server->id)->get();
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

        // 获取 Windows 服务器 资源占用
        foreach ($windows_servers as $windows_server) {
            $result = Http::retry(5, 100)->get("http://{$windows_server->address}/status", [
                'token' => $this->config['token']
            ]);
            
        }
    }
}
