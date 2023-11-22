<?php

namespace Jundayw\JTT808\MessageRequest;

class Message0001Request extends Request
{
    private $msgId = 0x0001;
    private $title = '终端通用应答';

    public $ackFlowId = 0;
    public $ackId = 0;
    public $code = 0;

    public function decode($bytes = null)
    {
        $bytes = $this->message->getMsgBody();

        $data = unpack("nackFlowId/nackId/Ccode", $bytes);

        $this->ackFlowId = $data['ackFlowId'];
        $this->ackId     = $data['ackId'];
        $this->code      = $data['code'];

        return $this;
    }
}