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

	public function add(){
		$vid=input("param.vid");
		$content=input("param.content");
		//uid替换
		$info=Db::name("link")->where('vid',$vid)->where('uid',1)->count();
		if($info==0)
			$this->error("当前用户与该设备未关联");
		$data=[
			//uid替换
			"uid"=>1,
			"vid"=>$vid,
			"content"=>$content,
			"uploadtime"=>date("Y-m-d H:i:s")
		];
		if(Db::name("repair")->insert($data))
			$this->success("申请报修成功");
		else
			$this->error("申请报修失败");
	}

	public function addPic(){
		$pic = request()->file('pic');
		$tid=input("param.tid");
		if($pic)
		{
			foreach ($pic as $key => $value) {
				$info=$value->rule("uniqid")->validate(["size"=>3000000,"ext"=>"jpg,png,gif,jpeg"])->move(ROOT_PATH.'public'.DS.'static'.DS.'user'.DS.'image');
				if($info)
				{
					$data=[
						"tid"=>$tid,
						"pic"=>$info->getFilename(),
						"uploaddate"=>date("Y-m-d H:i:s")
					];
					if(!Db::name("repairphoto")->insert($data))
						$this->error("第".($key+1)."张设备照片上传失败");
				}
				else
					$this->$pic->getError();
			}
			$this->success("共".(count($pic))."张设备照片上传成功");
		}
	}
}