<?php
namespace app\user\controller;
use think\Controller;
use think\Session;

class Index extends Controller
{
    public function index()
    {
        if(!Session::has('userid'))
        {
            $this->redirect('Login/index');
        }
        else
        {
            $this->fetch();
        }
    }
}
