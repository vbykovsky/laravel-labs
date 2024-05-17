<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;


//article
Route::resource('/article', ArticleController::class)->middleware('auth:sanctum')->only([
    'index',
    'show',

    'store',
    'update',
    'destroy',
]);

//comment
Route::controller(CommentController::class)->middleware('auth:sanctum')->group(function(){
    Route::get('/comment', 'index');

    Route::post('/comment', 'store');

    Route::put('/comment/{comment}', 'update');
    Route::delete('/comment/{comment}', 'destroy');

    Route::put('/comment/{comment}/accept', 'accept');
    Route::put('/comment/{comment}/reject', 'reject');
});

//Auth
Route::post('/signup', [AuthController::class, 'create']);
Route::post('/signin', [AuthController::class, 'auth']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
