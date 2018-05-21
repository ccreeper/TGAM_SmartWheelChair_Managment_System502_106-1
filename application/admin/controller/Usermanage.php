<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-20
 * Time: 下午6:47
 */

namespace app\admin\controller;
use app\user\model\UserInfo;
use think\Controller;
use think\Session;
use think\Db;

class Usermanage extends Controller
{
    public function index()
    {
        $admin=unserialize(Session::get("admininfo"));
        $this->assign("amdinid",$admin["adminid"]);
        $this->assign("adminname",$admin["adminname"]);
        $list=Db::name("users")->select();
        $this->assign("list",$list);
        return $this->fetch();
    }
    public function getInfo()
    {
        $uid=input("uid");
        $res=Db::name("users")->where("uid",$uid)->find();
        $report=[];
        $report["uid"]=$uid;
        $user=new UserInfo($res);
        echo json_encode($user->getInfo());
    }
    public function changeUserInfo()
    {
        $uid=input("uid");
        $res=Db::name("users")->where("uid",$uid)->find();
        $user=new UserInfo($res);
        $email=input("email");
        $uname=input("username");
        $report=[];
        $report["status"]="0";
        if(input("status")==null)
        {
            $user->Update("email",$email);
            $user->Update("username",$uname);
            $user->Update("status",0);
            $report["status"]="1";
        }
        else
        {
            $user->Update("email",$email);
            $user->Update("username",$uname);
            $user->Update("status",1);
            $report["status"]="1";
        }
        $file=request()->file("userpic");
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
                $report["status"]="1";
            }
            else
            {
                $report["status"]="0";
            }
        }
        echo json_encode($report);
    }
    public function userDel()
    {
        $uid=input("uid");
        $res=Db::name("users")->where("uid",$uid)->find();
        $user=new UserInfo($res);
        $report=[];
        if($user->getPic() != "default.jpg")
        {
            unlink("static/userpic/".$user->getPic());
        }
        if(Db::name("users")->where("uid",$uid)->delete())
        {
            $report["status"]="1";
        }
        else
        {
            $report["status"]="0";
        }
        echo json_encode($report);
    }
}