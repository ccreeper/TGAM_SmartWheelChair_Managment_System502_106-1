<?php
/**
 * Created by PhpStorm.
 * user: juntaohuang
 * Date: 18-4-29
 * Time: 下午12:06
 */

namespace app\admin\model;
use think\Db;

class AdminInfo
{
    private $adminname;
    private $password;
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
        $this->adminname=$DBdata["adminname"];
        $this->password=new AdminPassword($DBdata);
    }
    public function  __construct2($adminname,$passwd)
    {
        $this->adminname=$adminname;
        $this->password=new AdminPassword($passwd,date("YmdHis"));
//        DEBUG::
//        echo "<h1>IN AdminInfo:</h1>";
//        echo "<h2>Primery:".md5($passwd)."</h2><br>";
//        echo "<h2>Salted:".$this->password->salted()."</h2><br>";
//        echo "<h2>Salt:".$this->password->get_salt()."</h2><br>";
    }
    public function Save()
    {
        $data = [
            'adminname' => $this->adminname,
            'password' => $this->password->salted(),
            'salt' => $this->password->get_salt(),
        ];//JSON Data
        $res = Db::table('admins')->insert($data);
        if ($res)
        {
            echo "OK!";
        }
        else
        {
            echo "Err";
        }
    }

}