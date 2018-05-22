<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-22
 * Time: 上午1:02
 */

namespace app\admin\controller;

use app\admin\model\RepairInfo;
use app\user\model\Mail;
use app\user\model\MailTemplate;
use think\Session;
use think\Controller;
use think\Db;

class Repairmanage extends Controller
{
    public function index()
    {
        $admin=unserialize(Session::get("admininfo"));
        $this->assign("amdinid",$admin["adminid"]);
        $this->assign("adminname",$admin["adminname"]);
        $list=Db::name("repair")->select();
        $this->assign("list",$list);
        return $this->fetch();
    }
    public function sendUser()
    {
        $tid=input("tid");
        $content=input("content");
        $res=Db::name("repair")->where("tid",$tid)->find();
        $repairInfo=new RepairInfo($res);
        $user=Db::name("users")->where("uid",$res["uid"])->find();
        $mailcontent=new MailTemplate($user["username"],$tid,$content);
        $mailsend=new Mail($user["email"],"工单回执",$mailcontent->toTickit());
        $repairInfo->Update("status",1);
        $report=[];
        if($mailsend->sendit())
        {
            $report["status"]="1";
        }
        else
        {
            $report["status"]="0";
        }
        echo json_encode($report);
    }
    public function repairDel()
    {
        $tid=input("tid");
        $res=Db::name("repair")->where("tid",$tid)->find();
        $repairInfo=new RepairInfo($res);
        $report=[];
        if($repairInfo->getStatus() == 1)
        {
            if(Db::name("repair")->where("tid",$tid)->delete())
            {
                $report["status"]="0";
            }
            else
            {
                $report["status"]="1";
            }
        }
        else
        {
            $report["status"]="2";
        }
        echo json_encode($report);
    }
}