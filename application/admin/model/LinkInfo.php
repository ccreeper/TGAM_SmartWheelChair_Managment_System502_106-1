<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-21
 * Time: 下午7:37
 */

namespace app\admin\model;

use think\Db;

class LinkInfo
{
    private $id;
    private $vid;
    private $uid;
    private $linkdate;
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
        $this->id=$DBdata["id"];
        $this->vid=$DBdata["vid"];
        $this->uid=$DBdata["uid"];
        $this->linkdate=$DBdata["linkdate"];
    }
    public function  __construct3($uid,$vid,$linkdate)
    {
        $this->uid=$uid;
        $this->vid=$vid;
        $this->linkdate=$linkdate;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getVid()
    {
        return $this->vid;
    }
    public function getUid()
    {
        return $this->uid;
    }
    public function getLinkdate()
    {
        return $this->linkdate;
    }
    public function getInfo()
    {
        $arr=[];
        $arr["id"]=$this->getId();
        $arr["vid"]=$this->getVid();
        $arr["uid"]=$this->getUid();
        $arr["linkdate"]=$this->getLinkdate();
        return $arr;
    }
    public function Update($key,$value)
    {
        $data= [
            'id' => $this->id,
            $key => $value
        ];
        $res=Db::name("link")->update($data);
        return $res;
    }
    public function Save()
    {
        $data = [
            'uid' => $this->uid,
            'vid' => $this->vid,
            'linkdate' => $this->linkdate,
        ];//JSON Data
        $res = Db::table('link')->insert($data);
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