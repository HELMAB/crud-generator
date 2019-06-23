<?php

Route::group(['prefix', 'Post'], function () {
    Route::get('', 'postsController@index')->name('Post.index');
    Route::get('store', 'postsController@stroe')->name('Post.store');
    Route::get('show', 'postsController@show')->name('Post.show');
    Route::get('delete', 'postsController@delete')->name('Post.delete');
});
