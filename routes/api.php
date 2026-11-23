<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopPayController;
use App\Http\Controllers\TelegramController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::match(['get', 'post'], 'shoppay-webhook', [ShopPayController::class, 'webhook'])->name('shoppay.webhook');

Route::post('telegram-webhook/{secret}', [TelegramController::class, 'webhook'])->name('telegram.webhook');
