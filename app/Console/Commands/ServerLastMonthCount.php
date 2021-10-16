<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ServerBalanceCountController;

class ServerLastMonthCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:lm {--sid=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '显示上个月的流动资金。';

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
        $this->info('正在统计数据，可能会比较慢。');
        $balance = new ServerBalanceCountController();
        $data = $balance->lastMonth($this->option('sid'));
        $this->info('上个月，一共有 ' . $data['counts'] . ' 比交易，共产生了 ' . $data['balance'] . ' 比积分流入，大约 ' . number_format($data['balance'] / config('billing.exchange_rate'), 2) . ' 元。');
    }
}
