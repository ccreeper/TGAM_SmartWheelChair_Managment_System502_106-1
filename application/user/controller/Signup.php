<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-7
 * Time: 上午11:37
 */

namespace app\user\controller;
use app\user\model\UserInfo;
use think\Controller;
use think\Db;

class Signup extends Controller
{
    public function check_distinct()
    {
        $res=Db::name("users")->where("email",$_POST["email"])->find();
        $report=[];
        if($res!=null)
        {
            $report["status"]="0";
        }
        else
        {
            $report["status"]="1";
        }
        echo json_encode($report);
    }
    public function signup()
    {
        $report=[];
        $username=input("username");
        $password=input("passoword");
        $email=input("email");
        $captcha=input("captcha");
        $newuser=new UserInfo($username,$password,$email);
        if(!captcha_check($captcha))
        {
            $report["status"]="2";
            echo json_encode($report);
        }
        else if($newuser->Save())
        {
            $report["status"]="1";
            echo json_encode($report);
        }
        else
        {
            $report["status"]="0";
            echo json_encode($report);
        }
    }
}