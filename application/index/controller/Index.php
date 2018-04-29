<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function isOnline()
    {
    	$vid=input("param.vid");
    	$current=Db::table('log')->where('vid',$vid)
    		->whereTime('uploaddate','-1 minutes')->select();
    	if(empty($current)){
    		$result['online']=false;
    	}
    	else{
    		$result['online']=true;
    		$result['meditation']=self::getMeditation($vid);
    	}
    	$result['battery']=self::getBattery($vid);
    	echo json_encode($result);
    }

    public function getBattery($vid){
    	$res=Db::query("select * from log where vid='$vid' and uploaddate=(select max(uploaddate) from log)");
    	$battery=$res[0]['battery'];
    	return $battery;
    }

    public function getMeditation($vid){
		$current=Db::table('log')->where('vid',$vid)
    		->whereTime('uploaddate','-1 minutes')->select();
    	$meditation=$current[0]['Meditation'];
    	if($meditation<=40)
    		$res="疲劳";
    	else if($meditation<=70)
    		$res="兴奋";
    	else
    		$res="放松";
    	return $res;
    }
}




