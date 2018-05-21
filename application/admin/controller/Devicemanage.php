<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-21
 * Time: 上午11:19
 */

namespace app\admin\controller;

use app\admin\model\DeviceInfo;
use think\Controller;
use think\Db;
use think\Session;

class Devicemanage extends Controller
{
    public function index()
    {
        $admin=unserialize(Session::get("admininfo"));
        $this->assign("amdinid",$admin["adminid"]);
        $this->assign("adminname",$admin["adminname"]);
        $list=Db::name("device")->select();
        $this->assign("list",$list);
        return $this->fetch();
    }
    public function getInfo()
    {
        $vid=input("vid");
        $res=Db::name("device")->where("vid",$vid)->find();
        $device=new DeviceInfo($res);
        echo json_encode($device->getInfo());
    }
    public function changeDeviceInfo()
    {
        $vid=input("vid");
        $res=Db::name("device")->where("vid",$vid)->find();
        $device=new DeviceInfo($res);
        $vname=input("vname");
        $pdate=input("pdate");
        $rdate=input("rdate");
        $report=[];
        $device->Update("vname",$vname);
        $device->Update("pdate",$pdate);
        $device->Update("rdate",$rdate);
        $report["status"]="1";
        echo json_encode($report);
    }

    public function addDeviveInfo()
    {
        $vid=input("vid");
        $vname=input("vname");
        $pdata=input("pdate");
        $nDevice=new DeviceInfo($vid,$vname,$pdata);
        $report=[];
        if($nDevice->Save())
        {
            $report["status"]="1";
        }
        else
        {
            $report["status"]="0";
        }
        echo json_encode($report);
    }
    public function deviceDel()
    {
        $vid=input("vid");
        $res=Db::name("device")->where("vid",$vid)->find();
        $device=new DeviceInfo($res);
        $report=[];
        if(Db::name("link")->where("vid",$device->getVid())->count()==0)
        {
            if(Db::name("device")->where("vid",$vid)->delete())
            {
                $report["status"]="1";
            }
            else
            {
                $report["status"]="0";
            }
        }
        else
        {
            $report["status"]="2";
        }
        echo json_encode($report);
    }
}