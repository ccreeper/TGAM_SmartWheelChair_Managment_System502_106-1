<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;

class Index extends Controller
{
    public function index()
    {
        if(!Session::has('adminid'))
        {
            $this->redirect('Login/index');
        }
        else
        {
            return $this->fetch();
        }
    }
}
