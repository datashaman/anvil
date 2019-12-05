<?php

// Register global routes for anvil

Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('anvil');
