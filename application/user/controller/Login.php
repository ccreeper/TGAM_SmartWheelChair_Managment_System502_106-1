<?php
/**
 * Created by PhpStorm.
 * user: juntaohuang
 * Date: 18-4-29
 * Time: 下午12:10
 */
namespace app\user\controller;
use app\user\model\Mail;
use app\user\model\MailTemplate;
use app\user\model\UserInfo;
use app\user\model\UserPassword;
use think\Session;
use think\Controller;
use think\Db;

class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function signup()
    {
        return $this->fetch();
    }
    public function logincheck()
    {
        $useremail=input('email');
        $password=input('password');
        $captcha=input('captcha');
        $report=[];
        $report["status"]="10086";
        $res=Db::name('users')->where("email",$useremail)->find();
        if(!captcha_check($captcha))
        {
            $report["status"]="2";
            echo json_encode($report);
        }
        else if($res==null)
        {
            $report["status"]="0";
            echo json_encode($report);
        }
        else
        {
            $aPass = new UserPassword($res);
            if ($aPass->verify($password))
            {
                if($res["status"]==1)
                {
                    Session::clear();
                    $usrInfo=new UserInfo($res);
                    $info=$usrInfo->getInfo();
                    Session::set("userinfo", serialize($info));
                    $report["status"] = "1";
                    echo json_encode($report);
                }
                else
                {
                    $report["status"] = "3";
                    echo json_encode($report);
                }
            }
            else
            {
                $report["status"] = "0";
                echo json_encode($report);
            }
        }
    }
    public function activate()
    {
        $token=$_GET['token'];
        if(strlen($token)!=32)
        {
            $this->redirect("Index/_404page");
        }
        else
        {
            $res=Db::name('users')->where("token",$token)->find();
            if($res!=null)
            {
                if(md5(date("Ymd").$res["email"])==$token)
                {
                    $data=[
                        'uid'=>$res["uid"],
                        'status'=>1
                    ];
                    if(Db::name('users')->update($data))
                    {
                        $this->success("激活成功！","Login/index");
                    }
                    else
                    {
                        $this->error("系统错误");
                    }
                }
                else
                {
                    $this->redirect("Index/_404page");
                }
            }
            else
            {
                $this->redirect("Index/_404page");
            }
        }
    }
    public function iforgot()
    {
        return $this->fetch();
    }
    public function iforgotd()
    {
        $uemail=input("email");
        $captcha=input("captcha");
        if(!captcha_check($captcha))
        {
            $report["status"]="2";
            echo json_encode($report);
        }
        else
        {
            $res=Db::name("users")->where("email",$uemail)->find();
            $report=[];
            if($res!=null)
            {
                $user=new UserInfo($res);
                $newToken=md5(date('Ymd').$user->getEmail());
                $emailcont=new MailTemplate($user->getUsername(),$newToken);
                $email=new Mail($user->getEmail(),"密码变更",$emailcont->toForgot());
                if($user->Update("token",$newToken))
                {
                    if($email->sendit())
                    {
                        $report["status"]="1";
                    }
                    else
                    {
                        $report["satatus"]="3";
                    }
                }
                else
                {
                    $report["status"]="3";
                }
            }
            else
            {
                $report["status"]="4";
            }
            echo json_encode($report);
        }

    }
    public function passwdchange()
    {
        $token=$_GET["token"];
        if(strlen($token)!=32)
        {
            $this->redirect("Index/_404page");
        }
        else
        {
            $res=Db::name('users')->where("token",$token)->find();
            if($res!=null)
            {
                if(md5(date("Ymd").$res["email"])==$token)
                {
                    $this->assign("uid",$res["uid"]);
                    return $this->fetch();
                }
                else
                {
                    $this->redirect("Index/_404page");
                }
            }
            else
            {
                $this->redirect("Index/_404page");
            }
        }
    }
    public function passwdchanged()
    {
        $passwd=input("password");
        $uid=input("uid");
        $res=Db::name("users")->where("uid",$uid)->find();
        $uop=new UserInfo($res);
        $newPass=new UserPassword($passwd,date("YmdHis"));
        $report=[];
        if($uop->Update("password",$newPass->salted()) && $uop->Update("salt",$newPass->get_salt()))
        {
            $report["status"]="1";
            echo json_encode($report);
        }
        else
        {
            $report["status"]="0";
            echo json_encode($report);
        }

    }
    public function resend()
    {
        $email=input("email");
        $res=Db::name("users")->where("email",$email)->find();
        $makeMail=new MailTemplate($res["username"],$res["token"]);
        $mail=new Mail($email,"激活",$makeMail->toSignup());
        $report=[];
        if($mail->sendit())
        {
            $report["status"]="1";
            echo json_encode($report);
        }
        else
        {
            $report["status"]="0";
            echo json_encode($report);
        }
    }
    public function nresend()
    {
        $email=input("email");
        $res=Db::name("users")->where("email",$email)->find();
        $newToken=new UserInfo($res);
        if($newToken->renewToken())
        {
            $makeMail=new MailTemplate($res["username"],$newToken->getToken());
            $mail=new Mail($email,"激活",$makeMail->toSignup());
            $report=[];
            $mail->sendit();
            $report["status"]="1";
            echo json_encode($report);
        }
        else
        {
            echo "Error!";
        }
    }

}