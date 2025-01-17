<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'xdOrder' => 'app\command\XdOrder',
        'roomStart' => 'app\command\RoomStart',
        'roomEnd' => 'app\command\RoomEnd',
        'roomEndSms' => 'app\command\RoomEndSms',
        'qrList' => 'app\command\QrList',
        'qrListXf' => 'app\command\QrListXf',
        'wmjCard' => 'app\command\WmjCard',
        'userIni' => 'app\command\UserIni',
        'orderClose' => 'app\command\OrderClose',
        'cabinet' => 'app\command\Cabinet',
        'givesCoupons' => 'app\command\GivesCoupons',
        'joinWallet' => 'app\command\JoinWallet',
    ],
];
