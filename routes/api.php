<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/user/store/avatar', 'UserAvatarController@store');
Route::get('/user/get/avatar/{user_id}/{size?}', 'UserAvatarController@get');


Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');
    Route::delete('settings/destroy', 'Settings\PasswordController@destroy');
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::post('oauth/{provider}', 'Auth\OAuthController@redirectToProvider');
    Route::get('oauth/{provider}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
});

//    Tour category
Route::group(['prefix'=>'tour/category'], function(){
    Route::put('/store','TourCategoryController@store');
    Route::put('/edit','TourCategoryController@edit');
    Route::delete('/trash','TourCategoryController@destroy');
    Route::post('/show','TourCategoryController@show');
    Route::post('/restore','TourCategoryController@restoreTrashed');
    Route::get('/','TourCategoryController@index');
    Route::get('/trashed','TourCategoryController@trashed');
    Route::delete('/remove','TourCategoryController@destroyForever');
});
//    Tours
Route::group(['prefix'=>'tour'], function(){
    Route::put('/store','TourCategoryController@store');
    Route::put('/edit','TourCategoryController@edit');
    Route::delete('/delete','TourCategoryController@destroy');
    Route::post('/show','TourCategoryController@show');
    Route::post('/trashed','TourCategoryController@trashed');
    Route::get('/','TourCategoryController@index');
    Route::get('/list','TourCategoryController@listOfCategories');
});
//    Marker Category
Route::group(['prefix'=>'marker/category'], function(){
    Route::put('/store','MarkerCategoryController@store');
    Route::put('/edit','MarkerCategoryController@edit');
    Route::delete('/trash','MarkerCategoryController@destroy');
    Route::post('/show','MarkerCategoryController@show');
    Route::post('/restore','MarkerCategoryController@restoreTrashed');
    Route::get('/','MarkerCategoryController@index');
    Route::get('/trashed','MarkerCategoryController@trashed');
    Route::delete('/remove','MarkerCategoryController@destroyForever');

});
//    Marker
Route::group(['prefix'=>'marker'], function(){
    Route::put('/store','MarkerController@store');
    Route::put('/edit','MarkerController@edit');
    Route::delete('/trash','MarkerController@destroy');
    Route::get('/show/{slug}','MarkerController@show');
    Route::post('/restore','MarkerController@restoreTrashed');
    Route::get('/','MarkerController@index');
    Route::get('/trashed','MarkerController@trashed');
    Route::delete('/remove','MarkerController@destroyForever');
});

Route::group(['prefix'=>'favourite'], function(){
    Route::put('/add/marker','FavouritesController@createMarker');
    Route::put('/add/tour','FavouritesController@createTour');
    Route::get('/tours','FavouritesController@tours');
    Route::get('/markers','FavouritesController@arkers');
    Route::delete('/remove/marker','FavouritesController@deleteMarker');
    Route::delete('/remove/tour','FavouritesController@deleteTour');
});

