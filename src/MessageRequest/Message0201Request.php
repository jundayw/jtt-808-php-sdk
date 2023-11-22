<?php

namespace Jundayw\JTT808\MessageRequest;

class Message0201Request extends Message0200Request
{
    private $msgId = 0x0201;
    private $title = '位置信息查询应答';

    public $ackFlowId = 0;

    public function decode($bytes = null)
    {
        $bytes = $this->message->getMsgBody();

        $data            = unpack("nackFlowId", $bytes);
        $this->ackFlowId = $data['ackFlowId'];
        return parent::decode(substr($bytes, 2));
    }
}