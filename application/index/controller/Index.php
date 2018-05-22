<?php
namespace app\index\controller;
use app\index\model\Log;
use app\index\model\CommonUtil;
use app\index\model\Device;
use think\Controller;
use think\Db;
use think\Session;

class Index extends Controller
{
	public function index()
	{
        $user=unserialize(Session::get("userinfo"));
        $this->assign("pic",$user["pic"]);
        $this->assign("uname",$user["username"]);
        return $this->fetch();
	}

	public function getStatus()
    {
        $user=unserialize(Session::get("userinfo"));
        $log=new Log;
		$dev=new Device;
		//uid替换
		$uid=$user["uid"];
		$vid=$dev->getTrack($uid);
		if($vid){
			$result['online']=$log->isOnline($vid);
			if($result['online'])
				$result['meditation']=$log->getMeditation($vid);
			$result['battery']=$log->getBattery($vid);
			// echo json_encode($result);
			return json($result);
		}
		return ;
	}

	public function getLog(){
        $user=unserialize(Session::get("userinfo"));
        $dev=new Device;
		$log=new Log;
		//uid替换
		$uid=$user["uid"];
		$vid=$dev->getTrack($uid);
		$res=$log->getLog($vid,50);
		echo json_encode($res);
	}
	
	public function insertData(){
        $user=unserialize(Session::get("userinfo"));
        $util=new CommonUtil;
		$dev=new Device;
		$uid=$user["uid"];
		$vid=$dev->getTrack($uid);
		$random_lat=$util->randomFloat(20,50);
		$random_lon=$util->randomFloat(20,50);
		$random_bpm=rand(20,80);
		$random_attention=rand(20,90);
		$random_medition=rand(20,90);
		$random_speed=rand(10,30);
		$random_battery=rand(10,100);
		$data=[
			"vid"=>$vid, 
			"lat"=>$random_lat,
			"lon"=>$random_lon,
			"acc"=>2,
			"dir"=>14,
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

	public function logout()
    {
        Session::clear();
        echo "ok!";
    }

}




