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
    //门店管理
    Route::any('store.Store/add', 'store.Store/add');
    Route::any('store.Store/list', 'store.Store/list');
    Route::any('store.Store/edit', 'store.Store/edit');
    Route::any('store.Store/del', 'store.Store/del');
    Route::any('store.Store/status', 'store.Store/status');

    //房间管理
    Route::any('room.Room/add', 'room.Room/add');
    Route::any('room.Room/list', 'room.Room/list');
    Route::any('room.Room/edit', 'room.Room/edit');
    Route::any('room.Room/del', 'room.Room/del');
    Route::any('room.Room/status', 'room.Room/status');
    Route::any('room.Room/sort', 'room.Room/sort');

    //支付
//    Route::any('config.Pay/edit', 'config.Pay/edit');
    Route::any('config.Pay/info', 'config.Pay/info');


})->middleware([ \app\middleware\JwtAuth::class]);

//重置个人密码
Route::any('user.User/token', 'user.User/token');
