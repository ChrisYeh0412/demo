<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::group(['namespace' => 'Auth'], function() {
	Route::post('login', 'LoginController@loginAction')->name('loginAction');
	Route::get('logout', 'LoginController@logout')->name('logout');
    Route::get('/', 'LoginController@login')->name('login');
    Route::get('login', 'LoginController@login')->name('login');
    Route::get('register', 'RegisterController@index')->name('register');
    Route::post('registerAction', 'RegisterController@registerAction')->name('registerAction');
    Route::post('facebookRegisterAction', 'RegisterController@facebookRegisterAction')->name('facebookRegisterAction');
});


Route::group(['middleware' => ['auth:web']], function(){
    Route::get('home', 'HomeController@index')->name('home');
});

Route::get('privacy', 'HomeController@privacy')->name('privacy');
