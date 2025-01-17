<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::group('/', function () {

//    Route::any('store.Store/list', 'store.Store/list');
    Route::any('order.Room/placeOrder', 'order.Room/placeOrder');
    Route::any('api/user.Order/info', 'api/user.Order/info');
    Route::any('watch.user/mobile', 'watch.user/mobile');
    Route::any('watch.user/edit', 'watch.user/edit');
    Route::any('user.Room/list', 'user.Room/list');
    Route::any('user.Balance/info', 'user.Balance/info');
    Route::any('store.Store/nearby', 'store.Store/nearby');
    Route::any('user.Balance/info', 'user.Balance/info');
    Route::any('shop.ShoppingCar/list', 'shop.ShoppingCar/list');



})->middleware([ \app\middleware\JwtAuth::class]);

