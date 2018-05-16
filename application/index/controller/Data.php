<?php
namespace app\index\controller;
use app\index\model\Log;
use app\index\model\Douglas;
use app\index\model\Device;
use think\Controller;
use think\Db;
use think\Session;

class Data extends Controller
{
	public function historypath(){
        $user=unserialize(Session::get("userinfo"));
        $dev=new Device;
		//uid替换
		$uid=$user["uid"];
		$link=$dev->getList($uid);
        $this->assign("pic",$user["pic"]);
        $this->assign("uname",$user["username"]);
		$this->assign('links',$link);
		return $this->fetch();
	}

	public function historydata(){
        $user=unserialize(Session::get("userinfo"));
        $dev=new Device;
		//uid替换
		$uid=$user["uid"];
		$link=$dev->getList($uid);
		$this->assign('links',$link);
		return $this->fetch();
	}

	public function realposition(){
		return $this->fetch();
	}

	public function getPos(){
        $user=unserialize(Session::get("userinfo"));
        $log=new Log;
		$dev=new Device;
		//uid替换
		$uid=$user["uid"];
		$vid=$dev->getTrack($uid);
		$res=$log->getPos($vid);
		echo json_encode($res);
	}

	public function searchHistoryPath(){
		$log=new Log;
		$douglas=new Douglas;
		$vid=input("param.vid");
		$begindate=input("param.begindate");
		$begintime=input("param.begintime");
		$enddate=input("param.enddate");
		$endtime=input("param.endtime");	

		$start=$begindate.' '.$begintime;
		$end=$enddate.' '.$endtime;

		$res=$log->getPos($vid,$start,$end);
		if(empty($res))
			return;
		$data=$douglas->compress($res,5);
		echo json_encode($data);
	}

    public function searchMeditation(){
    	$data=[
    		'tired'=>0,
    		'excitation'=>0,
    		'relax'=>0
    	];
    	$vid=input("param.vid");
		$begindate=input("param.begindate");
		$begintime=input("param.begintime");
		$enddate=input("param.enddate");
		$endtime=input("param.endtime");	

		$start=$begindate.' '.$begintime;
		$end=$enddate.' '.$endtime;

		$log=new Log;
		$res=$log->getMeditation($vid,$start,$end);

		if(empty($res))
			return;

		foreach ($res as $key => $value) {
			if($value['Meditation']<=40)
				$data['tired']++;
			else if($value['Meditation']<=70)
				$data['excitation']++;
			else
				$data['relax']++;
		}
		echo json_encode($data);
    }

    public function searchBpmAndSpeed(){
    	$log=new Log;
    	$vid=input("param.vid");
		$begindate=input("param.begindate");
		$begintime=input("param.begintime");
		$enddate=input("param.enddate");
		$endtime=input("param.endtime");	

		$start=$begindate.' '.$begintime;
		$end=$enddate.' '.$endtime;

		$res=$log->getLog($vid,$start,$end);
		if(empty($res))
			return;
		echo json_encode($res);
    }
}