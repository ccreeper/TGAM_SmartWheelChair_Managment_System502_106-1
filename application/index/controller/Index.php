<?php
namespace app\index\controller;
use app\index\model\Log;
use app\index\model\CommonUtil;
use think\Controller;
use think\Db;
use think\Session;

class Index extends Controller
{
	public function index()
	{
		return $this->fetch();
	}

	public function getStatus(){
		$vid=input("param.vid");
		$log=new Log;
		$result['online']=$log->isOnline($vid);
		if($result['online'])
			$result['meditation']=$log->getMeditation($vid);
		$result['battery']=$log->getBattery($vid);
		echo json_encode($result);
	}

	public function getLog(){
		$vid=input("param.vid");
		$log=new Log;
		$res=$log->getLog($vid,50);
		echo json_encode($res);
	}
	
	public function insertData(){
		$util=new CommonUtil;
		$random_lat=$util->randomFloat(20,50);
		$random_lon=$util->randomFloat(20,50);
		$random_bpm=rand(20,80);
		$random_attention=rand(20,90);
		$random_medition=rand(20,90);
		$random_speed=rand(10,30);
		$random_battery=rand(10,100);
		$data=[
			"vid"=>"asdsdcz1314123ac",
			"lat"=>$random_lat,
			"lon"=>$random_lon,
			"bpm"=>$random_bpm,
			"Attention"=>$random_attention,
			"Meditation"=>$random_medition,
			"speed"=>$random_speed,
			"battery"=>$random_battery,
			"uploaddate"=>date("Y-m-d H:i:s")
		];
		Db::table("log")->insert($data);
		$res['result']=true;
		echo json_encode($res);
	}
}




