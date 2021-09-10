<?php

namespace App\Jobs;

use App\Models\User;
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
        if (isset($this->config['user'])) {
            $email = User::find($this->config['user'])->email;
        }

        switch ($this->config['method']) {
            case 'create':
                Http::retry(5, 100)->get("http://{$this->config['address']}:821/create", [
                    'username' => $this->config['username'],
                    'password' => $this->config['password'],
                    'token' => $this->config['token']
                ]);

                $remote_desktop->where('id', $this->config['inst_id'])->update([
                    'status' => 'active',
                ]);
                dispatch(new SendEmailJob($email, "久等了，您的 共享的 Windows 远程桌面 已经准备好了。"))->onQueue('mail');

                break;

            case 'delete':
                Http::retry(5, 100)->get("http://{$this->config['address']}:821/delete", [
                    'username' => $this->config['username'],
                    'token' => $this->config['token']
                ]);
                break;

            case 'passwd':
                Http::retry(5, 100)->get("http://{$this->config['address']}:821/passwd", [
                    'username' => $this->config['username'],
                    'password' => $this->config['password'],
                    'token' => $this->config['token']
                ]);
                break;
        }
    }
}
