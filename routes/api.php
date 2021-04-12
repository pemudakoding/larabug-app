<?php

Route::post('/feedback', 'ApiController@feedback')->name('api.feedback');

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/log', 'ApiController@log')->name('exceptions.log');
});
