<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;

class Device extends Controller
{
	public function index(){
		$dev=Db::query("select * from link l,device d where l.vid=d.vid");
		$this->assign('devices',$dev);
		return $this->fetch();
	}

	public function add(){
		$vid=input('param.vid');
		$num=Db::name("link")->where('vid',$vid)->count();
		$info=Db::name("device")->where('vid',$vid)->count();

		if($num>0){
			$this->error("该设备已关联");
		}
		if($info==0)
			$this->error("设备不存在");
		if(request()->isPost()){
			$data=[
				//uid替换
				"uid"=>1,
				"vid"=>$vid,
				"linkdate"=>date("Y-m-d")
			];
			if(Db::name("link")->insert($data))
				$this->success("添加关联成功");
			else
				$this->error("添加关联失败");
		}
	}

	public function delete($vid){
		if(Db::name("link")->where('vid',$vid)->delete()){
			$this->success("删除成功");
		}else{
			$this->error("删除失败");
		}
	}

	public function track(){
		$vid=input("param.vid");
		//uid替换
		Db::name("link")->where('uid',1)->where('track',1)->update(['track'=>0]);
		Db::name("link")->where('vid',$vid)->update(['track'=>1]);
		$result["res"]=true;
		echo json_encode($result);
	}
}