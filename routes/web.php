<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

Auth::routes(['register' => false]);

Route::get('routes', function () {
    Artisan::call('route:list');

    return '<pre>' . Artisan::output() . '</pre>';
});
Route::group(['namespace' => 'Front', 'middleware' => 'restrictIp', 'as' => 'front.'], function () {

    // Homepage
    Route::get('/', 'IndexController@index')->name('index');

    // Kontakt
    Route::get('/kontakt', 'ContactController@index')->name('contact');
    Route::post('/kontakt', 'ContactController@send')->name('contact.send');
    Route::post('/kontakt/{property}', 'ContactController@property')->name('contact.property');

    // Galeria
    Route::get('/galeria', 'Gallery\IndexController@index')->name('gallery.index');
    Route::get('/galeria/{gallery},{gallerySlug}', 'Gallery\IndexController@show')->name('gallery.show');

    // O firmie
    Route::get('/inwestor', 'About\IndexController@index')->name('investor');
    Route::get('/o-inwestycji', 'About\IndexController@investment')->name('investment');

    // DeveloPro
    Route::group(['namespace' => 'Developro', 'prefix' => '/mieszkania', 'as' => 'developro.'], function () {
        Route::get('/', 'InvestmentController@show')->name('show');
        Route::get('/pietro/{floor},{floorSlug}', 'InvestmentFloorController@index')->name('floor');
        Route::get('/pietro/{floor},{floorSlug}/m/{property},{propertySlug}', 'InvestmentPropertyController@index')->name('property');
    });

    //Historia cen
    Route::get('/historia/{property}', 'History\IndexController@show')->name('history');
    Route::get('/przynalezne/{property}', 'History\IndexController@others')->name('others');
    Route::get('/przynalezne/{property}/show', 'History\IndexController@other')->name('others.show');
    Route::get('/przynalezne/{property}/table', 'History\IndexController@otherTable')->name('others.table');

    // Inline
    Route::group(['prefix' => '/inline', 'as' => 'front.inline.'], function () {
        Route::get('/', 'InlineController@index')->name('index');
        Route::get('/loadinline/{inline}', 'InlineController@show')->name('show');
        Route::post('/update/{inline}', 'InlineController@update')->name('update');
    });
});
