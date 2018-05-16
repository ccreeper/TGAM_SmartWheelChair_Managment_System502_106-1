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
    public function __construct($username,$usertoken)
    {
        $this->username=$username;
        $this->usertoken=$usertoken;
    }
    public function toSignup()
    {
        $content='你好！'.$this->username.'点击以下链接激活您的账号：140.143.140.195/wheelchair/public/index.php/user/login/activate?token='.$this->usertoken.'本链接当日有效!';
        return $content;
    }
    public function toForgot()
    {
        $content='你好！'.$this->username.'点击以下链接更换您的账号密码：140.143.140.195/wheelchair/public/index.php/user/login/passwdchange?token='.$this->usertoken.'本链接当日有效!';
        return $content;
    }

}