<?php
namespace app\index\model;
use think\Db;
use think\Model;

class RepairModel extends Model
{
	public function getList($uid){
		$list=Db::table("repair")
			->alias('r')
			->where('uid',$uid)
			->join('device d','r.vid=d.vid')
			->select();
		return $list;
	}

	public function add($uid,$vid,$content){
		$info=Db::name("link")
			->where('vid',$vid)
			->where('uid',$uid)
			->count();
		if($info==0)
			return 1;
		$data=[
			"uid"=>$uid,
			"vid"=>$vid,
			"content"=>$content,
			"uploadtime"=>date("Y-m-d H:i:s")
		];
		if(Db::name("repair")->insert($data))
			return 2;
		else
			return 3;
	}

	public function addPic($tid,$info){
		$data=[
			"tid"=>$tid,
			"pic"=>$info->getFilename(),
			"uploaddate"=>date("Y-m-d H:i:s")
		];
		if(Db::name("repairphoto")->insert($data))
			return true;
		return false;
	}
}	