<?php
declare (strict_types=1);

namespace app\middleware;

use app\common\user\User;
use think\facade\Db;

class RoleAuth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $admin_user = Db::name('admin_user')->where([
            'user_id' => User::uid(),
        ])->find();
        if($admin_user['level']!=0){
            $role = Db::name('role')->where(['role_id' => $admin_user['role']])->find();
            $meun_ids = json_decode($role['access'], true);
            $menu = Db::name('menu')->where([
                'menu_id' => $meun_ids,
                'deleted_at' => 0,
                'url' => $request->url()
            ])->find();

            if(!$menu){
                return json(['status' => 0, 'error_code' => 103,'msg'=>'无权限']);
            }
        }

        return $next($request);
    }
}
