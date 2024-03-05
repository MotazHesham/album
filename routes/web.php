<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){return redirect()->route('login');});
Auth::routes(); 

Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    Route::get('/home', 'AlbumsController@index')->name('home'); 
    // Albums
    Route::delete('albums/destroy', 'AlbumsController@massDestroy')->name('albums.massDestroy');
    Route::post('albums/update_pricture_name', 'AlbumsController@update_pricture_name')->name('albums.update_pricture_name');
    Route::post('albums/update_prictures', 'AlbumsController@update_prictures')->name('albums.update_prictures');
    Route::get('albums/remove_picture/{id}', 'AlbumsController@remove_picture')->name('albums.remove_picture');
    Route::post('albums/view_pictures', 'AlbumsController@view_pictures')->name('albums.view_pictures');
    Route::post('albums/media', 'AlbumsController@storeMedia')->name('albums.storeMedia'); 
    Route::resource('albums', 'AlbumsController');

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');
});
