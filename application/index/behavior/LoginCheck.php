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
        //已经登录返回true
        if(Session::has("userinfo"))
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