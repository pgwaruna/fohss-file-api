<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiController;
use App\Http\Controllers\File\ApiPermissionController;
use App\Http\Controllers\File\ChannelsController;
use App\Http\Controllers\File\FileTypeController;
use App\Http\Controllers\File\FileController;
use App\Http\Controllers\Common\SettingController;
use App\Http\Middleware\AuthenticateAccess;
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

// Public Routes


// Authorized routes
Route::group(['middleware' => 'apitoken'], function () {
    //Auth account
    Route::get('/account/viewall', [ApiController::class, 'viewAllApi']);
    Route::post('/account/create', [ApiController::class, 'create']);
    Route::post('/account/{id}', [ApiController::class, 'update']);
    Route::delete('/account/{id}', [ApiController::class, 'delete']);

    //Auth account
    Route::get('/apipermission/viewall', [ApiPermissionController::class, 'getAllApiPermissionAssigns']);
    Route::get('/apipermission/byid/{id}', [ApiPermissionController::class, 'getApiPermissionAssign']);
    Route::get('/apipermission/byapi/{id}', [ApiPermissionController::class, 'getApiPermissionAssignByApiId']);
    Route::post('/apipermission/assign', [ApiPermissionController::class, 'create']);
    Route::delete('/apipermission/unassign/{id}', [ApiPermissionController::class, 'delete']);

    //Channels
    Route::get('/channels/viewall', [ChannelsController::class, 'getChannels']);
    Route::get('/channels/byid/{id}', [ChannelsController::class, 'getChannel']);
    Route::post('/channels/create', [ChannelsController::class, 'create']);
    Route::post('/channels/{id}', [ChannelsController::class, 'update']);
    Route::delete('/channels/{id}', [ChannelsController::class, 'delete']);

    //File Types
    Route::get('/mimetype/viewall', [FileTypeController::class, 'getFileTypes']);
    Route::get('/mimetype/byid/{id}', [FileTypeController::class, 'getFileType']);
    Route::post('/mimetype/create', [FileTypeController::class, 'create']);
    Route::post('/mimetype/{id}', [FileTypeController::class, 'update']);
    Route::delete('/mimetype/{id}', [FileTypeController::class, 'delete']);

    //File
    Route::post('/file/create', [FileController::class, 'createFile']);
    Route::post('/file/view', [FileController::class, 'getFile']);
    Route::delete('/file/delete', [FileController::class, 'deleteFile']);

    //Settings
    Route::get('/setting/viewall', [SettingController::class, 'viewAll']);
    Route::get('/setting/bykey/{setting_key}', [SettingController::class, 'getSetting']);
    Route::post('/setting/create', [SettingController::class, 'create']);
    Route::post('/setting/{id}', [SettingController::class, 'update']);
    Route::delete('/setting/{id}', [SettingController::class, 'delete']);
    });


