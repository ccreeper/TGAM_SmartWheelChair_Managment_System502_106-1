<?php
namespace app\index\model;
use think\Db;
use think\Model;

class UserModel extends Model
{
	public function login($username,$password){
		$res=Db::table("users")
			->where('username',$username)
			->where('password',md5($password))
			->find();

		return $res;
	}

	public function regist($username,$password,$email){
		$num=Db::table("users")
			->where('username',$username)
			->count();
		if($num>0)
			return false;
		$data=[
			"username"=>$username,
			"password"=>md5($password),
			"salt"=>"qebvzdg2784gab21f2vd",
			"pic"=>"default.jpg",
			"email"=>$email,
			"status"=>0,
			"token"=>"13sdcadc23dfg1as"
		];

		if(Db::name("users")->insert($data))
			return true;
	}
}