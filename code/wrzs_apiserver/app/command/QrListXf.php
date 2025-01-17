<?php
declare (strict_types=1);

namespace app\command;

use app\common\device\air_switch\AirSwitchFactory;
use app\common\kg\Kg;
use think\console\Command;
use think\console\Input;

use think\console\Output;
use think\facade\Db;

class QrListXf extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('qrListXf')
            ->setDescription('the qrListXf command');
    }

    protected function execute(Input $input, Output $output)
    {
        $Beanstalkd = Kg::app()->Beanstalkd();

        while (true) {
            //读取管道队列
            $job = $Beanstalkd->watch('qrList')->reserve();
            //获取管道内容

            $data = json_decode($job->getData(), true);
            //设置二维码内容
            Kg::app()->device()->lock($data['device_sn'])->qr($data['qr']);
            //设置语言文本
            Kg::app()->device()->lock($data['device_sn'])->voice($data['voice'],$data['volume']);
            $Beanstalkd->delete($job);
        }
        // 指令输出
        $output->writeln('qrListXf');
    }


}
