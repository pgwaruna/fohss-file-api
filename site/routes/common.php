<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Common\SettingController;
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

// Authorized routes
Route::group(['middleware' => 'auth.jwt'], function () {

    //Global Settings
    Route::get('/settings/viewall', [SettingController::class, 'viewAllSettings']);
    Route::get('/settings/byname/{setting_name}', [SettingController::class, 'getSetting']);
    Route::post('/settings/create', [SettingController::class, 'create']);
    Route::post('/settings/{id}', [SettingController::class, 'update']);
    Route::delete('/settings/{id}', [SettingController::class, 'delete']);
});
