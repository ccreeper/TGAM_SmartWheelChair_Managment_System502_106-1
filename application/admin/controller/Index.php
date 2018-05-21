<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;

class Index extends Controller
{
    public function index()
    {
        $admin=unserialize(Session::get("admininfo"));
        $this->assign("amdinid",$admin["adminid"]);
        $this->assign("adminname",$admin["adminname"]);
        return $this->fetch();
    }
    public function logout()
    {
        Session::clear();
    }
}
