<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-16
 * Time: 下午10:16
 */

namespace app\index\behavior;

use think\Session;

class LoginCheck
{
    public function run()
    {
        //不需要检测是否登录的模块的控制器
        $no_check = [
            'index/user',
        ];
        //当前访问的模块,控制器
        $visit = strtolower(Request::instance()->module().'/'.Request::instance()->controller());
        //已经登录返回true
        if(Session::has("userinfo")||in_array($visit,$no_check))
        {
            return true;
        }
        else
        {
            header("Location:".url('user/login/index'));
            exit();
        }

    }
}