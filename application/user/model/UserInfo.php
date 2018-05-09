<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-7
 * Time: 上午9:01
 */

namespace app\user\model;
use think\Db;

class UserInfo
{
    private $username;
    private $password;
    private $pic;
    private $email;
    private $status;
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
        $this->username=$DBdata["username"];
        $this->password=new UserPassword($DBdata);
        $this->pic=$DBdata["pic"];
        $this->email=$DBdata["email"];
        $this->status=$DBdata["status"];
    }
    public function  __construct3($username,$passwd,$email)
    {
        $this->username=$username;
        $this->password=new UserPassword($passwd,date("YmdHis"));
        $this->pic="default.jpg";
        $this->email=$email;
//        DEBUG::
//        echo "<h1>IN userInfo:</h1>";
//        echo "<h2>Primery:".md5($passwd)."</h2><br>";
//        echo "<h2>Salted:".$this->password->salted()."</h2><br>";
//        echo "<h2>Salt:".$this->password->get_salt()."</h2><br>";
//        die("HERE");
    }
    public function Save()
    {
        $data = [
            'username' => $this->username,
            'password' => $this->password->salted(),
            'salt' => $this->password->get_salt(),
            'pic' => $this->pic,
            'email' => $this->email,
            'status' => 0
        ];//JSON Data
        $res = Db::table('users')->insert($data);
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