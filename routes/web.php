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
Route::get('index','MemberController@index');

Route::domain('www.elm.com')->group(function () {
    //商家列表
    Route::get('shop/list','ShopController@index');
    //指定商家
    Route::get('shop/seller','ShopController@seller');

    //用户注册
    Route::post('member/create','MemberController@create');
    //短信验证码
    Route::get('sms','SmsController@send');
    //用户登录
    Route::post('login','MemberController@login');
    //修改密码
    Route::post('member/changePassword','MemberController@changePassword');
    //忘记密码
    Route::post('member/forgetPassword','MemberController@forgetPassword');

    //保存添加地址
    Route::post('address/create','AddressController@create');
    //地址列表
    Route::get('address/list','AddressController@index');
    //获取指定地址
    Route::get('address/edit','AddressController@edit');
    //保存修改地址
    Route::post('address/update','AddressController@update');

    //保存购物车
    Route::post('cart/create','CartController@create');
    //获取购物车数据
    Route::get('cart/show','CartController@show');

    //添加订单
    Route::post('order/create','OrderController@create');
    //获得指定订单
    Route::get('order/order','OrderController@order');
    //订单列表
    Route::get('order/orderList','OrderController@orderList');
});

