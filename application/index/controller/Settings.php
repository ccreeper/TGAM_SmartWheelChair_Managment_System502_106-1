<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-16
 * Time: 下午11:59
 */

namespace app\index\controller;

use app\user\model\Mail;
use app\user\model\MailTemplate;
use app\user\model\UserInfo;
use app\user\model\UserPassword;
use think\Controller;
use think\Session;
use think\Db;

class Settings extends Controller
{
    public function index()
    {
        $user=unserialize(Session::get("userinfo"));
        $this->assign("pic",$user["pic"]);
        $this->assign("uname",$user["username"]);
        $this->assign("uemail",$user["email"]);
        $this->assign("uid",$user["uid"]);
        return $this->fetch();
    }
    public function changeEmail()
    {
        $uid=input("uid");
        $nemail=input("email");
        $check=Db::name("users")->where("email",$nemail)->count();
        $report=[];
        if($check!=0)
        {
            $report["status"]="0";
        }
        else
        {
            $res=Db::name('users')->where("uid",$uid)->find();
            $userInfo=new UserInfo($res);
            if($userInfo->renewToken())
            {
                $newMailtmp=new MailTemplate($userInfo->getUsername(),$userInfo->getToken(),$nemail);
                $mail=new Mail($userInfo->getEmail(),"邮箱地址变更",$newMailtmp->toChangeE());
                $mail->sendit();
                $report["status"]="1";
            }
            else
            {
                $report["status"]="2";
            }
        }
        echo json_encode($report);
    }
    public function changePassword()
    {
        $uid=input("uid");
        $opassword=input("opassword");
        $npassword=input("npassword");
        $res=Db::name("users")->where("uid",$uid)->find();
        $userInfo=new UserInfo($res);
        $userPass=new UserPassword($res);
        $report=[];
        if($userPass->verify($opassword))
        {
            $nPass=new UserPassword($npassword,date('YmdHis'));
            $userInfo->Update("password",$nPass->salted());
            $userInfo->Update("salt",$nPass->get_salt());
            Session::clear();
            $report["status"]="1";
        }
        else
        {
            $report["status"]="0";
        }
        echo json_encode($report);
    }
    public function changeBasic()
    {
        $username=input("username");
        $uid=input("uid");
        $res=Db::name("users")->where("uid",$uid)->find();
        $user=new UserInfo($res);
        $file=request()->file("userpic");
        $report=[];
        if($file)
        {
            $info=$file->move('static/userpic/',date("YmdHis").rand(100,999));
            if($info)
            {
                if($user->getPic() != "default.jpg")
                {
                    unlink("static/userpic/".$user->getPic());
                }
                $user->Update("pic",$info->getSaveName());
                $user->Update("username",$username);
                Session::set("userinfo",serialize($user->getInfo()));
                $report["savename"]=$info->getSaveName();
                $report["status"]="1";

            }
            else
            {
                $report["status"]="0";
            }
        }
        else
        {
            $user->Update("username",$username);
            Session::set("userinfo",serialize($user->getInfo()));
            $report["savename"]="";
            $report["status"]="1";
        }
        echo json_encode($report);
    }
}