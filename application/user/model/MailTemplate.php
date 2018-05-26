<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-11
 * Time: 上午9:33
 */

namespace app\user\model;


class MailTemplate
{
    private $username;
    private $usertoken;
    private $otherPapram;
    public function __construct($username,$usertoken,$param="")
    {
        $this->username=$username;
        $this->usertoken=$usertoken;
        $this->otherPapram=$param;
    }
    public function toSignup()
    {
        $content='你好！'.$this->username.'点击以下链接激活您的账号：http://140.143.140.195:8080/wheelchair/public/index.php/user/login/activate?token='.$this->usertoken.' 本链接当日有效!';
        return $content;
    }
    public function toForgot()
    {
        $content='你好！'.$this->username.'点击以下链接更换您的账号密码：http://140.143.140.195:8080/wheelchair/public/index.php/user/login/passwdchange?token='.$this->usertoken.' 本链接当日有效!';
        return $content;
    }
    public function toChangeE()
    {
        $content='你好！'.$this->username.'您发出了邮箱变更请求，若不是您的操作请及时更换密码，点击以下链接更换您的账号密码：http://140.143.140.195:8080/wheelchair/public/index.php/user/login/emailchange?token='.$this->usertoken.'&email='.$this->otherPapram.' 本链接当日有效!';
        return $content;
    }
    public function toTickit()
    {
        $content='你好！'.$this->username.':\r\n您的工单:'.$this->usertoken.'的处理回复如下\r\n'.$this->otherPapram.'\r\n若您还有其他的疑问，请再次提交一个报修工单，谢谢！';
        return $content;
    }
}