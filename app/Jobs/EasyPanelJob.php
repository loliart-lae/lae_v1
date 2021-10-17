<?php

namespace App\Jobs;

use Exception;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use App\Models\EasyPanelVirtualHost;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class EasyPanelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $config, $sKey;

    private $c = 'whm';
    private $r;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->r = rand(1, 10000);
        $this->sKey = md5($config['method'] . $config['token'] . $this->r);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $easyPanelVirtualHost = new EasyPanelVirtualHost();

        switch ($this->config['method']) {
            case 'add_vh':
                try {
                    $result = Http::retry(5, 100)->timeout(1200)->asForm()->post("http://{$this->config['address']}/api/index.php", [
                        'a' => $this->config['method'],
                        'r' => $this->r,
                        's' => $this->sKey,
                        'c' => $this->c,
                        'json' => 1,
                        'init' => 1,
                        'cdn' => $this->config['cdn'],
                        'name' => $this->config['username'],
                        'passwd' => $this->config['password'],
                        'module' => 'php',
                        'web_quota' => $this->config['web_quota'],
                        'db_quota' => $this->config['db_quota'],
                        'db_type' => 'mysql',
                        'subdir_flag' => 1,
                        'subdir' => 'wwwroot',
                        'max_subdir' => 10,
                        'domain' => $this->config['domain'],
                        'port' => '80,443s',
                        'max_worker' => 8,
                        'max_queue' => 2,
                        'cron' => '1',
                        'access' => 1,
                        'ftp' => '1',
                        'ftp_usl' => $this->config['ftp_usl'],
                        'ftp_dsl' => $this->config['ftp_dsl'],
                        'speed_limit' => $this->config['speed_limit'],
                        'log_handle' => 1,
                        'ssi' => 1,
                        'htaccess' => 1,
                    ]);

                    // if ($result['result'] != 200) {
                    //     Message::send('此时无法创建虚拟主机。', $this->config['user']);
                    //     throw new \Exception('无法创建虚拟主机');
                    // }
                    $easyPanelVirtualHost->where('id', $this->config['inst_id'])->update([
                        'status' => 'active',
                    ]);

                    Message::send('虚拟主机 已经准备好了。FTP与数据库密码为: ' . $this->config['passwd'], $this->config['user']);
                } catch (Exception $e) {
                    Http::retry(5, 100)->get("http://{$this->config['address']}/api/index.php", [
                        'id' => $this->config['inst_id'],
                        'token' => $this->config['token'],
                    ]);

                    Message::send('此时无法创建虚拟主机。', $this->config['user']);
                    $easyPanelVirtualHost->where('id', $this->config['inst_id'])->delete();
                }
                break;

            case 'del_vh':
                $result = Http::retry(5, 100)->timeout(1200)->asForm()->post("http://{$this->config['address']}/api/index.php", [
                    'a' => $this->config['method'],
                    'r' => $this->r,
                    's' => $this->sKey,
                    'c' => $this->c,
                    'json' => 1,
                    'name' => $this->config['username'],
                ]);

                if ($result['result'] != 200) {
                    throw new \Exception('无法删除 虚拟主机');
                }

                break;

            case 'change_password':
                $result = Http::retry(5, 100)->timeout(1200)->asForm()->post("http://{$this->config['address']}/api/index.php", [
                    'a' => $this->config['method'],
                    'r' => $this->r,
                    's' => $this->sKey,
                    'c' => $this->c,
                    'json' => 1,
                    'name' => $this->config['username'],
                    'password' => $this->config['password']
                ]);

                if ($result['result'] != 200) {
                    throw new \Exception('修改密码失败 虚拟主机');
                }

                break;

            default:
                break;
        }
    }
}
