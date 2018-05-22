<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-22
 * Time: 下午12:59
 */

namespace app\admin\model;

use think\Db;

class RepairInfo
{
    private $tid;
    private $uid;
    private $vid;
    private $content;
    private $uploadtime;
    private $status;
    public function __construct($DBdata)
    {
        $this->tid=$DBdata["tid"];
        $this->uid=$DBdata["uid"];
        $this->vid=$DBdata["vid"];
        $this->content=$DBdata["content"];
        $this->uploadtime=$DBdata["uploadtime"];
        $this->status=$DBdata["status"];
    }
    public function Update($key,$value)
    {
        $data= [
            'tid' => $this->tid,
            $key => $value
        ];
        $res=Db::name("repair")->update($data);
        return $res;
    }
    public function getStatus()
    {
        return $this->status;
    }
}