<?php


namespace app\server_api\controller;


use think\App;

class Base
{
    public $request = null;

    public function __construct(App $app)
    {
        $this->request = $app->request;

    }
}
