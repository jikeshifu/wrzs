<?php

declare (strict_types=1);

namespace app\command;

use app\api\model\order\OrderRoom;
use app\model\order\Order;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;

class JoinWallet extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('joinWallet')
            ->setDescription('the joinWallet command');
    }

    protected function execute(Input $input, Output $output)
    {

        $join_wallet = Db::name("join_wallet")->where(["deleted_at" => 0])->select();
        //获取前一天开始时间
        $start_time = strtotime(date("Ymd")) - 86400;
        //获取前一天开始时间
        $end_time = $start_time + 86399;
        $tody = date("Ymd", $start_time);

        foreach ($join_wallet as $vo) {
            if ($vo["profit_time"] != $tody) {

                $price = Order::where("finish_time", "between", [$start_time, $end_time])
                    ->where([
                        "order_status" => 3,
                        "join_id" => $vo["join_id"],
                        "pay_type"=>"wechat"
                    ])->sum("order_profit");


//                //更新结算状态
//                Order::where("created_at", "between", [$start_time, $end_time])
//                    ->where([
//                        "order_status" => 3,
//                        "join_id" => $vo["join_id"],
//                        "pay_type"=>"wechat"
//                    ])->save(["js_status"=>1]);
                if ($price > 0) {
                    Db::name("join_wallet")
                        ->where(["join_id" => $vo["join_id"]])
                        ->update([
                         "money" => $vo["money"] + $price,
                         "money_total" => $vo["money_total"] + $price,
                         "profit_time"=>$tody
                    ]);
                }
            }

        }
        // 指令输出
        $output->writeln('joinWallet');
    }

}
