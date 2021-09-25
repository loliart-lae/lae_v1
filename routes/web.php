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
sleep(3);
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

    Route::get('/needLogin', function () {
        return view('needLogin');
    })->name('needLogin');

    Route::get('why', function () {
        return view('why');
    })->name('why');

    Route::get('about_us', function () {
        return view('about_us');
    })->name('about_us');

    Route::get('contributes', function () {
        return view('contributes');
    })->name('contributes');

    Route::get('dream', function () {
        return view('invites.dream');
    })->name('dream1');

    Route::get('webSSH', function () {
        return view('webSSH');
    })->name('webSSH');

    Route::get('download/{name}', [Controllers\DriveController::class, 'view'])->name('download.view')->middleware('auth');
    Route::get('download/{name}/download', [Controllers\DriveController::class, 'route_download'])->name('download.download')->middleware('auth');

    Route::post('/tunnel/auth', [Controllers\TunnelController::class, 'auth']);


    Route::get('/documents/my', [Controllers\DocumentController::class, 'my'])->name('documents.my');
    Route::get('/documents/search', [Controllers\DocumentController::class, 'search'])->name('documents.search');
    Route::put('/documents/like/{id}', [Controllers\DocumentController::class, 'like'])->name('documents.like');
    Route::resource('/documents', Controllers\DocumentController::class);

    // Route::resource('/forums', Controllers\ForumController::class);
    // Route::resource('/forums/{fid}/posts', Controllers\ForumPostController::class);

    Route::get('/messages/unread', [Controllers\MessageController::class, 'get'])->name('messages.unread');
});

Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/', [Controllers\UserStatusController::class, 'index'])->name('main');
    Route::get('/global', [Controllers\UserStatusController::class, 'global'])->name('global');
    Route::put('/status/like', [Controllers\UserStatusController::class, 'like'])->name('status.like');
    Route::resource('/status', Controllers\UserStatusController::class);



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


    Route::resource('/projects/{project_id}/storage', Controllers\DriveController::class)->except(['show', 'edit', 'update']);
    Route::get('/projects/{project_id}/storage/files', [Controllers\DriveController::class, 'files'])->name('storage.show');


    Route::resource('/lxd', Controllers\AppEngineController::class);
    Route::get('/lxd/{project_id}/create', [Controllers\AppEngineController::class, 'create_in_project'])->name('lxd.create_in_project');
    Route::resource('/lxd/{lxd_id}/forward', Controllers\ForwardController::class);

    Route::resource('/remote_desktop', Controllers\RemoteDesktopController::class);

    Route::resource('/tunnels', Controllers\TunnelController::class);

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
