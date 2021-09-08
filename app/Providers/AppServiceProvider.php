<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // Log::channel('single')->info('------------- Start at ' . date('Y-m-d H:i:s') . '------------');
        // DB::listen(function ($query) {
        //     Log::channel('single')->info(
        //         $query->sql,
        //         $query->bindings,
        //         $query->time
        //     );
        // });
        // Log::channel('single')->info('------------- End at ' . date('Y-m-d H:i:s') . '------------');
    }
}
