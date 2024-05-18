<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::post('/signin', [AuthController::class,'signup']);
Route::controller(AuthController::class)->group(function(){
    Route::post('/signup', 'signup');
    Route::post('/signin', 'signin');
});

Route::middleware([ApiAuthMiddleware::class])->group(function () {
    Route::post('/signout', [AuthController::class,'signout']);
    Route::controller(TodoController::class)->group(function(){
        Route::get('/todos', 'index');
        Route::get('/todos/{id}', 'show');
        Route::post('/todos', 'store');
        Route::put('/todos/{id}', 'update');
        Route::delete('/todos/{id}', 'destroy');
    });
});

