<?php

use App\Http\Controllers\Api\ApiController;

Route::post('/feedback', [ApiController::class, 'feedback'])->name('api.feedback');

Route::post('login', [\App\Http\Controllers\Api\Auth\LoginController::class, 'login'])->name('api.login');

Route::middleware('auth:api')->group(function () {
    Route::post('/log', [ApiController::class, 'log'])->name('exceptions.log');
});
