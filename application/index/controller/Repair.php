<?php
namespace app\index\controller;
use app\index\model\RepairModel;
use app\index\model\Device;
use think\Controller;
use think\Db;
use think\Session;

class Repair extends Controller
{
	public function index(){
        $user=unserialize(Session::get("userinfo"));
        $rep=new RepairModel;
        $dev=new Device;
		//uid替换
		$uid=$user["uid"];
		$list=$rep->getList($uid);
		$link=$dev->getList($uid);
		$this->assign('links',$link);
		$this->assign('repair',$list);
        $this->assign("pic",$user["pic"]);
        $this->assign("uname",$user["username"]);
		return $this->fetch();
	}

	public function add(){
        $user=unserialize(Session::get("userinfo"));
        $vid=input("param.vid");
		$content=input("param.content");
		$rep=new RepairModel;
		//uid替换
		$uid=$user["uid"];
		$info=$rep->add($uid,$vid,$content);
		if($info==1)
			$this->error("当前用户与该设备未关联");
		else if($info==2)
			$this->success("申请报修成功");
		else
			$this->error("申请报修失败");
	}

	public function addPic(){
		$pic = request()->file('pic');
		$tid=input("param.tid");
		$rep=new RepairModel;
		if($pic)
		{
			foreach ($pic as $key => $value) {
				$info=$value->rule("uniqid")->validate(["size"=>3000000,"ext"=>"jpg,png,gif,jpeg"])->move(ROOT_PATH.'public'.DS.'static'.DS.'user'.DS.'repairs');
				if($info)
				{
					$res=$rep->addPic($tid,$info);
					if(!$res)
						$this->error("第".($key+1)."张设备照片上传失败");
				}
				else
					$this->$pic->getError();
			}
			$this->success("共".(count($pic))."张设备照片上传成功");
		}
	}

	public function search(){
		$tid=input("param.tid");
		$rep=new RepairModel;
		$res=$rep->search($tid);
		if(!$res)
			return;
		echo json_encode($res);
	}

	public function getList(){
        $user=unserialize(Session::get("userinfo"));
        $rep=new RepairModel;
		$uid=$user["uid"];
		$res=$rep->getList($uid);
		var_dump($res);
		echo json_encode($res);
	}
}