<?php

namespace App\Jobs;

use Exception;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Http\Controllers\WordPressFetchController;

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
        $cache = Cache::get('lae_user_wp_fetch', true);
        if ($cache) {
            $data = User::where('wp_index', 1)->cursor();
            foreach ($data as $user) {
                try {
                    WordPressFetchController::fetch($user->id, $user->website, true);
                } catch (Exception $e) {
                    unset($e);
                    continue;
                }
            }

            Cache::put('lae_user_wp_fetch', 0, 60);
            return true;
        } else {
            return false;
        }
    }
}