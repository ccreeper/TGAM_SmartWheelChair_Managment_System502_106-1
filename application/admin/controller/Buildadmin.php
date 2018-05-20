<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-4
 * Time: 下午4:44
 */

namespace app\admin\controller;
use app\admin\model\AdminInfo;
use think\Controller;

class Buildadmin extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function save()
    {
        $adminname=input("adminname");
        $password=input("password");
        $newadmin=new AdminInfo($adminname,md5($password));
        $newadmin->Save();
    }
}