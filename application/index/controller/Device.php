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

	}

	public function delete($vid){
		if(Db::name("link")->where('vid',$vid)->delete()){
			$this->success("删除成功");
		}else{
			$this->error("删除失败");
		}
	}
}