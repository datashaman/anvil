<?php

// Artisan Commands entries...
Route::post('/anvil-api/commands', 'CommandsController@index');
Route::get('/anvil-api/commands/{command}', 'CommandsController@show');

Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('anvil');
