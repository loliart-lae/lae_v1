<?php

namespace App\Jobs;

use Exception;
use App\Models\Message;
use App\Models\RemoteDesktop;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RemoteDesktopJob implements ShouldQueue
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
        $remote_desktop = new RemoteDesktop();

        switch ($this->config['method']) {
            case 'create':
                try {
                    $result = Http::retry(5, 5)->get("http://{$this->config['address']}/create", [
                        'username' => $this->config['username'],
                        'password' => $this->config['password'],
                        'token' => $this->config['token']
                    ]);

                    $remote_desktop->where('id', $this->config['inst_id'])->update([
                        'status' => 'active',
                    ]);
                    Message::send('成功新建了 共享的 Windows 远程桌面。', $this->config['user']);
                } catch (Exception $e) {
                    $remote_desktop->where('id', $this->config['inst_id'])->delete();
                    Message::send('此时无法新建 共享的 Windows 远程桌面。', $this->config['user']);
                    Http::retry(5, 5)->get("http://{$this->config['address']}/delete", [
                        'username' => $this->config['username'],
                        'token' => $this->config['token']
                    ]);
                }

                // dispatch(new SendEmailJob($email, "久等了，您的 共享的 Windows 远程桌面 已经准备好了。"))->onQueue('mail');

                break;

            case 'delete':
                Http::retry(5, 5)->get("http://{$this->config['address']}/remove", [
                    'username' => $this->config['username'],
                    'token' => $this->config['token']
                ]);

                Http::retry(5, 5)->get("http://{$this->config['address']}/logout", [
                    'username' => $this->config['username'],
                    'token' => $this->config['token']
                ]);

                Http::retry(5, 5)->get("http://{$this->config['address']}/delete", [
                    'username' => $this->config['username'],
                    'token' => $this->config['token']
                ]);

                break;

            case 'passwd':
                try {
                    Http::retry(5, 5)->get("http://{$this->config['address']}/logout", [
                        'username' => $this->config['username'],
                        'token' => $this->config['token']
                    ]);

                    Http::retry(5, 5)->get("http://{$this->config['address']}/passwd", [
                        'username' => $this->config['username'],
                        'password' => $this->config['password'],
                        'token' => $this->config['token']
                    ]);

                    Message::send('你的 共享的 Windows 远程桌面 的新密码已经启用。', $this->config['user']);

                    // if ($result['status']) {
                    //     Message::send('你的 共享的 Windows 远程桌面 的新密码已经启用。', $this->config['user']);
                    // } else {
                    //     Message::send('此时无法更改密码。', $this->config['user']);
                    // }
                } catch (Exception $e) {
                    Message::send('此时无法更改 共享的 Windows 远程桌面 的密码。', $this->config['user']);
                }
                break;
        }
    }
}
