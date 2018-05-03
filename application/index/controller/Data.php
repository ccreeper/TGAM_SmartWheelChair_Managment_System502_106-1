<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;

class Data extends Controller
{
	public function historypath(){
		return $this->fetch();
	}

	public function realposition(){
		return $this->fetch();
	}

	public function getPos(){
		$data=array();
		$vid=input("param.vid");
		$res=Db::table('log')->where('vid',$vid)
		->whereTime('uploaddate','-1 minutes')->order('uploaddate desc')->limit(1)->select();
		$data['lon']=$res[0]['lon'];
		$data['lat']=$res[0]['lat'];
		echo json_encode($data);
	}
}