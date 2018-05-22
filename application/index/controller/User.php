<?php
namespace app\index\controller;
use app\index\model\Log;
use app\index\model\Douglas;
use app\index\model\Device;
use app\index\model\UserModel;
use think\Controller;
use think\Db;
use think\Session;

class User extends Controller
{
	public function login(){
		$username=input("param.username");
		$password=input("param.password");
		$user=new UserModel;
		$res=$user->login($username,$password);
		return json($res);
	}

	public function regist(){
		$username=input("param.username");
		$password=input("param.password");
		$email=input("param.email");
		$user=new UserModel;
		$res=$user->regist($username,$password,$email);
		$json=[];
		if($res)
			$json["success"]=1;
		else
			$json["success"]=0;
		return json($json);
	}

	public function searchHistoryPath(){
		$log=new Log;
		$douglas=new Douglas;
		$dev=new Device;
		$vid=input("param.vid");
		$start=input("param.sdatetime");
		$end=input("param.edatetime");	

		$res=$log->getPos(md5($vid),$start,$end);
		if(empty($res))
			return;
		$data=$douglas->compress($res,5);
		return json($data);
	}

	public function getPos(){
		$log=new Log;
		$dev=new Device;
		$uid=input("uid");
		$vid=$dev->getTrack($uid);
		$res=$log->getPos($vid);
		if(!$res)
			return;
		return json($res);
	}

	public function addbytwocode(){
		$uid=input('param.uid');
		$vid=input('param.vid');
		$dev=new Device;
		$res=$dev->add($uid,$vid);
		$json=[];
		if($res==3)
			$json['success']=1;
		else
			$json['success']=0;
		return json($json);
	}

	public function getTrack(){
		$uid=input("param.uid");
		$dev=new Device;
		$res=$dev->getTrack($uid);
		$json["vid"]=$res;
		return json($json);
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
		$res=$log->insert(md5($vid),$lat,$lon,$acc,$dir,$bpm,$attention,$meditation,$speed,$battery);
		$json=[];
		if($res==1)
			$json['success']=1;
		else
			$json['success']=0;
		return json($json);
	}
}