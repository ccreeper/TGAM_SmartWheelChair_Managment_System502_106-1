<?php
namespace app\index\controller;
use app\index\model\ShortcutModel;
use think\Controller;
use think\Db;
use think\Session;

class Shortcut extends Controller
{
	public function index(){
        $user=unserialize(Session::get("userinfo"));
        //uid替换
		$uid=$user["uid"];
		$shortcut=new ShortcutModel;
		$link=$shortcut->getLink($uid);
		$this->assign('links',$link);
		$data=[];
		foreach ($link as $key => $value) {
			$list=$shortcut->getList($value['vid']);
			$data[$key]=$list;
		}
		$this->assign('pics',$data);
        $this->assign("pic",$user["pic"]);
        $this->assign("uname",$user["username"]);
		return $this->fetch();
	}

	public function delete(){
		$shortcut=new ShortcutModel;
		$id=input("param.id");
		$pic=$shortcut->getPicById($id);
		$picname=$pic["pic"];
		if($res=$shortcut->destory($id)){
			if(file_exists(ROOT_PATH."public".DS."static".DS."user".DS."shortcut".DS.$picname))
				unlink(ROOT_PATH."public".DS."static".DS."user".DS."shortcut".DS.$picname);
			echo json_encode($res);
		}
		else{
			return;
		}
	}

	public function clear(){
		$shortcut=new ShortcutModel;
		$vid=input("param.vid");
		$pics=$shortcut->getPicByVid($vid);
		if($res=$shortcut->clear($vid)){
			foreach ($pics as $key => $value) {
				if(file_exists(ROOT_PATH."public".DS."static".DS."user".DS."shortcut".DS.$value['pic']))
					unlink(ROOT_PATH."public".DS."static".DS."user".DS."shortcut".DS.$value['pic']);
			}
			echo json_encode($res);
		}
		else{
			return;
		}
	}
}