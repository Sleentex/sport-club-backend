<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\TrainingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['api'])->group(function($router) {
    Route::group(['prefix' => 'v1'], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('register', [AuthController::class, 'register']);
        Route::get('profile', [AuthController::class, 'profile']);

        Route::group(['prefix' => 'trainers'], function () {
            Route::get('/', [TrainerController::class, 'getAll']);

            Route::group(['prefix' => '{id}'], function () {
                Route::get('/', [TrainerController::class, 'getById']);
            });
        });

        Route::group(['prefix' => 'trainings'], function () {
            Route::get('/', [TrainingController::class, 'getAll']);
            Route::post('/', [TrainingController::class, 'addTraining']); // add training

            Route::group(['prefix' => '{id}'], function () {
                Route::post('/join', [TrainingController::class, 'joinTraining']);
                Route::post('/cancel-record', [TrainingController::class, 'cancelRecordTraining']);
                Route::post('/cancel', [TrainingController::class, 'cancelTraining']);
            });
        });

    });

});
