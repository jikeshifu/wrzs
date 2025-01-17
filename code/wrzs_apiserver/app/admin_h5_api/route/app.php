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

    Route::any('store.Store/list', 'store.Store/list'); //门店列表
    Route::any('store.Store/editStatus', 'store.Store/editStatus');  //门店状态修改
    Route::any('store.Room/editStatus', 'store.Room/editStatus');  //房间状态修改
    Route::any('store.Room/openDevice', 'store.Room/openDevice');  //控制设备
    Route::any('order.Room/list', 'order.Room/list');  //房间订单
    Route::any('order.Room/cancel', 'order.Room/cancel'); //取消订单
    Route::any('order.Room/returnDeposit', 'order.Room/returnDeposit'); //押金退还

})->middleware([\app\middleware\JwtAuth::class]);

