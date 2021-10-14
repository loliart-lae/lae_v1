<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api;
use App\Http\Controllers\api\v1;

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

Route::prefix('/')->middleware(['auth:api'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/servers', [api\ServerController::class, 'index']);
    Route::get('/projects', [api\ProjectController::class, 'index']);

    Route::resource('/v1/_tunnels', v1\TunnelController::class);

    Route::resource('/v1/_projects', v1\TunnelController::class);
    Route::resource('/v1/_fastVisits', v1\FastVisitController::class);
    Route::resource('/v1/_staticPage', v1\StaticPageController::class);
});
