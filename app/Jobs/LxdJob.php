<?php

namespace App\Jobs;

use App\Models\Server;
use App\Models\Forward;
use App\Jobs\SendEmailJob;
use App\Models\LxdTemplate;
use App\Models\LxdContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
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
        if (isset($this->config['user'])) {
            $email = User::find($this->config['user'])->email;
        }


        switch ($this->config['method']) {
            case 'init':
                $result = Http::retry(5, 100)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}?id={$this->config['inst_id']}&cpu={$this->config['cpu']}&mem={$this->config['mem']}&image={$this->config['image']}&disk={$this->config['disk']}&password={$this->config['password']}&download=10&upload=10&token={$this->config['token']}");

                $lxd->where('id', $this->config['inst_id'])->update([
                    'status' => 'running',
                    'lan_ip' => $result['lan_ip'],
                ]);
                dispatch(new SendEmailJob($email, "容器 {$this->config['inst_id']} 调度成功。"))->onQueue('mail');
                break;

            case 'delete':
                Http::retry(5, 100)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}?id={$this->config['inst_id']}&token={$this->config['token']}");
                // 归还服务器配额
                $server_id = $this->config['server_id'];
                $server_query = Server::where('id', $server_id);
                $server_data = $server_query->firstOrFail();
                $old_memory = $server_data->free_mem;
                $old_disk = $server_data->free_disk;

                $server_query->update([
                    'free_mem' => $old_memory + $this->config['mem'],
                    'free_disk' => $old_disk + $this->config['disk'],
                ]);

                break;

            case 'start':
                Http::retry(5, 100)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}?id={$this->config['inst_id']}&token={$this->config['token']}");
                break;

            case 'forward':
                Http::retry(5, 100)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}?id={$this->config['inst_id']}&from={$this->config['from']}&to={$this->config['to']}&token={$this->config['token']}");
                $forward->where('lxd_id', $this->config['inst_id'])->update([
                    'status' => 'active',
                ]);
                break;

            case 'forward_delete':
                Http::retry(5, 100)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}?id={$this->config['inst_id']}&to={$this->config['to']}&token={$this->config['token']}");
                break;

            case 'resize':

                // 归还原有的服务器空间，然后填入新的空间
                $server_id = $this->config['server_id'];
                $server_query = Server::where('id', $server_id);
                $server_data = $server_query->firstOrFail();

                $server_data_memory = $server_data->free_mem;
                $server_data_disk = $server_data->free_disk;

                // Old Template
                $old_template = LxdTemplate::where('id', $this->config['old_template'])->firstOrFail();

                // Update memory
                $server_data_memory -= $old_template->mem;
                $server_data_memory -= $old_template->disk;

                // New Template
                $new_template = LxdTemplate::where('id', $this->config['new_template'])->firstOrFail();

                // Update memory
                $server_data_memory += $new_template->mem;
                $server_data_disk += $new_template->disk;

                if ($server_data_memory <= 1024 || $server_data_disk <= 5) {
                    $lxd->where('id', $this->config['inst_id'])->update([
                        'status' => 'running',
                        'template_id' => $this->config['old_template']
                    ]);
                    // 通知用户执行失败
                    dispatch(new SendEmailJob($email, '无法调整容器模板，因为服务器上已经没有更多的资源了。'))->onQueue('mail');
                } else {
                    Http::retry(5, 100)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}?id={$this->config['inst_id']}&cpu={$new_template->cpu}&mem={$new_template->mem}&disk={$new_template->disk}&token={$this->config['token']}");
                    $server_query->update([
                        'free_mem' => $server_data_memory,
                        'free_disk' => $server_data_disk,
                    ]);

                    $lxd->where('id', $this->config['inst_id'])->update([
                        'status' => 'running',
                    ]);
                }

                break;
        }
    }
}
