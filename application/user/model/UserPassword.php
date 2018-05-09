<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-7
 * Time: 上午9:01
 */
namespace app\user\model;

class UserPassword
{
    private $password;
    private $salt;
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
        $this->password=$DBdata["password"];
        $this->salt=$DBdata["salt"];
    }
    public function __construct2($passwd,$date)
    {
        $this->salt=md5($date);
        $this->password=md5($passwd.$this->salt);
//        DEBUG::
//        echo "<h1>IN AdminPassword:</h1>";
//        echo "<h2>passwd".$passwd."</h2>";
//        echo "<h2>salt:".$this->salt."</h2>";
//        echo "<h2>Salted:".$this->password."</h2>";
    }
    public function verify($passwd)
    {
        if(md5($passwd.$this->salt)==$this->password)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function salted()
    {
        return $this->password;
    }
    public function get_salt()
    {
        return $this->salt;
    }

}