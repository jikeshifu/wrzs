<?php
declare (strict_types=1);

namespace app\middleware;

class Auth
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

        if(!$request->header('Authorization')){
            return json(['status' => 0, 'error_code' => 101,'msg'=>'token不能为空']);
        }
        $res = \app\common\user\Token::validate($request->header('Authorization'));
        if (!$res) {
            return json(['status' => 0, 'error_code' => 101,'msg'=>'token无效']);
        }


        return $next($request);
    }
}
