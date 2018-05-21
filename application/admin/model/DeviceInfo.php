<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-21
 * Time: 下午12:10
 */

namespace app\admin\model;

use think\Db;

class DeviceInfo
{
    private $vid;
    private $vname;
    private $pdate;
    private $rdate;
    public function __construct()
    {
        $args=func_get_args();
        $num=count($args);
        if (method_exists($this,$f='__construct'.$num))
        {
            call_user_func_array(array($this,$f),$args);
        }
    }
    public function __construct1($DBdata)
    {
        $this->vid=$DBdata["vid"];
        $this->vname=$DBdata["vname"];
        $this->pdate=$DBdata["pdate"];
        $this->rdate=$DBdata["rdate"];
    }
    public function  __construct3($vid,$vname,$pdate)
    {
        $this->vid=$vid;
        $this->vname=$vname;
        $this->pdate=$pdate;
    }
    public function getVid()
    {
        return $this->vid;
    }
    public function getVname()
    {
        return $this->vname;
    }
    public function getPdate()
    {
        return $this->pdate;
    }
    public function getRdate()
    {
        return $this->rdate;
    }
    public function getInfo()
    {
        $arr=[];
        $arr["vid"]=$this->getVid();
        $arr["vname"]=$this->getVname();
        $arr["pdate"]=$this->getPdate();
        $arr["rdate"]=$this->getRdate();
        return $arr;
    }
    public function Update($key,$value)
    {
        $data= [
            $key => $value
        ];
        $res=Db::name("device")->where("vid",$this->getVid())->update($data);
        return $res;
    }
    public function Save()
    {
        $data = [
            'vid' => $this->vid,
            'vname' => $this->vname,
            'pdate' => $this->pdate,
        ];//JSON Data
        $res = Db::table('device')->insert($data);
        if ($res)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}