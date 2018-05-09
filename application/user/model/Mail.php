<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-8
 * Time: 下午9:52
 */

namespace app\user\model;


class Mail
{
    public function __construct()
    {
        $args=func_get_args();
        $num=count($args);
        if (method_exists($this,$f='__construct'.$num))
        {
            call_user_func_array(array($this,$f),$args);
        }
    }


}