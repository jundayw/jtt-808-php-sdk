<?php

namespace Jundayw\JTT808\MessageRequest;

class Message1206Request extends Request
{
    private $msgId = 0x1206;
    private $title = '文件上传完成通知';

    public $ackFlowId = 0;
    public $code = 0;

    public function decode($bytes = null)
    {
        $bytes = $this->message->getMsgBody();

        $data = unpack("nackFlowId/Ccode", $bytes);

        $this->ackFlowId = $data['ackFlowId'];
        $this->code      = $data['code'];

        return $this;
    }
}