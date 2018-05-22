<?php
namespace app\index\controller;
use app\index\model\Device;
use think\Controller;
use think\Db;
use think\Session;

class DeviceLink extends Controller
{
	public function index(){
		$user=unserialize(Session::get("userinfo"));
		$dev=new Device;
		//uid替换
		$uid=$user["uid"];
		$list=$dev->getList($uid);
		$this->assign("pic",$user["pic"]);
		$this->assign("uname",$user["username"]);
		$this->assign('devices',$list);
		return $this->fetch();
	}

	public function add(){
		$user=unserialize(Session::get("userinfo"));
		$vid=input('param.vid');
		$dev=new Device;
		$uid=$user["uid"];
		$res=$dev->add($uid,$vid);

		if($res==1)
			$this->error("该设备已关联");
		else if($res==2)
			$this->error("设备不存在");
		else if($res==3)
			$this->success("添加关联成功");
		else
			$this->error("添加关联失败");
	}



	public function delete($vid){
		$dev=new Device;
		$res=$dev->destory($vid);
		if($res){
			$this->success("删除成功");
		}else{
			$this->error("删除失败");
		}
	}

	public function track(){
		$user=unserialize(Session::get("userinfo"));
		$vid=input("param.vid");
		$dev=new Device;
		$res=$dev->setTrack($user["uid"],$vid);
		//uid替换
		$json["res"]=$res;
		echo json_encode($json);
	}

	public function getVid(){
		$vname=input("param.vname");
		$dev=new Device;
		$res=$dev->getVid($vname);
		return json($res);
	}
}