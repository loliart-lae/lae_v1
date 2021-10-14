<?php

namespace App\Jobs;

use Exception;
use App\Models\Server;
use App\Models\Message;
use App\Models\StaticPage;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class StaticPageJob implements ShouldQueue
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
        $staticPage = new StaticPage();

        switch ($this->config['method']) {
            case 'create':
                try {
                    $result = Http::retry(5, 100)->get("http://{$this->config['address']}/site/create", [
                        'username' => $this->config['username'],
                        'password' => $this->config['password'],
                        'id' => $this->config['inst_id'],
                        'email' => $this->config['email'],
                        'token' => $this->config['token'],
                        'domain' => $this->config['domain']
                    ]);

                    if ($result['status'] != 1) {
                        throw new Exception('error read response.');
                    }

                    $staticPage->where('id', $this->config['inst_id'])->update([
                        'status' => 'active',
                    ]);

                    Message::send('成功新建了 静态托管。', $this->config['user']);
                } catch (Exception $e) {
                    $staticPage->where('id', $this->config['inst_id'])->delete();
                    Message::send('此时无法新建 静态托管。', $this->config['user']);
                    Http::retry(5, 100)->get("http://{$this->config['address']}/site/delete", [
                        'id' => $this->config['inst_id'],
                        'token' => $this->config['token']
                    ]);
                }

                // dispatch(new SendEmailJob($email, "久等了，您的 静态托管 已经准备好了。"))->onQueue('mail');

                break;

            case 'delete':
                $result = Http::retry(5, 100)->get("http://{$this->config['address']}/site/delete", [
                    'id' => $this->config['inst_id'],
                    'token' => $this->config['token']
                ]);
                if ($result['status'] != 1) {
                    throw new Exception('error read response.');
                }
                break;

            case 'count':
                $servers = Server::where('type', 'staticPage')->get();
                foreach ($servers as $server) {
                    $result = Http::retry(5, 100)->get("http://{$server->address}/site/count", [
                        'token' => $server->token
                    ])->body();

                    $result = json_decode($result);

                    $result = $result->data->info;

                    // var_dump($result);

                    foreach ($result as $data) {;
                        StaticPage::where('id', $data->id)->update(['used_disk' => $data->size]);
                    }
                }

                break;

            case 'passwd':
                try {
                    $result = Http::retry(5, 100)->get("http://{$this->config['address']}/site/passwd", [
                        'id' => $this->config['inst_id'],
                        'password' => $this->config['password'],
                        'token' => $this->config['token']
                    ]);

                    Message::send('你的 静态托管FTP 的新密码已经启用。', $this->config['user']);

                    // if ($result['status']) {
                    //     Message::send('你的 共享的 Windows 远程桌面 的新密码已经启用。', $this->config['user']);
                    // } else {
                    //     Message::send('此时无法更改密码。', $this->config['user']);
                    // }
                } catch (Exception $e) {
                    Message::send('此时无法更改 静态托管FTP 的密码。', $this->config['user']);
                }
                break;

            case 'backup':
                try {
                    Message::send('正在备份静态站点 ' . $this->config['name'] . '。', $this->config['user']);

                    $result = Http::retry(5, 100)->get("http://{$this->config['address']}/site/backup", [
                        'id' => $this->config['inst_id'],
                        'filename' => $this->config['filename'],
                        'token' => $this->config['token']
                    ]);

                    if ($result['status'] != 1) {
                        if ($result['status'] != 1) {
                            throw new Exception('error read response.');
                        }
                    }

                    dispatch(new SendEmailJob($this->config['email'], "静态站点 {$this->config['name']} 备份完成，备份文件已保存至站点的根目录下。您可以访问 https://{$this->config['domain']}/{$this->config['filename']}.tar.gz 下载，或者通过FTP传输。下载/传输完成后请确认并删除备份文件。"))->onQueue('mail');

                    $staticPage->where('id', $this->config['inst_id'])->update([
                        'status' => 'active',
                    ]);

                    Message::send('静态站点 ' . $this->config['name'] . ' 备份完成。', $this->config['user']);


                } catch (Exception $e) {
                    Message::send('此时无法备份静态托管 ' . $this->config['name'] . '。', $this->config['user']);
                }
                break;
        }
    }
}
