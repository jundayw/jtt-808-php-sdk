<?php

namespace Jundayw\JTT808\MessageResponse;

class Message8100Response extends Response
{
    protected $msgId = 0x8100;
    protected $title = '终端注册应答';

    /**
     * 应答
     *
     * @param int $code
     * 0：成功；
     * 1：车辆已被注册；
     * 2：数据库中无该车辆；
     * 3：终端已被注册；
     * 4：数据库中无该终端
     * @param $auth
     * @return Message8100Response
     */
    public function response(int $code = 0, $auth = null): Message8100Response
    {
        $this->body = pack(
            'nCa*',
            $this->message->getMsgHeader()->getMsgFlowId(),
            $code,
            mb_convert_encoding($auth, 'GBK', 'utf-8')
        );
        return $this;
    }
}