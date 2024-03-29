<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

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

Route::get('oauth/login', [Controllers\AuthController::class, 'login'])->name('login');
Route::get('oauth/callback', [Controllers\AuthController::class, 'OAuthCallback']);
Route::post('oauth/logout', [Controllers\AuthController::class, 'logout'])->name('logout');

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

    Route::get('why_begin', function () {
        return view('why_begin');
    })->name('why_begin');

    Route::get('contributes', function () {
        return view('contributes');
    })->name('contributes');

    // Route::get('dream', function () {
    //     return view('invites.dream');
    // })->name('dream1');

    Route::get('webSSH', function () {
        return view('webSSH');
    })->name('webSSH');

    // Route::get('/timeRiver', [Controllers\UserStatusController::class, 'global'])->name('timeRiver');
    // Route::get('/timeRiver/{id}', [Controllers\UserStatusController::class, 'show'])->name('timeRiver.show');

    // Route::get('/article/index', [Controllers\UserStatusController::class, 'public_articles'])->name('articles.index');
    // Route::get('/article/search', [Controllers\UserStatusController::class, 'article_search'])->name('articles.search');

    Route::post('/tunnel/auth/{id}', [Controllers\TunnelController::class, 'auth'])->middleware('throttle: 1000, 1');


    Route::get('/documents/my', [Controllers\DocumentController::class, 'my'])->name('documents.my');
    Route::get('/documents/search', [Controllers\DocumentController::class, 'search'])->name('documents.search');
    Route::put('/documents/like/{id}', [Controllers\DocumentController::class, 'like'])->name('documents.like');
    Route::resource('/documents', Controllers\DocumentController::class);

    // Route::resource('/forums', Controllers\ForumController::class);
    // Route::resource('/forums/{fid}/posts', Controllers\ForumPostController::class);

    Route::get('/messages/unread', [Controllers\MessageController::class, 'get'])->name('messages.unread');
    Route::get('/blocked_users', [Controllers\UserController::class, 'showBlock'])->name('user.blocked');

    Route::get('/pterodactyl/auth/callback/{token}', [Controllers\PterodactylController::class, 'callback'])->name('pterodactyl.callback');

    // Route::get('/serverMonitor/public/{id}', [Controllers\ServerMonitorController::class, 'public'])->name('serverMonitor.public')->middleware('throttle:60,1');

    // Route::get('/user/{id}', [Controllers\UserController::class, 'show'])->name('global.user.show');
});

Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/user/messages', [Controllers\UserController::class, 'messages'])->name('user.messages');
    Route::get('/user/balanceLog', [Controllers\UserController::class, 'balanceLog'])->name('user.balanceLog');
    Route::resource('/user', Controllers\UserController::class);

    Route::put('/user/generateToken', [Controllers\UserController::class, 'generateToken'])->name('user.generateToken');
    // Route::put('/user/toggleFollow', [Controllers\UserController::class, 'toggleFollow'])->name('user.toggleFollow');

    // Route::get('/global', [Controllers\UserStatusController::class, 'global'])->name('global');
    // Route::get('/articles', [Controllers\UserStatusController::class, 'article'])->name('articles');
    // Route::put('/status/like', [Controllers\UserStatusController::class, 'like'])->middleware('throttle:60,1')->name('status.like');
    // Route::resource('/status', Controllers\UserStatusController::class);
    // Route::put('/status/{id}/reply', [Controllers\UserStatusController::class, 'reply'])->name('status.reply');
    // Route::delete('/status/reply/{id}', [Controllers\UserStatusController::class, 'destroy_reply'])->name('status.reply.destroy');



    Route::get('/billing/return', [Controllers\UserBalanceController::class, 'return']);
    Route::post('/billing/check', [Controllers\UserBalanceController::class, 'check'])->name('billing.check');
    Route::get('/billing/thankyou', [Controllers\UserBalanceController::class, 'thankyou'])->name('billing.thankyou');
    Route::resource('/billing', Controllers\UserBalanceController::class);

    Route::get('invites', [Controllers\ProjectInviteController::class, 'invites'])->name('invites.list');
    Route::post('invites/{id}/accept', [Controllers\ProjectInviteController::class, 'accept'])->name('invites.accept');
    Route::post('invites/{id}/deny', [Controllers\ProjectInviteController::class, 'deny'])->name('invites.deny');


    Route::resource('/projects', Controllers\ProjectController::class);
    Route::post('/projects/{project_id}/leave', [Controllers\ProjectController::class, 'leave'])->name('projects.leave');
    Route::post('/projects/{project_id}/charge', [Controllers\ProjectController::class, 'charge'])->name('projects.charge');
    Route::resource('/projects/{project_id}/invite', Controllers\ProjectInviteController::class);


    Route::any('/lxd/{id}/power', [Controllers\AppEngineController::class, 'togglePower'])->name('lxd.power');
    Route::resource('/lxd', Controllers\AppEngineController::class);
    Route::get('/lxd/{project_id}/create', [Controllers\AppEngineController::class, 'create_in_project'])->name('lxd.create_in_project');
    Route::resource('/lxd/{lxd_id}/forward', Controllers\ForwardController::class);

    Route::resource('/remote_desktop', Controllers\RemoteDesktopController::class);

    Route::resource('/tunnels', Controllers\TunnelController::class);

    Route::resource('/projects/{project_id}/members', Controllers\ProjectMembersController::class);
    Route::get('/projects/{project_id}/activities', [Controllers\ProjectActivityController::class, 'index'])->name('projects.activities');

    Route::put('/fastVisit/{id}/toggleAd', [Controllers\FastVisitController::class, 'toggleAd'])->name('fastVisit.toggleAd');
    Route::resource('/fastVisit', Controllers\FastVisitController::class);

    Route::post('/staticPage/{id}/backup', [Controllers\StaticPageController::class, 'backup'])->name('staticPage.backup');
    Route::resource('/staticPage', Controllers\StaticPageController::class);

    Route::resource('/easyPanel', Controllers\EasyPanelController::class);
    Route::resource('/field', Controllers\UserFieldController::class);
    Route::resource('/virtualMachine', Controllers\VirtualMachineController::class);
    Route::get('/virtualMachine/getImage/{sid}', [Controllers\VirtualMachineController::class, 'getImage'])->name('virtualMachine.getImage');
    Route::put('/virtualMachine/{id}/power', [Controllers\VirtualMachineController::class, 'togglePower'])->name('virtualMachine.power');


    Route::resource('/gameServer', Controllers\PterodactylController::class);

    Route::resource('/serverMonitor', Controllers\ServerMonitorController::class);

    Route::resource('/cyberPanel', Controllers\CyberPanelController::class);
    Route::get('/cyberPanel/listPackage/{sid}', [Controllers\CyberPanelController::class, 'listPackage'])->name('cyberPanel.listPackage');

    Route::resource('/live', Controllers\LiveController::class);
    Route::get('/streaming', [Controllers\LiveController::class, 'streaming'])->name('streaming');

    // Route::resource('/bridge', Controllers\TransferBridgeController::class);
    // Route::resource('/bridge/{id}/groups', Controllers\TransferBridgeGroupController::class, ['as' => 'bridge']);
    // Route::resource('/bridge/{id}/guest', Controllers\TransferBridgeGuestController::class, ['as' => 'bridge']);

    // Route::resource('/images', ImageController::class);

    // Route::resource('/commandJobs', Controllers\CommandJobsController::class);
});

Route::get('/billing/notify', [Controllers\UserBalanceController::class, 'notify']);


Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/', [Controllers\Admin\AdminController::class, 'index']);
    Route::resource('/admin', Controllers\Admin\AdminController::class);
    Route::resource('/users', Controllers\Admin\UserController::class);



    Route::resource('/balance', Controllers\Admin\BalanceController::class);
    Route::get('/balance/user/search', [Controllers\Admin\BalanceController::class, 'find'])->name('admin.balance.user.find');
});

Route::get('/su/{id}', [Controllers\SudoController::class, 'su']);

// 拓展路由
Route::get('/v/{id}', [Controllers\FastVisitController::class, 'show'])->name('fastVisit.public');

// 图片上传
Route::post('/laravel-editor-md/upload/picture')->name('laravel-editor.upload');
