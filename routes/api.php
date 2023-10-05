<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CommentController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth:sanctum'])->group(function(){
    
    Route::get('/logout', [AuthenticationController::class, 'logout']);

    Route::get('/user_login', [AuthenticationController::class, 'userLogin']);

    Route::post('/posts',[PostController::class, 'store']);
    Route::put('/posts/{id}',[PostController::class, 'update'])->middleware('isLogin');
    Route::delete('/posts/{id}',[PostController::class, 'destroy'])->middleware('isLogin');

    Route::post('/comments', [CommentController::class,'store'] );
    Route::put('/comments/{id}', [CommentController::class,'update'] )->middleware('comentator');

    Route::delete('/comments/{id}', [CommentController::class,'destroy'] )->middleware('comentator');

});

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::post('/login', [AuthenticationController::class, 'login']);




