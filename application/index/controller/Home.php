<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;

class Home extends Controller
{
	public function index()
	{
		return $this->fetch();
	}

	public function isOnline()
	{
		$vid=input("param.vid");
		$current=Db::table('log')->where('vid',$vid)
		->whereTime('uploaddate','-1 minutes')->select();
		if(empty($current)){
			$result['online']=false;
		}
		else{
			$result['online']=true;
			$result['meditation']=self::getMeditation($vid);
		}
		$result['battery']=self::getBattery($vid);
		echo json_encode($result);
	}

	public function getBattery($vid){
		$res=Db::query("select * from log where vid='$vid' and uploaddate=(select max(uploaddate) from log)");
		$battery=$res[0]['battery'];
		return $battery;
	}

	public function getMeditation($vid){
		$current=Db::table('log')->where('vid',$vid)
		->whereTime('uploaddate','-1 minutes')->select();
		$meditation=$current[0]['Meditation'];
		if($meditation<=40)
			$res="疲劳";
		else if($meditation<=70)
			$res="兴奋";
		else
			$res="放松";
		return $res;
	}

	public function getLog(){
		$vid=input("param.vid");
		$res=Db::table('log')->where('vid',$vid)->field('bpm,speed')
		->order('uploaddate desc')->limit(50)->select();
		echo json_encode($res);
	}
	
	public function insertData(){
		$random_lat=self::randomFloat(20,50);
		$random_lon=self::randomFloat(20,50);
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

	function randomFloat($min = 0, $max = 1) {
		return $min + mt_rand() / mt_getrandmax() * ($max - $min);
	}
}




