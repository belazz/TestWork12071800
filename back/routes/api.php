<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
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

Route::prefix('/movies')->group(function() {
    Route::get('/{movie}', [MovieController::class, 'get']);
    Route::get('/', [MovieController::class, 'movies']);
    Route::post('/upsert/{movie?}', [MovieController::class, 'upsert']);
    Route::delete('/{movie}', [MovieController::class, 'delete']);
});
