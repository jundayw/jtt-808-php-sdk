<?php

namespace Jundayw\JTT808\MessageRequest;

class Message0102Request extends Request
{
    private $msgId = 0x0102;
    private $title = '终端鉴权';

    public $authCode; // 鉴权码

    public function decode($bytes = null)
    {
        $bytes          = $this->message->getMsgBody();
        $data           = unpack("a*authCode", $bytes);
        $this->authCode = mb_convert_encoding($data['authCode'], 'utf-8', 'GBK');
        return $this;
    }
}