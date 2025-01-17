<?php
declare (strict_types=1);

namespace app\command;

use app\common\device\air_switch\AirSwitchFactory;
use app\common\kg\Kg;
use think\console\Command;
use think\console\Input;

use think\console\Output;
use think\facade\Db;

class QrList extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('qrList')
            ->setDescription('the qrList command');
    }

    protected function execute(Input $input, Output $output)
    {
        $Beanstalkd = Kg::app()->Beanstalkd();

        while (true) {
            //查询拥有自动更新的二维码
            $device = Db::name('device')->where(['deleted_at' => 0, 'qr_status' => 1])->select()->toArray();
            //获取今天时间
            $today = date("H", time());
            foreach ($device as $vo) {
                //获取设备今天开始的时间戳
                //如果今天开始的时间小于当前时间 那么二维码进入队列系统

                if ($today.':00' ==$vo['qr_time']) {
                    //将sn和二维码传入qrList队列

                    if ($vo['qr']) {
                        $Beanstalkd->useTube('qrList')->put(json_encode([
                            'device_sn' => $vo['device_sn'],
                            'qr' => $vo['qr'],
                            'voice' => $vo['voice'],
                            'volume' => $vo['volume']
                        ]));
                    }
                }
            }
            //休眠5分钟
            sleep(300);
        }
        // 指令输出
        $output->writeln('qrList');
    }


}
