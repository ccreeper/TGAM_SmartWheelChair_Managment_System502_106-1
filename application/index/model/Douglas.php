<?php
namespace app\index\model;
use think\Db;
use think\Model;
use app\index\model\CommonUtil;

class Douglas extends Model
{

	public function compress($points,$dmax){
		$util=new CommonUtil;
		$endLatLngs=array();
		$latLngPoints=$this->compressLine($points,$endLatLngs,0,count($points,0)-1,$dmax);
		array_push($latLngPoints,$points[0]);
		array_push($latLngPoints,end($points));
		$latLngPoints=$util->my_sort($latLngPoints,'id');
		return $latLngPoints;
	}

	public function compressLine($points,$endLatLngs,$start,$end,$dmax){
		if($start<$end){
			$maxDist=0;
			$currentIndex=0;
			for($i=$start+1;$i<$end;$i++){
				$currentDist=$this->distToSegment($points[$start],$points[$end],$points[$i]);
				if($currentDist>$maxDist){
					$maxDist=$currentDist;
					$currentIndex=$i;
				}
			}
			if($maxDist>=$dmax){
				array_push($endLatLngs,$points[$currentIndex]);
				$endLatLngs=$this->compressLine($points,$endLatLngs,$start,$currentIndex,$dmax);
				$endLatLngs=$this->compressLine($points,$endLatLngs,$currentIndex,$end,$dmax);
			}
		}
		return $endLatLngs;
	}

	public function distToSegment($start,$end,$center){
		$a=abs($this->getDistance($start,$end));
		$b=abs($this->getDistance($start,$center));
		$c=abs($this->getDistance($end,$center));
		$p=($a+$b+$c)/2.0;
		$s=sqrt(abs($p*($p-$a)*($p-$b)*($p-$c)));
		$d=$s*2.0/$a;
		return $d;
	}

	public function getDistance($start,$end){
		$EARTH_RADIUS = 6370.996; 
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
}