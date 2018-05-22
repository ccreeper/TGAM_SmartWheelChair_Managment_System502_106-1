<?php
namespace app\index\model;
use think\Db;
use think\Model;

class Device extends Model
{
	public function add($uid,$vid){
		$num=Db::name("link")
			->where('vid',md5($vid))
			->count();
		$info=Db::name("device")
			->where('vid',md5($vid))
			->count();

		if($num>0)
			return 1;
		if($info==0)
			return 2;
		$data=[
			"uid"=>$uid,
			"vid"=>md5($vid),
			"linkdate"=>date("Y-m-d")
		];
		if(Db::name("link")->insert($data))
		{
			self::activation($vid);
			return 3;
		}
		return 4;
	}

	public function destory($vid){
		if(Db::name("link")->where('vid',$vid)->delete())
			return true;
		return false;
	}

	public function setTrack($uid,$vid){
		Db::name("link")
			->where('uid',$uid)
			->where('track',1)
			->update(['track'=>0]);
		Db::name("link")
			->where('uid',$uid)
			->where('vid',$vid)
			->update(['track'=>1]);
		return true;
	}

	public function getList($uid){
		$list=Db::table("link")
			->alias('l')
			->where('uid',$uid)
			->join('device d','l.vid=d.vid')
			->paginate(8);
		return $list;
	}

	public function getTrack($uid){
		$res=Db::table("link")
			->where('uid',$uid)
			->where('track',1)
			->field('vid')
			->find();
		return $res['vid'];
	}


	public function getVid($vname){
		$res=Db::name("device")
			->where('vname',$vname)
			->find();
		return $res;
	}

	public function activation($vid){
		$res=Db::name("device")
			->where('vid',$vid)
			->field('rdate')
			->find();
		if(!$res){
			Db::name("device")
			->where('vid',md5($vid))
			->update(['rdate'=>date("Y-m-d")]);
		}
	}
}