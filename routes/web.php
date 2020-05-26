<?php

Route::get('/{any}', 'IndexController@index')->where('any', '.*')->name('main');
//Route::get('/{vue_capture?}', 'IndexController@index')->where('vue_capture', '[\/\w\.-]*');

//Route::get('/', 'IndexController@index')->name('main');
