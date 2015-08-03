<?php

Route::get('/', function() {
    return View::make('index');
});

Route::get('backend/', function() {
    return View::make('admin.index');
});

Route::group(array('prefix' => 'api'), function()
{

    Route::resource('products', 'ProductController', array('only' => array('index', 'destroy')));
    Route::post('admin/auth', 'BackendController@login');
    Route::get('admin/logout', 'BackendController@logout');
});


// 404 error pages
/*App::missing(function($exception) {
    return View::make('index');
});*/