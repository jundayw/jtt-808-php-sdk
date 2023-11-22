<?php

namespace Jundayw\JTT808\MessageResponse;

class Message8106Response extends Response
{
    protected $msgId = 0x8106;
    protected $title = '查询终端指定参数';

    /**
     * 应答
     *
     * @param array $params
     * @return Message8106Response
     */
    public function response(array $params = []): Message8106Response
    {
        $this->body = pack('C', count($params));
        foreach ($params as $param) {
            $this->body .= pack('N', $param);
        }
        return $this;
    }
}