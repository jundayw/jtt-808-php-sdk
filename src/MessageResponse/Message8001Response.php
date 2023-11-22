<?php

namespace Jundayw\JTT808\MessageResponse;

class Message8001Response extends Response
{
    protected $msgId = 0x8001;
    protected $title = '平台通用应答';

    /**
     * 应答
     *
     * @param int $code
     * 0：成功/确认；
     * 1：失败；
     * 2：消息有误；
     * 3：不支持；
     * 4：报警处理确认；
     * @return Message8001Response
     */
    public function response(int $code = 0): Message8001Response
    {
        $this->body = pack(
            'nnC',
            $this->message->getMsgHeader()->getMsgFlowId(),
            $this->message->getMsgHeader()->getMsgId(),
            $code
        );
        return $this;
    }
}