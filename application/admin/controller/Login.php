<?php
/**
 * Created by PhpStorm.
 * user: juntaohuang
 * Date: 18-4-29
 * Time: 下午12:10
 */
namespace app\admin\controller;
use think\Controller;
use think\db;

class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function logincheck()
    {

    }
}