<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;

class Data extends Controller
{
	public function historypath(){
		return $this->fetch();
	}

	public function realposition(){
		return $this->fetch();
	}

	public function getPos(){
		$data=array();
		$vid=input("param.vid");
		$res=Db::table('log')->where('vid',$vid)
		->whereTime('uploaddate','-1 minutes')
		->order('uploaddate desc')->limit(1)->select();
		$data['lon']=$res[0]['lon'];
		$data['lat']=$res[0]['lat'];
		echo json_encode($data);
	}

	public function searchHistoryPath(){
		$vid=input("param.vid");
		$begindate=input("param.begindate");
		$begintime=input("param.begintime");
		$enddate=input("param.enddate");
		$endtime=input("param.endtime");	

		$start=$begindate.' '.$begintime;
		$end=$enddate.' '.$endtime;
		$res=Db::table('log')->where('vid',$vid)
		->whereTime('uploaddate','between',[$start,$end])
		->field('id,lon,lat')->select();
		$data=self::compress($res);
		// var_dump($data);
		echo json_encode($res);
	}

	public function compressLine($points,$endLatLngs,$start,$end,$dmax){
		if($start<$end){
			$maxDist=0;
			$currentIndex=0;
			for($i=$start+1;$i<$end;$i++){
				$currentDist=self::distToSegment($points[$start],$points[$end],$points[$i]);
				if($currentDist>$maxDist){
					$maxDist=$currentDist;
					$currentIndex=$i;
				}
			}
			if($maxDist>=$dmax){
				array_push($endLatLngs,$points[$currentIndex]);
				self::compressLine($points,$endLatLngs,$start,$currentIndex,$dmax);
				self::compressLine($points,$endLatLngs,$currentIndex,$end,$dmax);
			}
		}
		return $endLatLngs;
	}

	public function distToSegment($start,$end,$center){
		$a=abs(self::getDistance($start,$end));
		$b=abs(self::getDistance($start,$center));
		$c=abs(self::getDistance($end,$center));
		$p=($a+$b+$c)/2.0;
		$s=sqrt(abs($p*($p-$a)*($p-$b)*($p-$c)));
		$d=$s*2.0/$a;
		return $d;
	}

	public function getDistance($start,$end){
		$EARTH_RADIUS = 6370.996; // 地球半径系数
	    $PI = 3.1415926;
	    $radLat1 = $start['lat'] * $PI / 180.0;
	    $radLat2 = $end['lat'] * $PI / 180.0;
	    $radLng1 = $start['lon'] * $PI / 180.0;
	    $radLng2 = $end['lon'] * $PI /180.0;
	    $a = $radLat1 - $radLat2;
	    $b = $radLng1 - $radLng2;
	    $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
	    $distance = $distance * $EARTH_RADIUS * 1000;
	    return round($distance,5);
	}

	public function compress($points){
		$endLatLngs=array();
		$latLngPoints=self::compressLine($points,$endLatLngs,0,count($points,0)-1,0.1);
		array_push($latLngPoints,$points[0]);
		array_push($latLngPoints,end($points));
		$latLngPoints=self::my_sort($latLngPoints,'id');
		return $latLngPoints;
	}

	public function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC){
        if(is_array($arrays)){  
            foreach ($arrays as $array){  
                if(is_array($array)){  
                    $key_arrays[] = $array[$sort_key];  
                }else{  
                    return false;  
                }  
            }  
        }else{  
            return false;  
        } 
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);  
        return $arrays;  
    }
}