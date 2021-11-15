<?php

use App\Http\Controllers\ShareVideoHookController;
use App\Http\Controllers\TelegramUpdateController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::post(
    "api/".config('myhero.api-key'),
    ShareVideoHookController::class
);

Route::post(
    config('telegram.bots.myhero.webhook_url'),
    TelegramUpdateController::class
)->middleware();
//['telegram.user', 'telegram.welcome', 'telegram.phone_number']


