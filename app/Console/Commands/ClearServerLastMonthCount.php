<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ServerBalanceCountController;

class ClearServerLastMonthCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:clear {--sid=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清除上个月的资金流动记录。';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit', '1024M');
        DB::connection()->disableQueryLog();

        $this->info('正在清除数据，可能会比较慢。');
        $balance = new ServerBalanceCountController();
        $result = $balance->clear($this->option('sid'));

        $this->info($result);

        Http::post(config('app.cq_http') . '/send_private_msg', [
            "user_id" => config('app.cq_master'),
            "message" => $result,
            "auto_escape" => "true",
        ]);

        return Command::SUCCESS;
    }
}