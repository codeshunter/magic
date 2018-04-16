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

//Route::get('/',function () {
//    return view('welcome');
//});

Route::group(['middleware' => ['web'], 'namespace'=>'Admin'], function () {
    //
    Route::get('/cglang','IndexController@cglang');
});

Route::group(['middleware' => ['web', 'lang'], 'prefix' => 'admin', 'namespace'=>'Admin'], function () {
    Route::get('/login','IndexController@login');
    Route::post('/logincon','LoginController@login');
    Route::get('/logout','LoginController@logout');
});


Route::group(['middleware' => ['web', 'lang', 'checklog'], 'prefix' => 'admin', 'namespace'=>'Admin'], function () {
    //
    Route::get('/','IndexController@index');
    Route::get('/addsort','IndexController@addsort');
    Route::get('/delsort','IndexController@delsort');
    Route::get('/editsort','IndexController@editsort');
    Route::post('/editsortcon','IndexController@editsortcon');
    Route::post('/addsortcon','IndexController@addsortcon');

    Route::get('/addprod','IndexController@addprod');
    Route::get('/prodlist','IndexController@prodlist');
    Route::post('/addprodcon','IndexController@addprodcon');
    Route::get('/delprod','IndexController@delprod');

    Route::get('/addindeximg','IndexController@addindeximg');
    Route::post('/addindeximgcon','IndexController@addindeximgcon');
    Route::get('/indeximglist','IndexController@indeximglist');
    Route::get('/delindeximg','IndexController@delindeximg');
});

Route::group(['middleware' => ['web', 'lang'], 'namespace'=>'Index'], function () {
    Route::get('/','IndexController@index');
    Route::get('/getsorts','IndexController@getsorts');
    Route::get('/getallsorts','IndexController@getallsorts');
    Route::get('/getprodsbysort','IndexController@getprodsbysort');
    Route::get('/getprods','IndexController@getprods');
});