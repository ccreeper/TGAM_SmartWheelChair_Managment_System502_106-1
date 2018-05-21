<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-21
 * Time: 下午5:30
 */

namespace app\admin\controller;

use app\admin\model\LinkInfo;
use think\session;
use think\Controller;
use think\Db;

class Devicelink extends Controller
{
    public function index()
    {
        $admin=unserialize(Session::get("admininfo"));
        $this->assign("amdinid",$admin["adminid"]);
        $this->assign("adminname",$admin["adminname"]);
        $list=Db::name("link")->select();
        $this->assign("list",$list);
        return $this->fetch();
    }
    public function getInfo()
    {
        $id=input("id");
        $res=Db::name("link")->where("id", $id)->find();
        $linkinfo=new LinkInfo($res);
        echo json_encode($linkinfo->getInfo());
    }
    public function changeLinkInfo()
    {
        $id=input("id");
        $res=Db::name("link")->where("id",$id)->find();
        $link=new LinkInfo($res);
        $uid=input("uid");
        $vid=input("vid");
        $linkdate=input("linkdate");
        $report=[];
        if($uid!=$link->getUid() || $vid!=$link->getVid())
        {
            $numofLink=Db::name("link")->where("vid",$vid)->count();
            $numofuser=Db::name('users')->where("uid",$uid)->count();
            if($numofLink!=0)
            {
                $report["status"]="2";
            }
            else if($numofuser==0)
            {
                $report["status"]="3";
            }
            else
            {
                $link->Update("uid",$uid);
                $link->Update("vid",$vid);
                $link->Update("linkdate",$linkdate);
                $report["status"]="1";
            }
        }
        else
        {
            $link->Update("uid",$uid);
            $link->Update("vid",$vid);
            $link->Update("linkdate",$linkdate);
            $report["status"]="1";
        }
        echo json_encode($report);
    }
    public function addLinkInfo()
    {
        $uid=input("uid");
        $vid=input("vid");
        $linkdata=input("linkdate");
        $nLink=new LinkInfo($uid,$vid,$linkdata);
        $report=[];
        $numofLink=Db::name("link")->where("vid",$vid)->count();
        $numofuser=Db::name('users')->where("uid",$uid)->count();
        if($numofLink!=0)
        {
            $report["status"]="2";
        }
        else if($numofuser==0)
        {
            $report["status"]="3";
        }
        else
        {
            if($nLink->Save())
            {
                $report["status"]="1";
            }
            else
            {
                $report["status"]="0";
            }
        }
        echo json_encode($report);
    }
    public function LinkDel()
    {
        $id=input("id");
        $report=[];
        if(Db::name("link")->where("id",$id)->delete())
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