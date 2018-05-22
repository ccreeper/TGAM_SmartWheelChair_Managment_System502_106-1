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
        $this->assign("pic",$user["pic"]);
        $this->assign("uname",$user["username"]);
		return $this->fetch();
	}

	public function realposition(){
        $user=unserialize(Session::get("userinfo"));
        $this->assign("pic",$user["pic"]);
        $this->assign("uname",$user["username"]);
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
		if(!$res)
			return;
		return json($res);
	}

	public function getRealtimePos(){
		$uid=input("param.uid");
		$log=new Log;
		$dev=new Device;
		$vid=$dev->getTrack($uid);
		$res=$log->getPos($vid);
		return json($res);
	}

	public function searchHistoryPath(){
		$log=new Log;
		$douglas=new Douglas;
		$dev=new Device;
		$vname=input("param.vname");
		$start=input("param.sdatetime");
		$end=input("param.edatetime");	
		$vid=$dev->getVid($vname)['vid'];

		$res=$log->getPos($vid,$start,$end);
		if(empty($res))
			return;
		$data=$douglas->compress($res,5);
		return json($data);
	}

    public function searchMeditation(){
    	$data=[
    		'tired'=>0,
    		'excitation'=>0,
    		'relax'=>0
    	];
    	$vname=input("param.vname");
		$start=input("param.sdatetime");
		$end=input("param.edatetime");	
		$dev=new Device;
		$vid=$dev->getVid($vname)['vid'];

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
    	$dev=new Device;
    	$vname=input("param.vname");
    	$vid=$dev->getVid($vname)['vid'];
		$start=input("param.sdatetime");
		$end=input("param.edatetime");	

		$res=$log->getLog($vid,$start,$end);
		if(empty($res))
			return;
		echo json_encode($res);
    }


}