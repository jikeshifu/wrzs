<?php
declare (strict_types=1);

namespace app\command;

use app\common\cache\Redis;

use think\console\Command;
use think\console\Input;

use think\console\Output;
use think\facade\Db;


class GivesCoupons extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('givesCoupons')
            ->setDescription('the givesCoupons command');
    }

    protected function execute(Input $input, Output $output)
    {

        $redis = Redis::redis();
        while (true) {
            $data = $redis->lPop('rrt-givesCoupons');
            if ($data) {

                $data = json_decode($data, true);
         
                unset($data['day_number']);
                Db::name("member_coupons")->insert($data);

            } else {
                sleep(5);
            }

        }
        // 指令输出
        $output->writeln('givesCoupons');
    }


}
