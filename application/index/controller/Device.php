<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;

class Device extends Controller
{
	public function index(){
		return $this->fetch();
	}
}