<?php

namespace App\Console;

use App\Jobs\CostJob;
use App\Jobs\CalcServerJob;
use App\Jobs\StaticPageJob;
use App\Jobs\ServerStatusJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
            // 分钟计费
            dispatch(new CostJob())->onQueue('cost');
            // 获取服务器资源
            dispatch(new ServerStatusJob())->onQueue('remote_desktop');
        })->everyMinute();

        $schedule->call(function () {
            // 重新计算服务器配额
            dispatch(new CalcServerJob())->onQueue('cost');
        })->everyTenMinutes();

        $schedule->call(function () {
            // 计算 静态托管 空间占用
            dispatch(new StaticPageJob(['method' => 'count']));

        })->everyFiveMinutes();
        // 生成 metrics
        $schedule->command('horizon:snapshot')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
