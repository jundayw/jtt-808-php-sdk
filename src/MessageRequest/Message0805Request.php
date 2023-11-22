<?php

namespace Jundayw\JTT808\MessageRequest;

class Message0805Request extends Request
{
    private $msgId = 0x0805;
    private $title = '摄像头立即拍摄命令应答';

    public $ackFlowId;                     // 应答流水号:对应平台摄像头立即拍摄命令的消息流水号
    public $code;                          // 结果:0-成功;1-失败;2-通道不支持。以下字段在结果=0 时才有效。
    public $multimediaNumber = 0;          // 多媒体ID个数:拍摄成功的多媒体个数
    public $multimediaList = [];           // 多媒体ID列表

    public function decode($bytes = null)
    {
        $bytes = $this->message->getMsgBody();
        $data  = unpack("nackFlowId/Ccode", $bytes);
        if ($data['code']) {
            $this->ackFlowId = $data['ackFlowId'];
            $this->code      = $data['code'];
        } else {
            $data                   = unpack("nackFlowId/Ccode/nnumber/N*multimediaList", $bytes);
            $this->ackFlowId        = $data['ackFlowId'];
            $this->code             = $data['code'];
            $this->multimediaNumber = $data['number'];
            for ($i = 1; $i <= $this->multimediaNumber; $i++) {
                $this->multimediaList[] = $data["multimediaList{$i}"];
            }
        }
        return $this;
    }
}