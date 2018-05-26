<?php
namespace app\index\controller;
use app\index\model\Log;
use app\index\model\Douglas;
use app\index\model\Device;
use app\index\model\UserModel;
use app\user\model\UserInfo;
use app\user\model\MailTemplate;
use app\user\model\Mail;
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
		$report=[];
        $username=input("username");
        $password=input("password");
        $email=input("email");
        $newuser=new UserInfo($username,$password,$email);
        $makeMail=new MailTemplate($username,$newuser->getToken());
        $mail=new Mail($email,"激活",$makeMail->toSignup());
        if($newuser->Save())
        {
            $mail->sendit();
            $report["success"]=1;
        }
        else
        {
            $report["success"]=0;
        }
        return json($report);
	}

	public function searchHistoryPath(){
		$log=new Log;
		$douglas=new Douglas;
		$dev=new Device;
		$vid=input("param.vid");
		$start=input("param.sdatetime");
		$end=input("param.edatetime");	

		$res=$log->getPos($vid,$start,$end);
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
		$res=$log->insert($vid,$lat,$lon,$acc,$dir,$bpm,$attention,$meditation,$speed,$battery);
		$json=[];
		if($res==1)
			$json['success']=1;
		else
			$json['success']=0;
		return json($json);
	}
}