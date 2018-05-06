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
		$list=$dev->getList(1);
		$this->assign('devices',$list);
		return $this->fetch();
	}

	public function add(){
		if(request()->isPost()){
			$vid=input('param.vid');
			$dev=new Device;
			//uid替换
			$res=$dev->add(1,$vid);
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
}