<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;


//article
Route::resource('/article', ArticleController::class)->middleware('auth:sanctum')->except(['show']);
Route::get('article/{article}', [ArticleController::class, 'show'])->name('article.show')->middleware('auth:sanctum', 'stats');

//comment
Route::controller(CommentController::class)->group(function(){
    Route::get('/comment', 'index')->middleware('auth:sanctum')->name('comment.index');

    Route::post('/comment', 'store')->middleware('auth:sanctum')->name('comment.store');

    Route::get('/comment/{comment}/edit', 'edit')->middleware('auth:sanctum')->name('comment.edit');
    Route::put('/comment/{comment}', 'update')->middleware('auth:sanctum')->name('comment.update');
    Route::get('/comment/{comment}/delete', 'destroy')->middleware('auth:sanctum')->name('comment.destroy');

    Route::get('/comment/{comment}/accept', 'accept')->middleware('auth:sanctum')->name('comment.approve');
    Route::get('/comment/{comment}/reject', 'reject')->middleware('auth:sanctum')->name('comment.reject');
});

//Auth
Route::get('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/signup', [AuthController::class, 'create'])->name('signup.create');
Route::get('/signin', [AuthController::class, 'signin'])->name('signin');
Route::post('/signin', [AuthController::class, 'auth'])->name('signin.auth');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

//Main
Route::get('/main', [MainController::class, 'index']);
Route::get('/galery/{img}', [MainController::class, 'show']);

Route::get('/', function () {
    return view('layout');
});
