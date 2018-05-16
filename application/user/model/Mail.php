<?php
/**
 * Created by PhpStorm.
 * User: juntaohuang
 * Date: 18-5-8
 * Time: 下午9:52
 */

namespace app\user\model;


class Mail
{
    private $addr;
    private $content;
    private $subject;
    public function __construct($addr,$subject,$content)
    {
        $this->addr=$addr;
        $this->subject=$subject;
        $this->content=$content;
    }
    public function sendit()
    {
        return mail($this->addr,$this->subject,$this->content);
    }

}