<?php

namespace App\Jobs;

use Exception;
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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
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
                    $result = Http::retry(5, 100)->get("http://{$this->config['address']}/create", [
                        'username' => $this->config['username'],
                        'password' => $this->config['password'],
                        'id' => $this->config['inst_id'],
                        'email' => $this->config['email'],
                        'token' => $this->config['token']
                    ]);

                    $staticPage->where('id', $this->config['inst_id'])->update([
                        'status' => 'active',
                    ]);

                    Message::send('成功新建了 静态托管。', $this->config['user']);


                } catch (Exception $e) {
                    $staticPage->where('id', $this->config['inst_id'])->delete();
                    Message::send('此时无法新建 静态托管。', $this->config['user']);
                    Http::retry(5, 100)->get("http://{$this->config['address']}/delete", [
                        'id' => $this->config['inst_id'],
                        'token' => $this->config['token']
                    ]);
                }

                // dispatch(new SendEmailJob($email, "久等了，您的 静态托管 已经准备好了。"))->onQueue('mail');

                break;

            case 'delete':
                Http::retry(5, 100)->get("http://{$this->config['address']}/delete", [
                    'id' => $this->config['inst_id'],
                    'token' => $this->config['token']
                ]);

                break;

            case 'passwd':
                try {
                    $result = Http::retry(5, 100)->get("http://{$this->config['address']}/passwd", [
                        'inst_id' => $this->config['inst)id'],
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
        }
    }
}
