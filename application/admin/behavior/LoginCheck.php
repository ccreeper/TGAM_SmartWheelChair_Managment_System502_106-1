<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-16
 * Time: 下午10:41
 */

namespace app\admin\behavior;

use think\Request;
use think\Session;

class LoginCheck
{
    public function run()
    {
        //不需要检测是否登录的模块的控制器
        $no_check = [
            'admin/login',
            'admin/buildadmin',
        ];
        //当前访问的模块,控制器
        $visit = strtolower(Request::instance()->module().'/'.Request::instance()->controller());
        //已经登录返回true
        if(Session::has("admininfo")||in_array($visit,$no_check))
        {
            return true;
        }
        else
        {
            header("Location:".url('admin/login/index'));
            exit();
        }

    }
}