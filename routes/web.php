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
//登录
Route::any('login',['uses'=>'IndexController@login']);
Route::any('index',['uses'=>'IndexController@index']);
Route::any('start',['uses'=>'IndexController@start']);