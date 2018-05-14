<?php
namespace app\index\controller;
use app\index\model\Device;
use think\Controller;
use think\Db;
use think\Session;

class DeviceLink extends Controller
{
	public function index(){
		$dev=new Device;
		//uid替换
		$uid=1;
		$list=$dev->getList($uid);
		$this->assign('devices',$list);
		return $this->fetch();
	}

	public function add(){
		if(request()->isPost()){
			$vid=input('param.vid');
			$dev=new Device;
			//uid替换
			$uid=1;
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
		$vid=input("param.vid");
		$dev=new Device;
		$res=$dev->setTrack(1,$vid);
		//uid替换
		$json["res"]=$res;
		echo json_encode($json);
	}

	public function getTrack(){
		$uid=input("param.uid");
		$dev=new Device;
		$res=$dev->getTrack($uid);
		$json["vid"]=$res;
		return json($json);
	}
}