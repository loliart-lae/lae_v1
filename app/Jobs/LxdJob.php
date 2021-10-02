<?php

namespace App\Jobs;

use Exception;
use App\Models\Server;
use App\Models\Forward;
use App\Models\Message;
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

        switch ($this->config['method']) {
            case 'init':
                try {
                    $result = Http::retry(5, 100)->timeout(1200)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}", [
                        'id' => $this->config['inst_id'],
                        'cpu' => $this->config['cpu'],
                        'mem' => $this->config['mem'],
                        'image' => $this->config['image'],
                        'disk' => $this->config['disk'],
                        'password' => $this->config['password'],
                        'token' => $this->config['token']
                    ]);

                    if ($result['status'] == 0) {
                        throw new \Exception('无法开设容器');
                    }
                    $lxd->where('id', $this->config['inst_id'])->update([
                        'status' => 'running',
                        'lan_ip' => $result['lan_ip'],
                    ]);
                    // dispatch(new SendEmailJob($email, "久等了，您的 Linux 容器已经准备好了。"))->onQueue('mail');
                    Message::send('Linux 容器已经准备好了。', $this->config['user']);
                } catch (Exception $e) {
                    Message::send('此时无法开设容器。', $this->config['user']);
                    $lxd->where('id', $this->config['inst_id'])->update([
                        'status' => 'failed',
                        'lan_ip' => '此时无法创建，请尝试销毁后重试。'
                    ]);
                }

                break;

            case 'delete':
                Http::retry(5, 100)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}", [
                    'id' => $this->config['inst_id'],
                    'token' => $this->config['token'],
                ]);

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
                try {
                    Http::retry(2, 1)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}", [
                        'id' => $this->config['inst_id'],
                        'token' => $this->config['token'],
                    ]);
                } catch (Exception $e) {
                    Message::send($this->config['server_name'] . ' 的健康检查出现问题， LAE正在紧急修复。<br />受影响的 Linux 容器: ' . $this->config['inst_name'] . '', $this->config['user']);
                }

                break;

            case 'forward':
                try {
                    Http::retry(5, 100)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}", [
                        'id' => $this->config['inst_id'],
                        'from' => $this->config['from'],
                        'to' => $this->config['to'],
                        'token' => $this->config['token']
                    ]);
                    $forward->where('lxd_id', $this->config['inst_id'])->update([
                        'status' => 'active',
                    ]);
                } catch (Exception $e) {
                    Message::send('此时无法新建端口转发。', $this->config['user']);
                    $forward->where('lxd_id', $this->config['inst_id'])->update([
                        'status' => 'failed',
                    ]);
                }
                break;

            case 'forward_delete':
                Http::retry(5, 100)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}", [
                    'id' => $this->config['inst_id'],
                    'to' => $this->config['to'],
                    'token' => $this->config['token']
                ]);
                break;

            case 'resize':

                // 归还原有的服务器空间，然后填入新的空间
                $server_id = $this->config['server_id'];
                $server_query = Server::where('id', $server_id);
                $server_data = $server_query->firstOrFail();

                // 现有空间
                $server_data_memory = $server_data->free_mem;
                $server_data_disk = $server_data->free_disk;

                // 旧模板
                $old_template = LxdTemplate::where('id', $this->config['old_template'])->firstOrFail();

                // 计算内存
                $server_data_memory += $old_template->mem;
                $server_data_disk += $old_template->disk;

                // New Template
                $new_template = LxdTemplate::where('id', $this->config['new_template'])->firstOrFail();

                // Update memory
                $server_data_memory -= $new_template->mem;
                $server_data_disk -= $new_template->disk;

                if ($server_data_memory <= 1024 || $server_data_disk <= 5) {
                    $lxd->where('id', $this->config['inst_id'])->update([
                        'status' => 'running',
                        'template_id' => $this->config['old_template']
                    ]);
                    // 通知用户执行失败
                    // dispatch(new SendEmailJob($email, '无法调整容器模板，因为服务器上已经没有更多的资源了。'))->onQueue('mail');
                    Message::send('无法调整容器模板，因为服务器上已经没有更多的资源了。', $this->config['user']);
                } else {
                    try {
                        Http::retry(5, 100)->timeout(600)->get("http://{$this->config['address']}:821/lxd/{$this->config['method']}", [
                            'id' => $this->config['inst_id'],
                            'cpu' => $new_template->cpu,
                            'mem' => $new_template->mem,
                            'disk' => $new_template->disk,
                            'token' => $this->config['token'],
                        ]);
                        $server_query->update([
                            'free_mem' => $server_data_memory,
                            'free_disk' => $server_data_disk,
                        ]);

                        $lxd->where('id', $this->config['inst_id'])->update([
                            'status' => 'running',
                        ]);
                        Message::send('容器模板已经调整完成。', $this->config['user']);
                    } catch (Exception $e) {
                        Message::send('此时无法调整容器模板。', $this->config['user']);
                    }
                }

                break;
        }
    }
}
