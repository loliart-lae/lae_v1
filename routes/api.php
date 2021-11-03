<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api;
use App\Http\Controllers\api\v1;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/v1/_field/{id}', [v1\UserFieldController::class, 'show']);

Route::prefix('/')->middleware(['auth:api'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/_servers', [api\ServerController::class, 'index']);
    Route::get('/_projects', [api\ProjectController::class, 'index']);
    Route::resource('/_status', api\StatusController::class);


    Route::resource('/v1/_tunnels', v1\TunnelController::class);

    Route::resource('/v1/_projects', v1\TunnelController::class);
    Route::resource('/v1/_fastVisits', v1\FastVisitController::class);
    Route::resource('/v1/_staticPage', v1\StaticPageController::class);
    Route::resource('/v1/_field', v1\UserFieldController::class);
});

Route::post('/serverMonitor/put', [Controllers\ServerMonitorController::class, 'save_data'])->name('serverMonitor.save_data');
