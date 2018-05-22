<?php
namespace app\index\model;
use think\Db;
use think\Model;

class ShortcutModel extends Model
{
	public function getList($vid){
		$res=Db::table("shortcut")
			->where('vid',$vid)
			->select();
		return $res;
	}

	public function getLink($uid){
		$link=Db::table("link")
			->alias('l')
			->where('uid',$uid)
			->join('device d','l.vid=d.vid')
			->select();
		return $link;
	}

	public function destory($id){
		$res=Db::name("shortcut")
			->where('id',$id)
			->delete();
		return $res;
	}

	public function getPicById($id){
		$res=Db::name("shortcut")
			->field("pic")
			->find($id);
		return $res;
	}

	public function getPicByVid($vid){
		$res=Db::name("shortcut")
			->field("pic")
			->where('vid',$vid)
			->select();
		return $res;
	}

	public function clear($vid){
		$res=Db::name("shortcut")
			->where('vid',$vid)
			->delete();
		return $res;
	}

}