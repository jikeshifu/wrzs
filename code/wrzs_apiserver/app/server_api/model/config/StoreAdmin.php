<?php


namespace app\server_api\model\config;

use app\common\auth\Jwt;
use app\common\user\Token;
use think\Model;

class StoreAdmin extends Model
{

    // 设置字段信息
    public $schema = [
        'store_admin_id' => 'int',
        'mobile' => 'char',
        'pw' => 'varchar',
        'status' => 'tinyint',
        'sms_status' => 'tinyint',
//        'wxapp_id' => 'int',
        'user_name' => 'varchar',
        'store_id_arr' => 'varchar',
        'created_at' => 'int',
        'updated_at' => 'int',
        'deleted_at' => 'int',
        'join_id' => 'int',
//        "level"=>'int'
    ];
    protected $pk = 'store_admin_id';
    public $error_msg = "";
    public $Pdata = [];
    protected $json = ['store_id_arr'];
    public $loginUserInfo = [];

    public function editVerification($data)
    {
        $this->Pdata = $data;
        unset($this->Pdata['pw']);
        if (!$this->Pdata['mobile']) {
            $this->error_msg = "mobile不能为空";
            return false;
        }
        if (!isset($this->Pdata['store_admin_id']) || !$this->Pdata['store_admin_id']) {
            $this->error_msg = "store_admin_id不能为空";
            return false;
        }
        $this->Pdata['updated_at'] = time();

        return true;
    }

    public function addVerification($data)
    {
        $this->Pdata = $data;
        //生成密码
        $options = [
            'cost' => 12,
        ];

        if (!$this->Pdata['pw']) {
            $this->error_msg = "pw不能为空";
            return false;
        }
        $StoreAdmin = StoreAdmin::where([
            'mobile' => $this->Pdata['mobile'],
            'deleted_at' => 0
        ])->find();
        if ($StoreAdmin) {
            $this->error_msg = "手机号重复";
            return false;
        }
        if (!$this->Pdata['mobile']) {
            $this->error_msg = "mobile不能为空";
            return false;
        }
        //加密存储
        $this->Pdata['pw'] = password_hash($this->Pdata['pw'], PASSWORD_BCRYPT, $options);
        $this->Pdata['created_at'] = time();
        $this->Pdata['join_id'] = \app\common\user\User::uid();
        return true;
    }

    public function loginVerification($data)
    {
        $this->Pdata = $data;
        if (!$this->Pdata['pw']) {
            $this->error_msg = "密码不能为空";
            return false;
        }
        if (!$this->Pdata['mobile']) {
            $this->error_msg = "手机号不能为空";
            return false;
        }
        $StoreAdmin = StoreAdmin::where([
            'mobile' => $this->Pdata['mobile'],
            'deleted_at' => 0,
            'status' => 1
        ])->find();
        if (!$StoreAdmin) {
            $this->error_msg = "该账号不存在或被禁用";
            return false;
        }
        //判断密码是否正确
        if (!password_verify($this->Pdata['pw'], $StoreAdmin['pw'])) {
            $this->error_msg = "密码错误";
            return false;
        }
        $this->loginUserInfo['token'] = Jwt::GetToken($StoreAdmin['store_admin_id']);
        $this->loginUserInfo['user_info'] = $StoreAdmin;
        return true;
    }

    public function resetPw($data)
    {
        $this->Pdata = $data;
        //生成密码
        $options = [
            'cost' => 12,
        ];
        if (!$this->Pdata['pw']) {
            $this->error_msg = "pw不能为空";
            return false;
        }
        //加密存储
        $this->Pdata['pw'] = password_hash($this->Pdata['pw'], PASSWORD_BCRYPT, $options);
        return true;
    }

    public function deletes($data)
    {


        $this->save(['deleted_at' => time()]);
    }
}
