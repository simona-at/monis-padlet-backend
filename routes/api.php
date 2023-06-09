<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PadletController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/', [PadletController::class, 'index']);
Route::get('/padlets', [PadletController::class, 'index']);
Route::get('/padlets/{id}', [PadletController::class, 'findByID']);
Route::get('/padlets/checkID/{id}', [PadletController::class, 'checkID']);

Route::post('/padlets', [PadletController::class, 'save']);


Route::group(['middleware' => ['api', 'auth.jwt']], function() {

    Route::put('/padlets/{id}', [PadletController::class, 'update']);
    Route::delete('/padlets/{id}', [PadletController::class, 'delete']);

    Route::put('/padlets/comments/{id}', [CommentController::class, 'saveComment']);
    Route::put('/padlets/likes/{id}', [LikeController::class, 'saveLike']);
    Route::delete('/padlets/likes/{padlet_id}/{user_id}', [LikeController::class, 'deleteLike']);

    Route::post('auth/logout', [AuthController::class, 'logout']);
});

Route::get('/users', [UserController::class, 'getAllUsers']);

