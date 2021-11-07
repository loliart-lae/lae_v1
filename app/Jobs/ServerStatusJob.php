<?php

namespace App\Jobs;

use App\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Http\Controllers\VirtualMachineController;

class ServerStatusJob implements ShouldQueue
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

        error_reporting(0);
        $windows_servers = Server::where('type', 'windows')->get();
        $pve = Server::where('type', 'pve')->get();


        // 获取 Windows 服务器 资源占用
        foreach ($windows_servers as $windows_server) {
            try {
                $result = Http::get("http://{$windows_server->address}/status", [
                    'token' => $windows_server->token
                ]);
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                $result = [];
                $result['cpu'] = 'unknown';
                $result['ram'] = 'unknown';
                // continue;
            }
            Cache::put('windows_server_status_' . $windows_server->id, json_encode([
                'cpu' => $result['cpu'],
                'mem' => $result['ram']
            ]), 600);
        }

        // 更新 PVE 以及各个虚拟机资源占用
        $virtualMachineController = new VirtualMachineController();
        foreach ($pve as $ve) {
            // 登录到VE
            $virtualMachineController->getAllVm($ve->id);
        }
    }
}