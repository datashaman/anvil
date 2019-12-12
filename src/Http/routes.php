<?php

use Illuminate\Support\Facades\Route;

Route::get('/anvil-api/commands', 'CommandsController@index')->name('commands.index');
Route::get('/anvil-api/commands/{command}', 'CommandsController@show')->name('commands.show');
Route::post('/anvil-api/commands/{command}/runs', 'RunsController@store')->name('runs.store');
Route::get('/anvil-api/commands/{command}/runs', 'RunsController@index')->name('runs.index');
Route::get('/anvil-api/commands/{command}/runs/{run}', 'RunsController@show')->name('runs.show');

Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('anvil');
