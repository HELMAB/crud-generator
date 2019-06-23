<?php

Route::group(['prefix', 'Comment'], function () {
    Route::get('', 'commentsController@index')->name('Comment.index');
    Route::get('store', 'commentsController@stroe')->name('Comment.store');
    Route::get('show', 'commentsController@show')->name('Comment.show');
    Route::get('delete', 'commentsController@delete')->name('Comment.delete');
});
