<?php

namespace App\Jobs;

use App\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Http;

class UserStatusJob implements ShouldQueue
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
        $windows_servers = Server::where('type', 'windows')->get();


        // 获取 Windows 服务器 资源占用
        foreach ($windows_servers as $windows_server) {
            $result = Http::retry(5, 100)->get("http://{$windows_server->address}/status", [
                'token' => $windows_server->token
            ]);
            Server::where('id', $windows_server->id)->update([
                'cpu_usage' => $result['cpu'],
                'mem_usage' => $result['ram']
            ]);
        }
    }
}
