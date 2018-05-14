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
		$dev=new Device;
		//uid替换
		$uid=1;
		$link=$dev->getList($uid);
		$this->assign('links',$link);
		return $this->fetch();
	}

	public function historydata(){
		$dev=new Device;
		//uid替换
		$uid=1;
		$link=$dev->getList($uid);
		$this->assign('links',$link);
		return $this->fetch();
	}

	public function realposition(){
		return $this->fetch();
	}

	public function getPos(){
		$log=new Log;
		$dev=new Device;
		//uid替换
		$uid=1;
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
		$vid=input("param.vid");
		$start=input("param.sdatetime");
		$end=input("param.edatetime");	

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

    public function insert(){
    	$log=new Log;
    	$vid=input("param.vid");
    	$lat=input("param.lat");
    	$lon=input("param.lon");
    	$acc=input("param.acc");
    	$dir=input("param.dir");
    	$bpm=input("param.bpm");	
    	$attention=input("param.attention");
    	$meditation=input("param.meditation");
    	$speed=input("param.speed");
    	$battery=input("param.battery");
    	$res=$log->insert($vid,$lat,$lon,$acc,$dir,$bpm,$attention,$meditation,$speed,$battery);
    	$json=[];
    	if($res==1)
    		$json['success']=1;
		else
			$json['success']=0;
		return json($json);
    }
}