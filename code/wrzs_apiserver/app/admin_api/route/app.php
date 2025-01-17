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

    Route::any('/order.Order/list', 'order.Order/list');
    Route::any('/order.Order/cancel', 'order.Order/cancel');
    Route::any('/join.User/list', 'join.User/list');




})->middleware([\app\middleware\JwtAuth::class]);

Route::any('/user.User/login', 'user.User/login');
