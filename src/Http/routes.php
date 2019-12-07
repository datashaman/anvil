<?php

Route::post('/anvil-api/commands', 'CommandsController@index');
Route::get('/anvil-api/commands/{command}', 'CommandsController@show');
Route::post('/anvil-api/commands/{command}/runs', 'RunsController@store');
Route::get('/anvil-api/commands/{command}/runs', 'RunsController@index');
Route::get('/anvil-api/commands/{command}/runs/{run}', 'RunsController@show');

Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('anvil');
