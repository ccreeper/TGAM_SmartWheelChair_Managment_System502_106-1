<?php
/**
 * Created by PhpStorm.
 * user: juntaohuang
 * Date: 18-4-29
 * Time: 下午12:10
 */
namespace app\admin\controller;
use app\admin\model\AdminInfo;
use app\admin\model\AdminPassword;
use think\Db;
use think\Controller;
use think\Session;

class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function logincheck()
    {
        $adminname=input('adminname');
        $password=input('password');
        $captcha=input('captcha');
        $report=[];
        $res=Db::name('admins')->where("adminname",$adminname)->find();
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
            $aPass=new AdminPassword($res);
            if($aPass->verify($password))
            {
                Session::clear();
                $adminInfo=new AdminInfo($res);
                Session::set("admininfo",serialize($adminInfo->getInfo()));
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

}