<?php
namespace app\index\model;
use think\Db;
use think\Model;

class Device extends Model
{
	public function add($uid,$vid){
		$num=Db::name("link")
			->where('vid',$vid)
			->count();
		$info=Db::name("device")
			->where('vid',$vid)
			->count();

		if($num>0)
			return 1;
		if($info==0)
			return 2;
		$data=[
			"uid"=>$uid,
			"vid"=>$vid,
			"linkdate"=>date("Y-m-d")
		];
		if(Db::name("link")->insert($data))
			return 3;
		return 4;
	}

	public function destory($vid){
		if(Db::name("link")->where('vid',$vid)->delete())
			return true;
		return false;
	}

	public function setTrack($uid,$vid){
		Db::name("link")->where('uid',1)->where('track',1)->update(['track'=>0]);
		Db::name("link")->where('vid',$vid)->update(['track'=>1]);
		return true;
	}

	public function getList($uid){
		$list=Db::table("link")
			->alias('l')
			->where('uid',$uid)
			->join('device d','l.vid=d.vid')
			->select();
		return $list;
	}
}