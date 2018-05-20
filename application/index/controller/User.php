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
}