<?php

// Artisan Commands entries...
Route::post('/anvil-api/commands', 'CommandsController@index');
Route::get('/anvil-api/commands/{anvilEntryId}', 'CommandsController@show');

// Scheduled Commands entries...
Route::post('/anvil-api/schedule', 'ScheduleController@index');
Route::get('/anvil-api/schedule/{anvilEntryId}', 'ScheduleController@show');

Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('anvil');
