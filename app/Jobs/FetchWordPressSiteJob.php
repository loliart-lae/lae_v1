<?php

namespace App\Jobs;

use App\Http\Controllers\WordPressFetchController;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class FetchWordPressSiteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lock = Cache::lock('lae_user_wp_fetch_lock');

        if ($lock->get()) {
            $data = User::where('wp_index', 1)->cursor();
            foreach ($data as $user) {
                WordPressFetchController::fetch($user->id, $user->website);
            }

            $lock->release();
            return true;
        } else {
            return false;
        }
    }
}
