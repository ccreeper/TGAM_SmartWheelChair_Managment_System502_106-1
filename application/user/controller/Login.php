<?php
/**
 * Created by PhpStorm.
 * user: juntaohuang
 * Date: 18-4-29
 * Time: 下午12:10
 */
namespace app\user\controller;
use app\user\model\UserPassword;
use think\Controller;
use think\Db;

class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function signup()
    {
        return $this->fetch();
    }
    public function logincheck()
    {
        $username=input('username');
        $password=input('password');
        $captcha=input('captcha');
        $report=[];
        $res=Db::name('users')->where("email",$username)->find();
        if(!captcha_check($captcha))
        {
            $report["status"]="2";
            echo json_encode($report);
        }
        else if($res==null)
        {
            $report["status"]="0";
            echo json_encode($report);
        }
        else
        {
            $aPass = new UserPassword($res);
            if ($aPass->verify($password))
            {
                Session::set("userid", $res["uid"]);
                $report["status"] = "1";
                echo json_encode($report);
            }
            else
            {
                $report["status"] = "0";
                echo json_encode($report);
            }
        }
    }
}