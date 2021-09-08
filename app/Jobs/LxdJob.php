<?php

namespace App\Jobs;

use App\Models\Server;
use App\Models\Forward;
use App\Models\LxdContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class LxdJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $config;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $lxd = new LxdContainer();
        $forward = new Forward();

        switch ($this->config['method']) {
            case 'init':
                $result = Http::timeout(300)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}?id={$this->config['inst_id']}&cpu={$this->config['cpu']}&mem={$this->config['mem']}&image={$this->config['image']}&disk={$this->config['disk']}&password={$this->config['password']}&download=10&upload=10&token={$this->config['token']}");

                $lxd->where('id', $this->config['inst_id'])->update([
                    'status' => 'running',
                    'lan_ip' => $result['lan_ip'],
                ]);
                break;
            case 'delete':
                Http::timeout(300)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}?id={$this->config['inst_id']}&token={$this->config['token']}");
                // 归还服务器配额
                $server_id = $this->config['server_id'];
                $server_query = Server::where('id', $server_id);
                $server_data = $server_query->firstOrFail();
                $old_memory = $server_data->mem;
                $old_disk = $server_data->disk;

                $server_query->update([
                    'free_mem' => $old_memory + $this->config['mem'],
                    'free_disk' => $old_disk+ $this->config['disk'],
                ]);

                break;

            case 'start':
                Http::timeout(300)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}?id={$this->config['inst_id']}&token={$this->config['token']}");
                break;

            case 'forward':
                Http::timeout(300)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}?id={$this->config['inst_id']}&from={$this->config['from']}&to={$this->config['to']}&token={$this->config['token']}");
                $forward->where('lxd_id', $this->config['inst_id'])->update([
                    'status' => 'active',
                ]);
                break;

            case 'forward_delete':
                Http::timeout(300)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}?id={$this->config['inst_id']}&to={$this->config['to']}&token={$this->config['token']}");
                break;
        }
    }
}
