<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;
use hanbz\PassportClient\Facades\PassportClient;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('oauth/login', fn () => PassportClient::driver('passport')->redirect())->name('login');
Route::get('oauth/callback', [Controllers\AuthController::class, 'OAuthCallback']);
Route::post('oauth/logout', [Controllers\AuthController::class, 'logout'])->name('logout');

Route::get('/doing', function () {
    return view('doing');
})->name('doing');


Route::prefix('/')->group(function () {
    Route::get('/', function () {
        return view('index');
    })->name('index');

    Route::get('why', function () {
        return view('why');
    })->name('why');

    Route::get('about_us', function () {
        return view('about_us');
    })->name('about_us');


});

Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('main');
    })->name('main');


    Route::get('/billing/return', [Controllers\UserBalanceController::class, 'return']);
    Route::get('/billing/thankyou', [Controllers\UserBalanceController::class, 'thankyou'])->name('billing.thankyou');
    Route::resource('/billing', Controllers\UserBalanceController::class);

    Route::get('invites', [Controllers\ProjectInviteController::class, 'invites'])->name('invites.list');
    Route::post('invites/{id}/accept', [Controllers\ProjectInviteController::class, 'accept'])->name('invites.accept');
    Route::post('invites/{id}/deny', [Controllers\ProjectInviteController::class, 'deny'])->name('invites.deny');


    Route::resource('/projects', Controllers\ProjectController::class);
    Route::post('/projects/{project_id}/leave', [Controllers\ProjectController::class, 'leave'])->name('projects.leave');
    Route::post('/projects/{project_id}/charge', [Controllers\ProjectController::class, 'charge'])->name('projects.charge');
    Route::resource('/projects/{project_id}/invite', Controllers\ProjectInviteController::class);
    Route::resource('/lxd', Controllers\AppEngineController::class);
    Route::get('/lxd/{project_id}/create', [Controllers\AppEngineController::class, 'create_in_project'])->name('lxd.create_in_project');
    Route::resource('/lxd/{lxd_id}/forward', Controllers\ForwardController::class);

    Route::resource('/remote_desktop', Controllers\RemoteDesktopController::class);

    Route::resource('/projects/{project_id}/members', Controllers\ProjectMembersController::class);

});

Route::get('/billing/notify', [Controllers\UserBalanceController::class, 'notify']);


Route::prefix('admin')->middleware('can:enter-admin')->group(function () {
    Route::get('/', function () {
        return 'admin.index';
    })->name('admin.index');

    Route::resource('/balance', Controllers\Admin\BalanceController::class);
    Route::get('/balance/user/search', [Controllers\Admin\BalanceController::class, 'find'])->name('admin.balance.user.find');
});

