<?php
namespace app\index\model;
use think\Db;
use think\Model;

class Log extends Model
{
	public function isOnline($vid)
	{
		$current=Db::table('log')
			->where('vid',$vid)
			->whereTime('uploaddate','-1 minutes')
			->order('uploaddate desc')
			->limit(1)
			->select();
		if(empty($current))
			return false;
		return true;
	}

	public function getBattery($vid){
		$res=Db::table('log')
			->where('vid',$vid)
			->order('uploaddate desc')
			->limit(1)
			->field('battery')
			->select();
		$battery=$res[0]['battery'];
		return $battery;
	}

	public function getMeditation1($vid){
		$current=Db::table('log')
			->where('vid',$vid)
			->whereTime('uploaddate','-1 minutes')
			->order('uploaddate desc')
			->limit(1)
			->select();
		$meditation=$current[0]['Meditation'];
		if($meditation<=40)
			$res="疲劳";
		else if($meditation<=70)
			$res="兴奋";
		else
			$res="放松";
		return $res;
	}

	public function getMeditation2($vid,$begintime,$endtime){
		$res=Db::table('log')
			->where('vid',$vid)
			->whereTime('uploaddate','between',[$begintime,$endtime])
			->order('uploaddate')
			->field('Meditation')
			->select();
		return $res;
	}

	public function getSpeed($vid){
		$current=Db::table('log')
			->where('vid',$vid)
			->order('uploaddate desc')
			->limit(1)
			->field('speed')
			->select();
		return $current[0]['speed'];
	}

	public function getAttention($vid){
		$current=Db::table('log')
			->where('vid',$vid)
			->order('uploaddate desc')
			->limit(1)
			->field('Attention')
			->select();
		return $current[0]['Attention'];
	}

	public function getLog2($vid,$begintime,$endtime){
		$res=Db::table('log')
			->where('vid',$vid)
			->whereTime('uploaddate','between',[$begintime,$endtime])
			->order('uploaddate')
			->field('speed,bpm')
			->select();

		return $res;
	}

	public function getLog1($vid,$num){
		$res=Db::table('log')
			->where('vid',$vid)
			->field('bpm,speed')
			->order('uploaddate desc')
			->limit($num)
			->select();
		return $res;
	}

	public function getPos1($vid){
		$pos=array();
		$res=Db::table('log')
			->where('vid',$vid)
			->whereTime('uploaddate','-1 minutes')
			->order('uploaddate desc')
			->limit(1)
			->select();
		$pos['lon']=$res[0]['lon'];
		$pos['lat']=$res[0]['lat'];
		return $pos;
	}

	public function getPos2($vid,$begintime,$endtime){
		$res=Db::table('log')
			->where('vid',$vid)
			->whereTime('uploaddate','between',[$begintime,$endtime])
			->field('id,lon,lat')
			->select();

		return $res;
	}

	public function __call($name,$args){
        if($name == "getLog"){
            switch (count($args)) {
                case 2:
                    return $this->getLog1($args[0],$args[1]);
                case 3:
                    return $this->getLog2($args[0],$args[1],$args[2]);
            }
        }
        else if($name=='getPos'){
        	switch (count($args)) {
                case 1:
                    return $this->getPos1($args[0]);
                case 3:
                    return $this->getPos2($args[0],$args[1],$args[2]);
            }
        }
        else if($name=='getMeditation'){
        	switch (count($args)) {
                case 1:
                    return $this->getMeditation1($args[0]);
                case 3:
                    return $this->getMeditation2($args[0],$args[1],$args[2]);
            }
        }
    }
}