<?php

namespace Jundayw\JTT808\MessageResponse;

use Jundayw\JTT808\Contracts\ResponseContract;
use Jundayw\JTT808\Message;

abstract class Response implements ResponseContract
{
    protected Message $message;

    protected $title;
    protected $msgId;
    protected $body = '';

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getMsgId()
    {
        return $this->msgId;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    public function response()
    {
        return $this;
    }

    /**
     * 数据封装
     *
     * @return string
     */
    public function encode()
    {
        // 计算消息头
        $header = pack("nnH12n",
            $this->getMsgId(),
            0
            | ($this->message->getMsgHeader()->getMsgEncryptionType() << 10)
            // @todo 暂不处理分包
            // | ($this->message->getMsgHeader()->getMsgHasSubPackage() << 13)
            | strlen($this->body),
            $this->message->getMsgHeader()->getTerminalId(),
            $this->message->getMsgHeader()->getMsgFlowId() & 0xffff,
        );

        // if ($this->message->getMsgHeader()->getMsgHasSubPackage()) {
        //     $header .= $this->message->getMsgHeader()->getSubPackage();
        // }

        // 组装消息（消息头 + 消息体）
        return $header . $this->body;
    }
}