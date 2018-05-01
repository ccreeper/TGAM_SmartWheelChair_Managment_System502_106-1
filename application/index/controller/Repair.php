<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;

class Repair extends Controller
{
	public function index(){
		$list=Db::query("select * from repair l,device d where l.vid=d.vid");		
		$this->assign('repair',$list);
		return $this->fetch();
	}
}