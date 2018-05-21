<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-20
 * Time: 下午5:00
 */

namespace app\admin\controller;

use app\admin\model\AdminInfo;
use app\admin\model\AdminPassword;
use think\Controller;
use think\Session;
use think\Db;

class Setting extends Controller
{
    public function index()
    {
        $admin=unserialize(Session::get("admininfo"));
        $this->assign("adminid",$admin["adminid"]);
        $this->assign("adminname",$admin["adminname"]);
        return $this->fetch();
    }
    public function changePassword()
    {
        $adminid=input("adminid");
        $opassword=input("opassword");
        $npassword=input("npassword");
        $res=Db::name("admins")->where("adminid",$adminid)->find();
        $adminInfo=new AdminInfo($res);
        $adminPass=new AdminPassword($res);
        $report=[];
        if($adminPass->verify($opassword))
        {
            $nPass=new AdminPassword($npassword,date('YmdHis'));
            $adminInfo->Update("password",$nPass->salted());
            $adminInfo->Update("salt",$nPass->get_salt());
            Session::clear();
            $report["status"]="1";
        }
        else
        {
            $report["status"]="0";
        }
        echo json_encode($report);
    }
}