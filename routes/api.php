<?php

use App\Http\Controllers\Api\ApiController;

Route::post('/feedback', [ApiController::class, 'feedback'])->name('api.feedback');

Route::post('login', [\App\Http\Controllers\Api\Auth\LoginController::class, 'login'])->name('api.login');

Route::middleware('auth:api')->group(function () {
    Route::post('/log', [ApiController::class, 'log'])->name('exceptions.log');

    Route::get('user', [\App\Http\Controllers\Api\UserController::class, 'show'])->name('api.user.show');

    Route::group(['prefix' => 'projects', 'as' => 'api.projects.'], function(){
        Route::get('/', [\App\Http\Controllers\Api\ProjectController::class, 'index'])->name('index');
        Route::get('{project}', [\App\Http\Controllers\Api\ProjectController::class, 'show'])->name('show');
        Route::get('{project}/exceptions', [\App\Http\Controllers\Api\ProjectController::class, 'exceptions'])->name('exceptions');
        Route::get('{project}/exceptions/{exception}', [\App\Http\Controllers\Api\ProjectController::class, 'exception'])->name('exception');
    });
});
