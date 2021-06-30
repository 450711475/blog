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

Route::get('/', function () {
    return view('welcome');
});
//登录页面路由
Route::get('admin/login','admin\LoginController@login');
//验证码路由
//Route::get('admin/code','admin\LoginController@code');
Route::get('code/captcha/{tmp}','admin\LoginController@captcha');