<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1;
use App\Http\Controllers\API\V1\web;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


#Register start
Route::post('register', [\App\Http\Controllers\API\V1\AuthController::class, 'register']);
Route::post('activate-user', [\App\Http\Controllers\API\V1\AuthController::class, 'activateUser']);
Route::post('login', [\App\Http\Controllers\API\V1\AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('revoke-password', [\App\Http\Controllers\API\V1\AuthController::class, 'revokePassword']);
    Route::post('reset-password-verify', [\App\Http\Controllers\API\V1\AuthController::class, 'resetPasswordVerify']);
    Route::post('logout', [\App\Http\Controllers\API\V1\AuthController::class, 'logout']);
    Route::post('change-password', [\App\Http\Controllers\API\V1\AuthController::class, 'changePassword']);

    Route::post('/send-message', [web\ChatController::class, 'sendMessage']);
    Route::post('/read-message', [web\ChatController::class, 'readMessage']);
    Route::post('/send-message/{group_username}', [web\ChatController::class, 'sendMessageGroup']);

    Route::get('/get-chat-messages/{user_id}/{paginate}', [web\ChatController::class, 'getMessages']);
    Route::get('/get-chat-users',  [web\ChatController::class, 'getUser']);

});
